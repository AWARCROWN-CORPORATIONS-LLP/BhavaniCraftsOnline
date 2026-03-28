<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Auditable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'name',
        'phone',
        'user_type',
        'is_approved',
        'is_verified',
        'session_token',
        'is_blocked',
        'google_id',
        'wishlist_token',
        'wishlist_public',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'session_token',
    ];

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($roleName)
    {
        // Special case for hardcoded superadmin - ONLY return true for super_admin role
        if (strtolower($this->email) === 'archbhavanicrafts@gmail.com') {
            return str_replace(['_', ' '], '', strtolower($roleName)) === 'superadmin';
        }
        
        // Normalize role name (handle both 'super_admin' and 'super admin')
        $normalizedSearch = str_replace(['_', ' '], '', strtolower($roleName));
        
        return $this->roles->contains(function($role) use ($normalizedSearch) {
            $normalizedRole = str_replace(['_', ' '], '', strtolower($role->name));
            return $normalizedRole === $normalizedSearch;
        });
    }

    /**
     * Get the cart items for the user.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Poojari Profile if user is a poojari.
     */
    public function poojariProfile()
    {
        return $this->hasOne(PoojariProfile::class);
    }

    /**
     * Bookings made BY this user.
     */
    public function bookings()
    {
        return $this->hasMany(PoojariBooking::class, 'user_id');
    }

    /**
     * Bookings assigned TO this user (if they are a poojari).
     */
    public function poojariBookings()
    {
        return $this->hasMany(PoojariBooking::class, 'poojari_id');
    }

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wishlist_public' => 'boolean',
        ];
    }
}

