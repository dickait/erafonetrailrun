<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'participant_id', 'invoice_id', 'payment_link',
        'amount', 'status', 'payment_method',
        'paid_at', 'webhook_payload',
        'discount_code_id', 'discount_amount', 'final_amount',
    ];

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
