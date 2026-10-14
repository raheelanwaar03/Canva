<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'main_balance',
        'task_earnings',
        'referral_earnings',
        'staff_earnings',
        'withdrawable_balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
