<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable = ['code', 'promotion_id', 'usage_limit', 'used_count'];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function isValid()
    {
        if (!$this->promotion->isValid()) return false;
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) return false;
        return true;
    }
}
