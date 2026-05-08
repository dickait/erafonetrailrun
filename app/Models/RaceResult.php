<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RaceResult extends Model
{
    use HasUuids;

    protected $fillable = [
        'participant_id',
        'bib_number',
        'gun_time',
        'net_time',
        'distance_km',
        'gender',
        'age_category',
        'rank_overall',
        'rank_category',
        'rank_group',
        'is_podium',
        'status',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
