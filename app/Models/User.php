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
        'phone',
        'password',
        'referral_code',
        'referred_by',
        'role',
        'status',
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
}
