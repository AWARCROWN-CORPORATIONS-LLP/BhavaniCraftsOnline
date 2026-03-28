<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyComplaint extends Model
{
    protected $fillable = [
        'user_id', 'order_id', 'assigned_logistics_id', 
        'complaint_type', 'description', 'status', 'admin_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function logisticsAgent()
    {
        return $this->belongsTo(User::class, 'assigned_logistics_id');
    }
}
