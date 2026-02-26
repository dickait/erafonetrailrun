<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'regencies';
    protected $fillable = ['id', 'province_id', 'name'];
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
