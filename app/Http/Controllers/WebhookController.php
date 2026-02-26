<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleMayar(Request $request)
    {
        // Log incoming webhook for debugging
        Log::info('Mayar webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        // Verify webhook signature
        $secret = config('services.mayar.webhook_secret');
        if ($secret) {
            $signature = $request->header('x-callback-signature')
                ?? $request->header('x-mayar-signature')
                ?? $request->header('X-Callback-Signature')
                ?? $request->header('X-Mayar-Signature');

            $expectedSignature = hash_hmac('sha256', $request->getContent(), $secret);

            if (!hash_equals($expectedSignature, $signature ?? '')) {
                Log::warning('Mayar webhook signature verification failed', [
                    'expected' => $expectedSignature,
                    'received' => $signature,
                ]);
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        $payload = $request->all();
        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];

        // Only process payment.received events
        if ($event !== 'payment.received') {
            Log::info('Mayar webhook ignored (non-payment event)', ['event' => $event]);
            return response()->json(['message' => 'Event ignored']);
        }

        // Find payment by Mayar payment ID or paymentLinkId
        $mayarId = $data['id'] ?? null;
        $paymentLinkId = $data['paymentLinkId'] ?? null;
        $status = $data['status'] ?? null;

        $payment = null;

        // Try to find by invoice_id (which stores Mayar's payment ID)
        if ($mayarId) {
            $payment = Payment::where('invoice_id', $mayarId)->first();
        }

        // Fallback: try paymentLinkId
        if (!$payment && $paymentLinkId) {
            $payment = Payment::where('invoice_id', $paymentLinkId)->first();
        }

        if (!$payment) {
            Log::warning('Mayar webhook: Payment not found', [
                'mayar_id' => $mayarId,
                'paymentLinkId' => $paymentLinkId,
            ]);
            return response()->json(['error' => 'Payment not found'], 404);
        }

        DB::transaction(function () use ($payment, $status, $data, $payload) {
            // Map Mayar status to our internal status
            $paymentStatus = match (strtoupper($status ?? '')) {
                    'SUCCESS', 'PAID', 'SETTLEMENT' => 'paid',
                    'FAILED', 'DENY', 'CANCEL' => 'failed',
                    'EXPIRED', 'EXPIRE' => 'expired',
                    'REFUND' => 'refunded',
                    default => 'pending',
                };

            $payment->update([
                'status' => $paymentStatus,
                'payment_method' => $data['paymentMethod'] ?? $data['payment_method'] ?? null,
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

            Log::info('Mayar webhook: Payment updated', [
                'payment_id' => $payment->id,
                'status' => $paymentStatus,
                'participant_id' => $payment->participant_id,
            ]);
        });

        return response()->json(['message' => 'Webhook processed successfully']);
    }
}
