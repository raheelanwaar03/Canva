<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'avatar',
        'phone',
        'password',
        'referral_code',
        'referred_by',
        'role',
        'status',
        'first_task_bonus_received',
        'custom_level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'first_task_bonus_received' => 'boolean',
            'custom_level' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function packages()
    {
        return $this->hasMany(UserPackage::class);
    }

    public function activePackage()
    {
        return $this->hasOne(UserPackage::class)
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function packagePayments()
    {
        return $this->hasMany(PackagePayment::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function taskLogs()
    {
        return $this->hasMany(TaskLog::class);
    }

    // Direct referrals (people invited by this user)
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    // Direct upline sponsor
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Business Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Profile picture URL or fallback
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if (!empty($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff&bold=true';
    }

    /**
     * Count active approved direct referrals
     */
    public function approvedDirectReferralsCount(): int
    {
        return $this->referrals()
            ->whereHas('packages', function ($q) {
                $q->whereIn('status', ['active', 'upgraded']);
            })
            ->count();
    }

    /**
     * Referral Level (0 to 8) - Supports custom override if set by admin
     */
    public function getReferralLevelAttribute(): int
    {
        if ($this->custom_level !== null) {
            return (int) $this->custom_level;
        }

        $count = $this->approvedDirectReferralsCount();

        if ($count >= 1000) return 8;
        if ($count >= 500)  return 7;
        if ($count >= 370)  return 6;
        if ($count >= 220)  return 5;
        if ($count >= 150)  return 4;
        if ($count >= 27)   return 3;
        if ($count >= 12)   return 2;
        if ($count >= 3)    return 1;

        return 0;
    }

    /**
     * Dynamic Daily Task Limit based on Active Package & Referral Level
     */
    public function getEffectiveDailyTaskLimitAttribute(): int
    {
        $activeSub = $this->activePackage;
        if (!$activeSub || !$activeSub->package) return 0;

        $packageName = strtolower(trim($activeSub->package->name));
        $level = $this->referral_level;

        $matrix = [
            0 => ['basic' => 1, 'gold' => 2, 'standard' => 4, 'premium' => 6],
            1 => ['basic' => 1, 'gold' => 2, 'standard' => 4, 'premium' => 6],
            2 => ['basic' => 3, 'gold' => 2, 'standard' => 4, 'premium' => 6],
            3 => ['basic' => 4, 'gold' => 2, 'standard' => 4, 'premium' => 6],
            4 => ['basic' => 4, 'gold' => 2, 'standard' => 4, 'premium' => 6],
            5 => ['basic' => 5, 'gold' => 2, 'standard' => 5, 'premium' => 6],
            6 => ['basic' => 6, 'gold' => 2, 'standard' => 6, 'premium' => 6],
            7 => ['basic' => 6, 'gold' => 2, 'standard' => 6, 'premium' => 6],
            8 => ['basic' => 7, 'gold' => 2, 'standard' => 7, 'premium' => 7],
        ];

        return $matrix[$level][$packageName] ?? $activeSub->package->daily_task_limit;
    }

    /**
     * Next Level Progression Info
     */
    public function getNextLevelInfoAttribute(): array
    {
        $count = $this->approvedDirectReferralsCount();
        $level = $this->referral_level;

        $thresholds = [
            1 => 3,
            2 => 12,
            3 => 27,
            4 => 150,
            5 => 220,
            6 => 370,
            7 => 500,
            8 => 1000,
        ];

        if ($level >= 8) {
            return [
                'next_level' => 8,
                'needed' => 0,
                'progress' => 100,
                'current_count' => $count,
                'target' => 1000,
            ];
        }

        $target = $thresholds[$level + 1];
        $prevTarget = $level > 0 ? $thresholds[$level] : 0;

        $range = $target - $prevTarget;
        $progressInRange = $count - $prevTarget;
        $percent = min(100, max(0, round(($progressInRange / $range) * 100)));

        return [
            'next_level' => $level + 1,
            'needed' => max(0, $target - $count),
            'progress' => $percent,
            'current_count' => $count,
            'target' => $target,
        ];
    }

    /**
     * 15-Day & 7-Day Referral Task Eligibility Rule
     */
    public function getTaskEligibilityStatus(): array
    {
        $activeSub = $this->activePackage;

        if (!$activeSub) {
            return [
                'unlocked' => false,
                'reason' => 'No active package found. Please purchase or upgrade a package.',
                'days_left' => 0,
            ];
        }

        $now = now();

        if ($now->greaterThan($activeSub->expires_at)) {
            return [
                'unlocked' => false,
                'reason' => 'Your 30-day package has expired. Please renew or upgrade.',
                'days_left' => 0,
            ];
        }

        $startedAt = $activeSub->started_at;
        $daysSinceStart = (int) $startedAt->diffInDays($now);

        // Window 1: First 15 days completely free of referral requirement
        if ($daysSinceStart < 15) {
            return [
                'unlocked' => true,
                'reason' => 'Active under initial 15-day free period.',
                'days_left' => 15 - $daysSinceStart,
            ];
        }

        // Window 2: After Day 15 -> 7-day rolling window requiring approved referral
        $sevenDaysAgo = $now->copy()->subDays(7);

        $hasApprovedReferralInWindow = PackagePayment::whereHas('user', function ($query) {
            $query->where('referred_by', $this->id);
        })
            ->where('status', 'approved')
            ->where('reviewed_at', '>=', $sevenDaysAgo)
            ->exists();

        if ($hasApprovedReferralInWindow) {
            return [
                'unlocked' => true,
                'reason' => 'Unlocked via approved referral in the last 7 days.',
                'days_left' => 7,
            ];
        }

        return [
            'unlocked' => false,
            'reason' => '15-day period ended. You must bring 1 approved direct referral every 7 days to keep tasks unlocked.',
            'days_left' => 0,
        ];
    }
}
