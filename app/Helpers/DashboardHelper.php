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

    // ==================== ALL-TIME METRICS ====================

    public static function getTotalUsers(): int
    {
        return User::where('role', 'user')->count();
    }

    public static function getTotalDeposited(): float
    {
        return (float) PackagePayment::whereIn('status', ['approved', 'active', 1, '1'])->sum('amount');
    }

    public static function getTotalWithdrawn(): float
    {
        return (float) Withdrawal::whereIn('status', ['approved', 1, '1'])->sum('amount');
    }

    public static function getPendingPaymentsCount(): int
    {
        return PackagePayment::whereIn('status', ['pending', 0, '0'])->count();
    }

    public static function getPendingPaymentsAmount(): float
    {
        return (float) PackagePayment::whereIn('status', ['pending', 0, '0'])->sum('amount');
    }

    public static function getPendingWithdrawalsCount(): int
    {
        return Withdrawal::whereIn('status', ['pending', 0, '0'])->count();
    }

    public static function getPendingWithdrawalsAmount(): float
    {
        return (float) Withdrawal::whereIn('status', ['pending', 0, '0'])->sum('amount');
    }

    // ==================== TODAY-ONLY METRICS (PKT) ====================

    public static function getTodayApprovedUsers(): int
    {
        [$todayStart, $todayEnd] = self::getTodayTimeRange();

        return PackagePayment::whereIn('status', ['approved', 'active', 1, '1'])
            ->whereBetween('updated_at', [$todayStart, $todayEnd])
            ->distinct('user_id')
            ->count('user_id');
    }

    public static function getTodayReceivedBalance(): float
    {
        [$todayStart, $todayEnd] = self::getTodayTimeRange();

        return (float) PackagePayment::whereIn('status', ['approved', 'active', 1, '1'])
            ->whereBetween('updated_at', [$todayStart, $todayEnd])
            ->sum('amount');
    }

    public static function getTodayPendingPaymentsCount(): int
    {
        [$todayStart, $todayEnd] = self::getTodayTimeRange();

        return PackagePayment::whereIn('status', ['pending', 0, '0'])
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();
    }

    public static function getTodayPendingPaymentsAmount(): float
    {
        [$todayStart, $todayEnd] = self::getTodayTimeRange();

        return (float) PackagePayment::whereIn('status', ['pending', 0, '0'])
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('amount');
    }

    public static function getTodayPendingWithdrawalsCount(): int
    {
        [$todayStart, $todayEnd] = self::getTodayTimeRange();

        return Withdrawal::whereIn('status', ['pending', 0, '0'])
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();
    }

    public static function getTodayPendingWithdrawalsAmount(): float
    {
        [$todayStart, $todayEnd] = self::getTodayTimeRange();

        return (float) Withdrawal::whereIn('status', ['pending', 0, '0'])
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('amount');
    }

    public static function getTodayCompletedTasksCount(): int
    {
        $todayDate = Carbon::now('Asia/Karachi')->toDateString();
        return TaskLog::where('completed_date', $todayDate)->count();
    }

    /**
     * Aggregate all dashboard metrics in one clean, cached call.
     */
    public static function getMetrics(): array
    {
        return Cache::remember('admin_dashboard_metrics', 15, function () {
            $totalDeposited = self::getTotalDeposited();
            $totalWithdrawn = self::getTotalWithdrawn();
            $todayDate      = Carbon::now('Asia/Karachi')->toDateString();

            return [
                // All-Time Metrics
                'totalUsers'                 => self::getTotalUsers(),
                'totalDeposited'             => $totalDeposited,
                'totalWithdrawn'             => $totalWithdrawn,
                'pendingPaymentsCount'       => self::getPendingPaymentsCount(),
                'pendingPaymentsAmount'      => self::getPendingPaymentsAmount(),
                'pendingWithdrawCount'       => self::getPendingWithdrawalsCount(),
                'pendingWithdrawAmount'      => self::getPendingWithdrawalsAmount(),
                'netBalance'                 => $totalDeposited - $totalWithdrawn,

                // Today-Only Metrics (PKT)
                'todayApprovedUsers'         => self::getTodayApprovedUsers(),
                'todayReceivedBalance'       => self::getTodayReceivedBalance(),
                'todayPendingPaymentsCount'  => self::getTodayPendingPaymentsCount(),
                'todayPendingPaymentsAmount' => self::getTodayPendingPaymentsAmount(),
                'todayPendingWithdrawCount'  => self::getTodayPendingWithdrawalsCount(),
                'todayPendingWithdrawAmount' => self::getTodayPendingWithdrawalsAmount(),
                'tasksCompletedToday'        => self::getTodayCompletedTasksCount(),
                'taskEarningsToday'          => (float) TaskLog::where('completed_date', $todayDate)->sum('reward_earned'),
            ];
        });
    }
}
