<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RefundRequest extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'gateway_name',
        'account_name',
        'account_number',
        'reason',
        'status',
        'admin_remark',
        'decision_deadline',
        'decided_at',
    ];

    protected $casts = [
        'decision_deadline' => 'datetime',
        'decided_at'        => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
