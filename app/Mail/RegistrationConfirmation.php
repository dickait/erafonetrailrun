<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Participant $participant)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Konfirmasi Pendaftaran - Era Trail Run 2026');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.registration-confirmation');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath(public_path('qris.webp'))
                ->as('qris-pembayaran.webp')
                ->withMime('image/webp'),
        ];
    }
}