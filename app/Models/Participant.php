<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasUuids;

    protected $fillable = [
        'event_id',
        'category_id',
        'user_id',
        'full_name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'age',
        'identity_number',
        'nationality',
        'country_id',
        'province_id',
        'city_id',
        'address',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        'jersey_size',
        'community',
        'medical_conditions',
        'payment_status',
        'bib_number',
        'checked_in',
        'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'checked_in' => 'boolean',
            'checked_in_at' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function hasBib(): bool
    {
        return !empty($this->bib_number);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class, 'participant_id');
    }
}
