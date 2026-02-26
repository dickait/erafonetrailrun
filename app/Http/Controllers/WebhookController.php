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

        // Verify webhook using x-callback-token header
        $secret = config('services.mayar.webhook_secret');
        if ($secret) {
            $callbackToken = $request->header('x-callback-token');

            if (!$callbackToken || $callbackToken !== $secret) {
                Log::warning('Mayar webhook token verification failed', [
                    'expected' => $secret,
                    'received' => $callbackToken,
                ]);
                return response()->json(['error' => 'Invalid token'], 403);
            }
        }

        $payload = $request->all();
        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];

        // Ignore test events
        if ($event === 'testing') {
            Log::info('Mayar webhook test received successfully');
            return response()->json(['message' => 'Test webhook received']);
        }

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
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
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
