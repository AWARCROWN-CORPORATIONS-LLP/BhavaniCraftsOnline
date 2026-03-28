<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoojariBooking extends Model
{
    protected $fillable = [
        'user_id',
        'poojari_id',
        'event_name',
        'event_date',
        'event_address',
        'additional_notes',
        'status',
        'admin_employee_notes',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poojari()
    {
        return $this->belongsTo(User::class, 'poojari_id');
    }
}
