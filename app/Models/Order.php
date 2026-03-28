<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use App\Traits\Auditable;

class Order extends Model
{
    use Auditable;
    protected $fillable = [
        'user_id', 'order_id_string', 'address_id', 'status',
        'total_amount', 'currency', 'subtotal', 'discount_total',
        'tax_total', 'shipping_total', 'payment_status', 'payment_method',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
        'coupon_id', 'discount_amount', 'label_printed_at', 'dispatch_id',
        'delivery_pin', 'pin_generations_count', 'delivered_at', 'delivery_status',
        'delivery_photo_url', 'failed_delivery_reason', 'delivery_rating', 
        'delivery_latitude', 'delivery_longitude', 'return_requested_at', 
        'return_reason', 'returned_at', 'assigned_logistics_id',
    ];

    protected $casts = [
        'ordered_date' => 'datetime',
        'label_printed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'return_requested_at' => 'datetime',
        'returned_at' => 'datetime',
        'created_at'   => 'datetime',
    ];

    // ── Encrypted URL helpers ──────────────────────────────────────────────

    /**
     * Returns a URL-safe encrypted token that hides the real order ID.
     * Use this in every route() call instead of $order->id.
     */
    public function encryptedId(): string
    {
        return rtrim(strtr(base64_encode(Crypt::encryptString((string) $this->id)), '+/', '-_'), '=');
    }

    /**
     * Decode an encrypted token back to a real order ID (int).
     * Returns null if the token is invalid / tampered.
     */
    public static function decryptOrderId(string $token): ?int
    {
        try {
            $padded = str_pad(strtr($token, '-_', '+/'), strlen($token) + (4 - strlen($token) % 4) % 4, '=');
            return (int) Crypt::decryptString(base64_decode($padded));
        } catch (\Exception $e) {
            return null;
        }
    }

    // ── Relationships ──────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function assignedLogistics()
    {
        return $this->belongsTo(User::class, 'assigned_logistics_id');
    }
}

