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
        // 1. Define exact Pakistan Time boundaries for Today
        $todayStart = Carbon::now('Asia/Karachi')->startOfDay();
        $todayEnd   = Carbon::now('Asia/Karachi')->endOfDay();

        // 2. Total Deposited (All-time approved package payments)
        $totalDeposited = PackagePayment::whereIn('status', ['approved', 'active', 1])
            ->sum('amount');

        // 3. Today Approved Users (Count payments approved within today's window)
        $todayApprovedUsers = PackagePayment::whereIn('status', ['approved', 'active', 1])
            ->whereBetween('updated_at', [$todayStart, $todayEnd])
            ->distinct('user_id')
            ->count('user_id');

        // 4. Today Received Balance (Total money approved/collected today)
        $todayReceivedBalance = PackagePayment::whereIn('status', ['approved', 'active', 1])
            ->whereBetween('updated_at', [$todayStart, $todayEnd])
            ->sum('amount');

        // 5. Total Withdrawn
        $totalWithdrawn = Withdrawal::whereIn('status', ['approved', 1])
            ->sum('amount');

        // 6. Pending User Requests
        $pendingUserRequests = PackagePayment::whereIn('status', ['pending', 0])
            ->count();

        // 7. Pending User Requests Amount Queued
        $pendingUserAmount = PackagePayment::whereIn('status', ['pending', 0])
            ->sum('amount');

        // 8. Total Registered Users
        $totalUsers = User::where('role', 'user')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalDeposited',
            'todayApprovedUsers',
            'todayReceivedBalance',
            'totalWithdrawn',
            'pendingUserRequests',
            'pendingUserAmount'
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
