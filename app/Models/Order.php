<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_id_string', 'address_id', 'status',
        'total_amount', 'currency', 'subtotal', 'discount_total',
        'tax_total', 'shipping_total', 'payment_status',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
    ];

    protected $casts = [
        'ordered_date' => 'datetime',
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
}

