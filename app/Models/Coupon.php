<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount', 
        'usage_limit', 'used_count', 'expires_at', 'status', 'is_first_order_only'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'status' => 'boolean',
        'is_first_order_only' => 'boolean',
    ];

    /**
     * Check if the coupon is currently valid for a given order total.
     */
    public function isValid($total)
    {
        if (!$this->status) return false;

        if ($this->expires_at && now()->isAfter($this->expires_at)) return false;

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) return false;

        if ($total < $this->min_order_amount) return false;

        return true;
    }

    /**
     * Check if a user has already used this coupon or if it's first-order only.
     */
    public function isUserEligible($userId)
    {
        // Rule: If it's a first-order-only coupon, check if user has ANY previous successful order
        if ($this->is_first_order_only) {
            $hasPreviousOrder = \App\Models\Order::where('user_id', $userId)
                ->where('status', '!=', 'Cancelled')
                ->exists();
            if ($hasPreviousOrder) return false;
        }

        // Rule: A coupon can only be used once per user
        $alreadyUsedThisCoupon = \App\Models\Order::where('user_id', $userId)
            ->where('coupon_id', $this->id)
            ->where('status', '!=', 'Cancelled')
            ->exists();

        return !$alreadyUsedThisCoupon;
    }

    /**
     * Calculate the discount amount for a given total.
     */
    public function calculateDiscount($total)
    {
        if ($this->type === 'percentage') {
            return round(($total * $this->value) / 100, 2);
        }

        // Fixed discount: cannot exceed the total price
        return min($this->value, $total);
    }
}
