<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contributions()
    {
        return $this->hasMany(RegistryContribution::class);
    }

    // Crowdfunding logic
    public function getTotalContributedAttribute()
    {
        return $this->contributions()->where('payment_status', 'Paid')->sum('amount');
    }

    public function getIsFullyFundedAttribute()
    {
        return $this->product && $this->total_contributed >= $this->product->price;
    }
}
