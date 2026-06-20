<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'location',
        'banner_image', 'event_date', 'registration_open',
        'registration_close', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'registration_open' => 'datetime',
            'registration_close' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function isRegistrationOpen(): bool
    {
        if (!config('services.is_open', true)) {
            return false;
        }

        $now = now();
        return $this->is_active
            && ($this->registration_open === null || $now->gte($this->registration_open))
            && ($this->registration_close === null || $now->lte($this->registration_close));
    }
}
