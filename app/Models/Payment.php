<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'participant_id', 'order_id', 'invoice_id', 'payment_link',
        'amount', 'status', 'payment_method',
        'paid_at', 'webhook_payload',
        'discount_code_id', 'discount_amount', 'final_amount',
    ];

    public static function generateOrderId()
    {
        $prefix = 'ETR26-';
        $lastPayment = self::where('order_id', 'LIKE', $prefix . '%')
            ->orderBy('order_id', 'desc')
            ->first();

        if (!$lastPayment) {
            $number = 1;
        } else {
            $lastNumber = (int) str_replace($prefix, '', $lastPayment->order_id);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'webhook_payload' => 'array',
        ];
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }
}
