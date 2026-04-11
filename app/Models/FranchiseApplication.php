<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FranchiseApplication extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'location',
        'experience',
        'status',
        'admin_notes',
    ];
}
