<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
    'title',
    'description',
    'ad_url',
    'banner_image',
    'duration_seconds',
    'is_active',
    ];
}
