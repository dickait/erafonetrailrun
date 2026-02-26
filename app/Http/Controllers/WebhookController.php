<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleMayar(Request $request)
    {
        // Verify webhook signature
        $secret = config('services.mayar.webhook_secret');
        if ($secret) {
            $signature = $request->header('X-Mayar-Signature');
            $expectedSignature = hash_hmac('sha256', $request->getContent(), $secret);

            if (!hash_equals($expectedSignature, $signature ?? '')) {
                Log::warning('Mayar webhook signature verification failed');
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        $payload = $request->all();
        $invoiceId = $payload['invoice_id'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$invoiceId || !$status) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $payment = Payment::where('invoice_id', $invoiceId)->first();

        if (!$payment) {
            Log::warning("Payment not found for invoice: {$invoiceId}");
            return response()->json(['error' => 'Payment not found'], 404);
        }

        DB::transaction(function () use ($payment, $status, $payload) {
            $paymentStatus = match ($status) {
                    'paid', 'success', 'settlement' => 'paid',
                    'failed', 'deny', 'cancel' => 'failed',
                    'expired', 'expire' => 'expired',
                    'refund' => 'refunded',
                    default => $status,
                };

            $payment->update([
                'status' => $paymentStatus,
                'payment_method' => $payload['payment_method'] ?? null,
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
                'webhook_payload' => $payload,
            ]);

            // Update participant payment status
            $participantStatus = match ($paymentStatus) {
                    'paid' => 'paid',
                    'failed' => 'failed',
                    'refunded' => 'refunded',
                    default => 'pending',
                };

            $payment->participant->update([
                'payment_status' => $participantStatus,
            ]);
        });

        return response()->json(['message' => 'Webhook processed']);
    }
}
