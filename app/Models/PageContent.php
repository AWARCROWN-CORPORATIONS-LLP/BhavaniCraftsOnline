<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    use \App\Traits\InvalidatesHomeCache;
    protected $fillable = ['key', 'value', 'type', 'label', 'section'];
}
