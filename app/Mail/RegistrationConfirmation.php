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
        $latestPayment = $this->participant->latestPayment;
        $finalAmount = (float) (optional($latestPayment)->final_amount ?? (optional($latestPayment)->amount ?? 0));
        $paymentMethod = optional($latestPayment)->payment_method;
        $activePaymentGateway = config('services.payment');

        // Only attach QRIS if:
        // 1. Amount is > 0
        // 2. Local payment record status is not midtrans (not manual)
        // 3. Global gateway is not midtrans
        if ($finalAmount > 0 && strtolower($paymentMethod) !== 'midtrans' && strtolower($activePaymentGateway) !== 'midtrans' && strtolower($paymentMethod) !== 'discount_full') {
            return [
                Attachment::fromPath(public_path('qris.webp'))
                    ->as('qris-pembayaran.webp')
                    ->withMime('image/webp'),
            ];
        }

        return [];
    }
}