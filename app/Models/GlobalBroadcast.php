<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalBroadcast extends Model
{
    protected $fillable = [
        'title',
        'message',
        'urgency',
        'target_audience',
        'is_active',
    ];
}
