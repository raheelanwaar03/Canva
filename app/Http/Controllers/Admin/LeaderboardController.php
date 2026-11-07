<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class LeaderboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now('Asia/Karachi')->toDateString();

        $leaderboard = User::where('role', 'user')
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
            ->with(['activePackage.package'])
            ->orderByDesc('team_count')
            ->orderByDesc('total_withdrawn')
            ->get();

        return view('admin.leaderboard.index', compact('leaderboard'));
    }
}
