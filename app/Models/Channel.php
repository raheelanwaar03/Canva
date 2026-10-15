<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = [
        'title',
        'url',
        'icon',
        'is_active',
    ];
}
