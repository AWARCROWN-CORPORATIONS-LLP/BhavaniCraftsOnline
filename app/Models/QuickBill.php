<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickBill extends Model
{
    protected $fillable = [
        'bill_number',
        'is_quotation',
        'items',
        'subtotal',
        'discount_amount',
        'gst_percent',
        'gst_amount',
        'total_amount',
        'razorpay_order_id',
        'razorpay_payment_id',
        'payment_status',
        'customer_name',
        'customer_phone',
    ];

    protected $casts = [
        'items' => 'json',
        'is_quotation' => 'boolean',
    ];

}

