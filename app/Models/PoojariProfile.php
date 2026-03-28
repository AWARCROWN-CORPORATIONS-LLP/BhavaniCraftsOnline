<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoojariProfile extends Model
{
    protected $fillable = [
        'user_id',
        'slug',
        'bio',
        'experience_years',
        'specializations',
        'profile_image',
        'availability',
        'location',
        'is_verified',
    ];

    protected $casts = [
        'availability' => 'array',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
