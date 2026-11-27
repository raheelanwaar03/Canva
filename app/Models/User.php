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
        'joining_bonus_eligible',
        'joining_bonus_claimed',
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
            'joining_bonus_eligible' => 'boolean',
            'joining_bonus_claimed'  => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function isEligibleForJoiningBonus(): bool
    {
        return (bool) $this->joining_bonus_eligible
            && !(bool) $this->joining_bonus_claimed
            && !$this->taskLogs()->exists();
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function packages()
    {
        return $this->hasMany(UserPackage::class);
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


    public function refundRequests()
    {
        return $this->hasMany(\App\Models\RefundRequest::class);
    }

    public function latestRefundRequest()
    {
        return $this->hasOne(\App\Models\RefundRequest::class)->latestOfMany();
    }

    public function complaints()
    {
        return $this->hasMany(\App\Models\Complaint::class);
    }

    /**
     * Returns the user's current/latest purchased plan (even if expired)
     * so it stays visible on their profile and in the Admin Panel.
     */
    public function activePackage()
    {
        return $this->hasOne(\App\Models\UserPackage::class)
            ->whereIn('status', ['active', 'upgraded', 'expired'])
            ->latest();
    }

    /**
     * Check if the user has ANY purchased plan record (active or expired)
     * so they are allowed past the login/dashboard gate.
     */
    public function hasPurchasedPlan(): bool
    {
        return $this->packages()
            ->whereIn('status', ['active', 'upgraded', 'expired'])
            ->exists();
    }

    /**
     * 15-Day -> 1 Approved Direct Joining -> 7-Day Repeating Task Lock Cycle
     * - No package expiry or renewal lock.
     * - Never stacks a new requirement while the current one is pending.
     */
    public function getTaskEligibilityStatus(): array
    {
        $activeSub = $this->activePackage;

        if (!$activeSub) {
            return [
                'unlocked'  => false,
                'reason'    => 'No active package found. Please activate a plan to access the Task Room.',
                'days_left' => 0,
            ];
        }

        $now = now();
        $startedAt = $activeSub->started_at ?? $activeSub->created_at ?? $now;

        // 1. First 15 days: completely unlocked with 0 referral requirement
        $firstLockAt = $startedAt->copy()->addDays(15);

        if ($now->lt($firstLockAt)) {
            $daysLeft = max(1, (int) ceil($now->diffInHours($firstLockAt) / 24));

            return [
                'unlocked'  => true,
                'reason'    => "Initial 15-day free period active ({$daysLeft} day(s) remaining before first referral requirement).",
                'days_left' => $daysLeft,
            ];
        }

        // 2. Fetch all approved direct referrals chronologically from Day 15 onward
        $approvedReferrals = PackagePayment::whereHas('user', function ($query) {
            $query->where('referred_by', $this->id);
        })
            ->whereIn('status', ['approved', 'active', 1, '1'])
            ->orderByRaw('COALESCE(reviewed_at, updated_at) ASC')
            ->get()
            ->map(function ($payment) {
                return $payment->reviewed_at ?? $payment->updated_at;
            })
            ->filter()
            ->values();

        // 3. Walk the cycle step-by-step:
        // Current lock begins at Day 15. Any 1 approved direct joining at or after $lockStartedAt
        // immediately unlocks tasks and starts a fresh 7-day countdown from that approval timestamp.
        $lockStartedAt = $firstLockAt->copy();

        foreach ($approvedReferrals as $approvedAt) {
            if ($approvedAt->gte($lockStartedAt)) {
                $cycleEndsAt = $approvedAt->copy()->addDays(7);

                if ($now->lt($cycleEndsAt)) {
                    $daysLeft = max(1, (int) ceil($now->diffInHours($cycleEndsAt) / 24));

                    return [
                        'unlocked'  => true,
                        'reason'    => "Tasks unlocked! {$daysLeft} day(s) left in your current 7-day cycle.",
                        'days_left' => $daysLeft,
                    ];
                }

                // Those 7 days have now passed -> next lock begins when that 7-day cycle ended
                $lockStartedAt = $cycleEndsAt->copy();
            }
        }

        // 4. Current cycle has ended and no approved direct joining has occurred since $lockStartedAt
        return [
            'unlocked'  => false,
            'reason'    => 'Your Task Room is currently locked. Complete 1 approved direct joining to automatically unlock your tasks for the next 7 days.',
            'days_left' => 0,
        ];
    }
    /**
     * Plans do not expire (Task Room access is governed exclusively by getTaskEligibilityStatus()).
     */
    public function hasValidUnexpiredPlan(): bool
    {
        return $this->hasPurchasedPlan();
    }
}
