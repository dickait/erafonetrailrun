<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'event_id', 'name', 'slug', 'description',
        'quota', 'distance_km', 'color',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function prices()
    {
        return $this->hasMany(CategoryPrice::class);
    }

    public function getBasePrice($pax = 1): float
    {
        $categoryPrice = $this->prices()->where('pax', $pax)->first();
        return (float) ($categoryPrice->price ?? 0);
    }

    public function getCurrentPrice($pax = 1): float
    {
        $price = $this->getBasePrice($pax);
        if ($price <= 0) return 0;
        
        // Let's check for an active early bird promo
        $earlyBird = $this->getActivePromotion('earlybird');
        if ($earlyBird) {
            if ($earlyBird->discount_type == 'fixed') {
                $price -= (float) $earlyBird->discount_value;
            } else {
                $price -= $price * ((float) $earlyBird->discount_value / 100);
            }
        }

        return max(0, $price);
    }

    public function getActivePromotion($type)
    {
        return Promotion::where('type', $type)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->first();
    }

    public function getRemainingQuota(): int
    {
        $registered = $this->participants()->whereIn('payment_status', ['pending', 'paid'])->count();
        return max(0, $this->quota - $registered);
    }

    public function isEarlyBirdActive(): bool
    {
        $promo = $this->getActivePromotion('earlybird');
        return !is_null($promo);
    }

    public function getEarlyBirdDiscount(): float
    {
        $promo = $this->getActivePromotion('earlybird');
        if (!$promo) return 0;

        return (float) $promo->discount_value;
    }
}