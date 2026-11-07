<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now('Asia/Karachi')->toDateString();

        $topUsers = User::where('users.role', 'user')
            ->whereHas('packages', function ($q) {
                $q->whereIn('status', ['active', 'upgraded']);
            })
            ->leftJoin('wallets', 'wallets.user_id', '=', 'users.id')
            ->select(
                'users.*',
                DB::raw('COALESCE(wallets.withdrawable_balance, 0) as account_balance'),
                DB::raw('COALESCE(wallets.task_earnings + wallets.referral_earnings, 0) as total_earnings')
            )
            ->withCount('referrals as team_count')
            ->withSum(['withdrawals as total_withdrawn' => function ($q) {
                $q->where('status', 'approved');
            }], 'amount')
            ->withSum(['taskLogs as today_task_earnings' => function ($q) use ($today) {
                $q->where('completed_date', $today);
            }], 'reward_earned')
            // Order by Referrals, Account Balance, and Lifetime Withdrawals
            ->orderByDesc('team_count')
            ->orderByDesc('account_balance')
            ->orderByDesc('total_withdrawn')
            ->take(10)
            ->get();

        return view('user.leaderboard', compact('topUsers'));
    }
}
