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
        'short_description', 'full_description', 'video_url', 'youtube_url',
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

    /**
     * Get the first image as the display image.
     */
    public function getDisplayImageAttribute(): ?string
    {
        $image = $this->images()->where('is_main', true)->first() ?? $this->images()->first();
        if (!$image) return null;
        
        $url = $image->image_url;
        if (str_starts_with($url, 'http')) {
            return $url;
        }
        return \Illuminate\Support\Facades\Storage::url($url);
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
    public function getYoutubeIdAttribute()
    {
        if (!$this->youtube_url) return null;
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->youtube_url, $match);
        return isset($match[1]) ? $match[1] : null;
    }

    public function ritualKits()
    {
        return $this->belongsToMany(RitualKit::class, 'ritual_kit_product');
    }
}
