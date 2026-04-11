<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateRequest extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'estimated_quantity',
        'message',
        'status',
    ];
}
