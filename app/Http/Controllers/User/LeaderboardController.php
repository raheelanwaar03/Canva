<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class LeaderboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now('Asia/Karachi')->toDateString();

        // Top 50 verified/approved users (having an active or upgraded package)
        $topUsers = User::where('role', 'user')
            ->whereHas('packages', function ($q) {
                $q->whereIn('status', ['active', 'upgraded']);
            })
            ->withCount('referrals as team_count')
            ->withSum(['withdrawals as total_withdrawn' => function ($q) {
                $q->where('status', 'approved');
            }], 'amount')
            ->withSum(['taskLogs as today_task_earnings' => function ($q) use ($today) {
                $q->where('completed_date', $today);
            }], 'reward_earned')
            // Order primarily by team members count and approved withdrawals
            ->orderByDesc('team_count')
            ->orderByDesc('total_withdrawn')
            ->take(50)
            ->get();

        return view('user.leaderboard', compact('topUsers'));
    }
}
