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

        // Find payment by matching IDs from webhook
        $mayarId = $data['id'] ?? $data['transactionId'] ?? null;
        $transactionId = $data['transactionId'] ?? null;
        $productId = $data['productId'] ?? null;
        $paymentLinkId = $data['paymentLinkId'] ?? null;
        $linkId = $data['linkId'] ?? null;
        $status = $data['status'] ?? null;

        $payment = null;

        // 1. Match by productId
        if ($productId) {
            $payment = Payment::where('gateway_id', $productId)->orWhere('invoice_id', $productId)->first();
            if ($payment) Log::info('Webhook match found: productId', ['id' => $productId]);
        }

        // 2. Match by mayarId
        if (!$payment && $mayarId) {
            $payment = Payment::where('gateway_id', $mayarId)->orWhere('invoice_id', $mayarId)->first();
            if ($payment) Log::info('Webhook match found: mayarId', ['id' => $mayarId]);
        }

        // 3. Match by transactionId explicitly
        if (!$payment && $transactionId) {
            $payment = Payment::where('gateway_id', $transactionId)->orWhere('invoice_id', $transactionId)->first();
            if ($payment) Log::info('Webhook match found: transactionId', ['id' => $transactionId]);
        }

        // 4. Fallback: Parse Order ID from description
        if (!$payment) {
            $desc = $data['productDescription'] ?? $data['description'] ?? '';
            Log::info('Webhook parsing description', ['desc' => $desc]);
            if (preg_match('/Order\s+#(ETR26-[0-9A-Z-]+)/i', $desc, $matches)) {
                $orderId = $matches[1];
                Log::info('Webhook matched Order ID from regex', ['orderId' => $orderId]);
                $payment = Payment::where('order_id', $orderId)->first();
                if ($payment) Log::info('Webhook match found: Order ID from description');
            }
        }

        // 5. Fallback: try by other IDs in the payload
        if (!$payment && $paymentLinkId) {
            $payment = Payment::where('invoice_id', $paymentLinkId)->first();
            if ($payment) Log::info('Webhook match found: paymentLinkId');
        }
        if (!$payment && $linkId) {
            $payment = Payment::where('invoice_id', $linkId)->first();
            if ($payment) Log::info('Webhook match found: linkId');
        }

        // 6. Last resort: match by customer email + amount
        if (!$payment) {
            $customerEmail = $data['customerEmail'] ?? null;
            $amount = $data['amount'] ?? null;
            if ($customerEmail && $amount) {
                $payment = Payment::where('amount', $amount)
                    ->where('status', 'pending')
                    ->whereHas('participant', function ($q) use ($customerEmail) {
                        $q->where('email', $customerEmail);
                    })
                    ->latest()
                    ->first();
                if ($payment) Log::info('Webhook match found: Email + Amount');
            }
        }

        if (!$payment) {
            Log::warning('Mayar webhook: Payment not found with any strategy', [
                'mayarId' => $mayarId,
                'transactionId' => $transactionId,
                'productId' => $productId,
                'description' => $data['productDescription'] ?? $data['description'] ?? 'N/A',
                'customerEmail' => $data['customerEmail'] ?? 'N/A'
            ]);
            return response()->json(['error' => 'Payment not found'], 404);
        }

        Log::info('Mayar webhook: Payment found', ['payment_id' => $payment->id, 'order_id' => $payment->order_id]);

        DB::transaction(function () use ($payment, $event, $status, $data, $payload, $mayarId) {
            $statusCode = $data['statusCode'] ?? null;
            $statusStr = $status ?? $statusCode ?? '';

            $oldStatus = $payment->status;

            // Map Mayar status to our internal status
            if ($event === 'payment.received') {
                $paymentStatus = 'paid';
            } else {
                $paymentStatus = match (strtoupper((string) $statusStr)) {
                    'SUCCESS', 'PAID', 'SETTLEMENT' => 'paid',
                    'FAILED', 'DENY', 'CANCEL' => 'failed',
                    'EXPIRED', 'EXPIRE' => 'expired',
                    'REFUND' => 'refunded',
                    default => 'pending',
                };
            }

            $paymentMethod = $data['paymentMethod'] ?? null;

            $payment->update([
                'status' => $paymentStatus,
                'gateway_id' => $mayarId,
                'payment_method' => $paymentMethod,
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
                'webhook_payload' => $payload,
            ]);

            // Increment promotion used_count and decrement quota if payment just became paid
            if ($paymentStatus === 'paid' && $oldStatus !== 'paid') {
                if ($payment->promotion_id) {
                    $promo = $payment->promotion;
                    $promo->increment('used_count');
                    if ($promo->quota !== null && $promo->quota > 0) {
                        $promo->decrement('quota');
                    }
                }
            }

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
