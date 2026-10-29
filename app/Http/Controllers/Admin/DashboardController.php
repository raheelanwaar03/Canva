<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PackagePayment;
use App\Models\Withdrawal;
use GuzzleHttp\Psr7\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Set timezone to Pakistan (PKT) for daily stats
        $startOfToday = Carbon::now('Asia/Karachi')->startOfDay();
        $endOfToday   = Carbon::now('Asia/Karachi')->endOfDay();

        // 1. Overall / Lifetime Totals (Never resets)
        $totalApprovedRevenue = PackagePayment::where('status', 'approved')->sum('amount');
        $lifetimeWithdrawals  = Withdrawal::where('status', 'approved')->sum('amount');
        $totalUsers           = User::where('role', 'user')->count();

        // 2. Daily Totals (Automatically starts fresh at 12:00 AM PKT)
        $todayApprovedRevenue = PackagePayment::where('status', 'approved')
            ->whereBetween('updated_at', [$startOfToday, $endOfToday])
            ->sum('amount');

        $todayApprovedCount = PackagePayment::where('status', 'approved')
            ->whereBetween('updated_at', [$startOfToday, $endOfToday])
            ->count();

        $todayWithdrawals = Withdrawal::where('status', 'approved')
            ->whereBetween('updated_at', [$startOfToday, $endOfToday])
            ->sum('amount');

        // 3. Pending counters
        $pendingPaymentsCount    = PackagePayment::where('status', 'pending')->count();
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalApprovedRevenue',
            'lifetimeWithdrawals',
            'totalUsers',
            'todayApprovedRevenue',
            'todayApprovedCount',
            'todayWithdrawals',
            'pendingPaymentsCount',
            'pendingWithdrawalsCount'
        ));
    }

    public function pending(Request $request)
    {
        return 1;
        $users = User::where('role', 'user')
            ->where('status', 'pending')
            ->with(['referrer', 'activePackage.package', 'packagePayments'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'pageTitle' => 'Pending Users',
            'status' => 'pending'
        ]);
    }

    public function approved(Request $request)
    {
        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->with(['referrer', 'activePackage.package', 'packagePayments'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'pageTitle' => 'Approved Users',
            'status' => 'approved'
        ]);
    }

    public function rejected(Request $request)
    {
        $users = User::where('role', 'user')
            ->where('status', 'rejected')
            ->with(['referrer', 'activePackage.package', 'packagePayments'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'pageTitle' => 'Rejected Users',
            'status' => 'rejected'
        ]);
    }
}
