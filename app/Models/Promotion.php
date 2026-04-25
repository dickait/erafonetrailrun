<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'discount_type', 'discount_value',
        'start_date', 'end_date', 'quota', 'used_count'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_value' => 'decimal:2',
    ];


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function isValidForCategory($categoryId)
    {
        // If the promotion has no categories associated, it's global
        if ($this->categories()->count() === 0) {
            return true;
        }

        // Otherwise, it must be in the associated categories
        return $this->categories()->where('categories.id', $categoryId)->exists();
    }

    public function isValid()
    {
        $now = now();
        if ($this->start_date && $now->lt($this->start_date)) return false;
        if ($this->end_date && $now->gt($this->end_date)) return false;
        
        if ($this->quota !== null) {
            if ($this->quota <= 0) return false;
        }
        
        return true;
    }
}
