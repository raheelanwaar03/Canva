<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'user_package_id',
        'reward_earned',
        'completed_date',
    ];

    protected $casts = [
        'completed_date' => 'date',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function task() { return $this->belongsTo(Task::class); }
}
