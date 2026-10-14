<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'price',
        'daily_task_limit',
        'reward_per_task',
        'referral_commission',
        'duration_days'
    ];
}
