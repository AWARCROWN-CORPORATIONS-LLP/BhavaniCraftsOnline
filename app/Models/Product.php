<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Product extends Model
{
    use Auditable;
    public $timestamps = false;

    protected $fillable = [
        'product_name', 'telugu_name', 'slug', 'price', 'mrp', 'discount_percent', 'gst_rate',
        'short_description', 'full_description', 'video_url',
        'category_id', 'material_type', 'festival_use', 'made_type',
        'customizable', 'requires_shipping', 'replacement_available',
        'replacement_conditions', 'product_code', 'listed_status', 'stock', 'user_id',
        'model_3d', 'model_usdz'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (!$product->slug) {
                $product->slug = \Illuminate\Support\Str::slug($product->product_name) . '-' . uniqid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->latest();
    }

    public function encryptedId()
    {
        return base64_encode(\Illuminate\Support\Facades\Crypt::encryptString($this->id));
    }

    public static function decryptId($token)
    {
        try {
            return \Illuminate\Support\Facades\Crypt::decryptString(base64_decode($token));
        } catch (\Exception $e) {
            return null;
        }
    }
    public function ritualKits()
    {
        return $this->belongsToMany(RitualKit::class, 'ritual_kit_product');
    }
}
