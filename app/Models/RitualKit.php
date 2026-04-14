<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RitualKit extends Model
{
    use \App\Traits\InvalidatesHomeCache;
    protected $fillable = ['name', 'slug', 'description', 'price', 'display_image', 'is_active'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'ritual_kit_product');
    }
}
