<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use App\Models\Wallet;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff&bold=true';
    }

    // In app/Models/User.php

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

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function packages()
    {
        return $this->hasMany(UserPackage::class);
    }

    public function activePackage()
    {
        return $this->hasOne(UserPackage::class)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latestOfMany();
    }

    /**
     * Client's 15-day & 7-day Referral Task-Lock Engine
     * Returns ['unlocked' => bool, 'reason' => string, 'days_left' => int]
     */
    public function getTaskEligibilityStatus(): array
    {
        $activeSub = $this->activePackage;

        if (!$activeSub) {
            return [
                'unlocked' => false,
                'reason' => 'No active package. Please purchase or activate a plan.',
                'days_left' => 0
            ];
        }

        $now = now();
        $startedAt = $activeSub->started_at;
        $daysSinceStart = (int) $startedAt->diffInDays($now);

        // Rule 1: First 15 Days -> Always Unlocked
        if ($daysSinceStart < 15) {
            return [
                'unlocked' => true,
                'reason' => 'Free 15-day trial period active.',
                'days_left' => 15 - $daysSinceStart
            ];
        }

        // Rule 2: After 15 Days -> Check 7-day rolling window for an approved direct referral
        // A qualifying referral is a direct referral who joined and had their package payment approved
        $sevenDaysAgo = $now->copy()->subDays(7);

        $hasApprovedReferralInWindow = PackagePayment::where('status', 'approved')
            ->whereIn('user_id', function ($query) {
                $query->select('id')->from('users')->where('referred_by', $this->id);
            })
            ->where('reviewed_at', '>=', $sevenDaysAgo)
            ->exists();

        if ($hasApprovedReferralInWindow) {
            return [
                'unlocked' => true,
                'reason' => 'Referral criteria met. Tasks unlocked.',
                'days_left' => 7
            ];
        }

        return [
            'unlocked' => false,
            'reason' => '15-day period ended. You must bring 1 approved direct referral every 7 days to unlock tasks.',
            'days_left' => 0
        ];
    }

    // In app/Models/User.php

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
     * Determine Referral Level (0 to 8) based on approved direct referrals
     */
    public function getReferralLevelAttribute(): int
    {
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
     * Follows Section 5 Exact Matrix
     */
    public function getEffectiveDailyTaskLimitAttribute(): int
    {
        $activeSub = $this->activePackage;
        if (!$activeSub) return 0;

        $packageName = strtolower(trim($activeSub->package->name));
        $level = $this->referral_level;

        // Exact Client Matrix:
        // Level => [Basic, Gold, Standard, Premium]
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

    public function packagePayments()
    {
        return $this->hasMany(PackagePayment::class);
    }
}
