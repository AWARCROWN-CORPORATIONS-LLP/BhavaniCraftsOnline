<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    // The product_images table has no created_at / updated_at columns
    public $timestamps = false;

    protected $fillable = ['product_id', 'image_url', 'is_main'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
