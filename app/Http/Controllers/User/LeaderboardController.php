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
            ->select('users.*', DB::raw('COALESCE(wallets.task_earnings + wallets.referral_earnings, 0) as total_earnings'))
            ->withCount('referrals as team_count')
            ->withSum(['withdrawals as total_withdrawn' => function ($q) {
                $q->where('status', 'approved');
            }], 'amount')
            ->withSum(['taskLogs as today_task_earnings' => function ($q) use ($today) {
                $q->where('completed_date', $today);
            }], 'reward_earned')
            // 1. Sort by Highest Level First (custom level or calculated)
            // 2. Sort by Highest Lifetime Earnings
            // 3. Sort by Total Withdrawn
            ->orderByDesc(DB::raw('COALESCE(users.custom_referral_level, 0)'))
            ->orderByDesc('total_earnings')
            ->orderByDesc('total_withdrawn')
            ->orderByDesc('team_count')
            ->take(50)
            ->get();

        // If levels are calculated dynamically via accessor, sort the collection safely:
        $topUsers = $topUsers->sortByDesc(function ($u) {
            return [
                $u->referral_level,                                // Tier Level (8 down to 0)
                (float) ($u->total_earnings ?? 0),                 // Total Earned (PKR)
                (float) ($u->total_withdrawn ?? 0),                // Total Withdrawn (PKR)
            ];
        })->values();

        return view('user.leaderboard', compact('topUsers'));
    }
}
