<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePayment extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'amount', 'payment_method',
        'sender_number', 'trx_id', 'screenshot', 'type', 'status',
        'admin_note', 'reviewed_by', 'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function package() { return $this->belongsTo(Package::class); }
}
