<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Participant $participant)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Payment Confirmed - Era Trail Run 2026');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.payment-confirmation');
    }
}