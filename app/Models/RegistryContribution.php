<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistryContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'wishlist_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'amount',
        'payment_status',
        'gift_message',
        'transaction_id',
        'thank_you_sent'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'thank_you_sent' => 'boolean',
    ];

    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class);
    }
}
