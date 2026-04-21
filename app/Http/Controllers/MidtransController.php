<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create Midtrans Snap Token
     */
    public function createToken(Request $request)
    {
        $participant = Participant::with(['category', 'latestPayment'])->findOrFail($request->participant_id);
        $payment = $participant->latestPayment;

        if (!$payment) {
            return response()->json(['error' => 'Payment record not found'], 404);
        }

        $paymentType = 'bank_transfer'; // Forced to BNI VA
        $fee = $this->calculateFee($payment->final_amount, $paymentType);
        $grossAmount = (int) ($payment->final_amount + $fee);

        $params = [
            'transaction_details' => [
                'order_id' => $payment->order_id . '-' . time(),
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $participant->full_name,
                'email' => $participant->email,
                'phone' => $participant->phone,
            ],
            'item_details' => [
                [
                    'id' => $participant->category_id,
                    'price' => (int) $payment->final_amount,
                    'quantity' => 1,
                    'name' => $participant->category->name,
                ],
                [
                    'id' => 'fee',
                    'price' => (int) $fee,
                    'quantity' => 1,
                    'name' => 'Payment Service Fee',
                ]
            ],
            'callbacks' => [
                'finish' => route('registration.payment', ['email' => $participant->email]),
                'unfinish' => route('registration.payment', ['email' => $participant->email]),
                'error' => route('registration.payment', ['email' => $participant->email]),
            ]
        ];

        // Restrict to selected payment method if provided
        if ($paymentType) {
            $params['enabled_payments'] = $this->getEnabledPayments($paymentType);
        }

        try {
            $snapToken = Snap::getSnapToken($params);

            $payment->update([
                'payment_method' => $paymentType,
                'fee_amount' => $fee,
                'gateway_id' => $snapToken,
                'webhook_payload' => null,
            ]);

            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function calculateFee($amount, $type)
    {
        $fee = 0;
        switch ($type) {
            case 'qris':
                $fee = $amount * 0.007;
                break;
            case 'bank_transfer':
                $fee = 4000;
                break;
            case 'gopay':
                $fee = $amount * 0.02;
                break;
        }

        if ($fee > 0) {
            // Add 11% PPN to the fee
            $fee = $fee + ($fee * 0.11);
        }

        return ceil($fee);
    }

    private function getEnabledPayments($type)
    {
        // Force to ONLY BNI Virtual Account
        return ['bni_va'];
    }

    /**
     * Midtrans Webhook Notification
     */
    public function webhook(Request $request)
    {
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id_from_notif = $notif->order_id;
        $fraud = $notif->fraud_status;

        Log::info('Midtrans Webhook received', [
            'order_id' => $order_id_from_notif,
            'status' => $transaction,
            'type' => $type
        ]);

        $payment = Payment::where('order_id', $order_id_from_notif)->first();

        // If not found, try to extract base order ID (removes the timestamp suffix)
        if (!$payment) {
            $parts = explode('-', $order_id_from_notif);
            if (count($parts) >= 2) {
                // If it was ETR26-00001 or ETR26-00001-TIMESTAMP
                // $parts[0] = ETR26, $parts[1] = 00001
                $base_order_id = $parts[0] . '-' . $parts[1];
                $payment = Payment::where('order_id', $base_order_id)->first();
            }
        }

        if (!$payment) {
            Log::error('Payment not found for Order ID: ' . $order_id_from_notif);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        DB::transaction(function () use ($payment, $transaction, $fraud, $type, $notif, $request) {
            $oldStatus = $payment->status;

            // Jika status sudah 'paid', jangan update lagi dari webhook attempt lama
            if ($oldStatus === 'paid') {
                Log::info('Payment already paid, ignoring webhook for Order ID: ' . $notif->order_id);
                return;
            }

            $paymentStatus = 'pending';

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $paymentStatus = 'pending';
                    } else {
                        $paymentStatus = 'paid';
                    }
                }
            } elseif ($transaction == 'settlement') {
                $paymentStatus = 'paid';
            } elseif ($transaction == 'pending') {
                $paymentStatus = 'pending';
            } elseif ($transaction == 'deny') {
                $paymentStatus = 'failed';
            } elseif ($transaction == 'expire') {
                $paymentStatus = 'expired';
            } elseif ($transaction == 'cancel') {
                $paymentStatus = 'failed';
            }

            $payment->update([
                'status' => $paymentStatus,
                'gateway_id' => $notif->transaction_id ?? $payment->gateway_id,
                'payment_method' => $type,
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
                'webhook_payload' => $request->all(),
            ]);

            // If paid, update participant status and handle promotion
            if ($paymentStatus === 'paid' && $oldStatus !== 'paid') {
                $payment->participant->update(['payment_status' => 'paid']);

                if ($payment->promotion_id) {
                    $promo = $payment->promotion;
                    $promo->increment('used_count');
                    if ($promo->quota !== null && $promo->quota > 0) {
                        $promo->decrement('quota');
                    }
                }

                // Optional: Send payment confirmation email
                try {
                    \Illuminate\Support\Facades\Mail::to($payment->participant->email)
                        ->queue(new \App\Mail\PaymentConfirmation($payment->participant));
                } catch (\Exception $e) {
                    Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
                }
            }
        });

        return response()->json(['message' => 'OK']);
    }
}
