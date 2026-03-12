<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPrice extends Model
{
    protected $fillable = ['category_id', 'pax', 'price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
