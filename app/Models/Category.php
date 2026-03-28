<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\Auditable;

class Category extends Model
{
    use Auditable;
    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'parent_id', 'icon_url', 'image_path'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the best available image URL for this category.
     */
    public function getDisplayImageAttribute(): ?string
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }
        if ($this->icon_url) {
            return $this->icon_url;
        }
        return null;
    }
}
