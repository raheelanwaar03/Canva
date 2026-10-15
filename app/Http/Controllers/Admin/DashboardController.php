<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PackagePayment;
use App\Models\Withdrawal;
use App\Models\TaskLog;
use App\Models\UserPackage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. High-level business stats
        $totalUsers = User::where('role', 'user')->count();
        $activeSubscribers = UserPackage::where('status', 'active')->where('expires_at', '>', now())->count();

        $pendingPaymentsCount = PackagePayment::where('status', 'pending')->count();
        $pendingPaymentsAmount = PackagePayment::where('status', 'pending')->sum('amount');

        $pendingWithdrawCount = Withdrawal::where('status', 'pending')->count();
        $pendingWithdrawAmount = Withdrawal::where('status', 'pending')->sum('amount');

        $totalApprovedRevenue = PackagePayment::where('status', 'approved')->sum('amount');
        $totalPaidWithdrawals = Withdrawal::where('status', 'approved')->sum('amount');
        $tasksCompletedToday = TaskLog::where('completed_date', now()->toDateString())->count();

        // 2. Recent activities for quick review
        $recentPayments = PackagePayment::with(['user', 'package'])->latest()->take(5)->get();
        $recentWithdrawals = Withdrawal::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeSubscribers',
            'pendingPaymentsCount',
            'pendingPaymentsAmount',
            'pendingWithdrawCount',
            'pendingWithdrawAmount',
            'totalApprovedRevenue',
            'totalPaidWithdrawals',
            'tasksCompletedToday',
            'recentPayments',
            'recentWithdrawals'
        ));
    }
}
