<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
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

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'checked_in' => 'boolean',
            'checked_in_at' => 'datetime',
            'rpc' => 'boolean',
        ];
    }
}
