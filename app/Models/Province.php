<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['id', 'name'];
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
