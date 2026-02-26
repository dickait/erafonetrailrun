<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'event_id', 'name', 'slug', 'description',
        'price', 'early_bird_price', 'early_bird_deadline',
        'quota', 'distance_km', 'color',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'early_bird_price' => 'decimal:2',
            'early_bird_deadline' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function getCurrentPrice(): float
    {
        if ($this->early_bird_price && $this->early_bird_deadline && now()->lte($this->early_bird_deadline)) {
            return (float) $this->early_bird_price;
        }
        return (float) $this->price;
    }

    public function getRemainingQuota(): int
    {
        $registered = $this->participants()->whereIn('payment_status', ['pending', 'paid'])->count();
        return max(0, $this->quota - $registered);
    }

    public function isEarlyBird(): bool
    {
        return $this->early_bird_price && $this->early_bird_deadline && now()->lte($this->early_bird_deadline) && $this->early_bird_price < $this->price;
    }
}