<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockRequest extends Model
{
    protected $fillable = [
        'franchise_id',
        'product_id',
        'current_stock',
        'requested_quantity',
        'priority',
        'status',
        'admin_notes',
    ];

    public function franchise()
    {
        return $this->belongsTo(User::class, 'franchise_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
