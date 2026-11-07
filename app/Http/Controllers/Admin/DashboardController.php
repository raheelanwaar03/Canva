<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DashboardHelper;
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
        $metrics = DashboardHelper::getMetrics();

        return view('admin.dashboard', $metrics);
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
