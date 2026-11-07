<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\PackagePayment;
use App\Models\Withdrawal;
use App\Models\TaskLog;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardHelper
{
    /**
     * Get start and end boundaries for today in Pakistan Standard Time (PKT).
     */
    public static function getTodayTimeRange(): array
    {
        return [
            Carbon::now('Asia/Karachi')->startOfDay(),
            Carbon::now('Asia/Karachi')->endOfDay(),
        ];
    }

    /**
     * Total registered standard members (excluding admin accounts).
     */
    public static function getTotalUsers(): int
    {
        return User::where('role', 'user')->count();
    }

    /**
     * Total lifetime deposited amount (approved package purchases and upgrades).
     */
    public static function getTotalDeposited(): float
    {
        return (float) PackagePayment::whereIn('status', ['approved', 'active', 1, '1'])->sum('amount');
    }

    /**
     * Today Approved Users count (verified within PKT today).
     */
    public static function getTodayApprovedUsers(): int
    {
        [$todayStart, $todayEnd] = self::getTodayTimeRange();

        return PackagePayment::whereIn('status', ['approved', 'active', 1, '1'])
            ->whereBetween('updated_at', [$todayStart, $todayEnd])
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Today received revenue amount (collected and approved today in PKT).
     */
    public static function getTodayReceivedBalance(): float
    {
        [$todayStart, $todayEnd] = self::getTodayTimeRange();

        return (float) PackagePayment::whereIn('status', ['approved', 'active', 1, '1'])
            ->whereBetween('updated_at', [$todayStart, $todayEnd])
            ->sum('amount');
    }

    /**
     * Total lifetime approved/paid withdrawals disbursed to users.
     */
    public static function getTotalWithdrawn(): float
    {
        return (float) Withdrawal::whereIn('status', ['approved', 1, '1'])->sum('amount');
    }

    /**
     * Queued pending package payment requests count.
     */
    public static function getPendingPaymentsCount(): int
    {
        return PackagePayment::whereIn('status', ['pending', 0, '0'])->count();
    }

    /**
     * Queued pending package payment amount in PKR.
     */
    public static function getPendingPaymentsAmount(): float
    {
        return (float) PackagePayment::whereIn('status', ['pending', 0, '0'])->sum('amount');
    }

    /**
     * Queued pending withdrawal payout requests count.
     */
    public static function getPendingWithdrawalsCount(): int
    {
        return Withdrawal::whereIn('status', ['pending', 0, '0'])->count();
    }

    /**
     * Queued pending withdrawal payout amount in PKR.
     */
    public static function getPendingWithdrawalsAmount(): float
    {
        return (float) Withdrawal::whereIn('status', ['pending', 0, '0'])->sum('amount');
    }

    /**
     * Net platform balance (Total Deposited - Total Withdrawn).
     */
    public static function getNetBalance(): float
    {
        return self::getTotalDeposited() - self::getTotalWithdrawn();
    }

    /**
     * Verified ad tasks completed today in PKT.
     */
    public static function getTodayCompletedTasksCount(): int
    {
        $todayDate = Carbon::now('Asia/Karachi')->toDateString();
        return TaskLog::where('completed_date', $todayDate)->count();
    }

    /**
     * Total lifetime earnings paid out from task ads.
     */
    public static function getTotalTaskEarnings(): float
    {
        return (float) Transaction::where('type', 'task_reward')->sum('amount');
    }

    /**
     * Total manual balances added by Admin.
     */
    public static function getTotalManualAdditions(): float
    {
        return (float) Transaction::where('balance_type', 'credit')
            ->where('description', 'like', '%Admin balance adjustment%')
            ->sum('amount');
    }

    /**
     * Aggregate all dashboard metrics in one clean, cached, high-speed call.
     */
    public static function getMetrics(): array
    {
        // Cache metrics for 15 seconds to ensure fast loading on high admin traffic
        return Cache::remember('admin_dashboard_metrics', 15, function () {
            $totalDeposited = self::getTotalDeposited();
            $totalWithdrawn = self::getTotalWithdrawn();
            $tasksCompletedToday = self::getTodayCompletedTasksCount();

            return [
                'totalUsers'             => self::getTotalUsers(),
                'totalDeposited'         => $totalDeposited,
                'todayApprovedUsers'     => self::getTodayApprovedUsers(),
                'todayReceivedBalance'   => self::getTodayReceivedBalance(),
                'totalWithdrawn'         => $totalWithdrawn,
                'pendingPaymentsCount'   => self::getPendingPaymentsCount(),
                'pendingPaymentsAmount'  => self::getPendingPaymentsAmount(),
                'pendingWithdrawCount'   => self::getPendingWithdrawalsCount(),
                'pendingWithdrawAmount'  => self::getPendingWithdrawalsAmount(),
                'netBalance'             => $totalDeposited - $totalWithdrawn,
                'tasksCompletedToday'    => $tasksCompletedToday,
                'taskEarningsToday'      => (float) TaskLog::where('completed_date', Carbon::now('Asia/Karachi')->toDateString())->sum('reward_earned'),
                'totalManualAdditions'   => self::getTotalManualAdditions(),
            ];
        });
    }
}
