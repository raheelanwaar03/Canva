<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskLog;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    // In app/Http/Controllers/TaskController.php

    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $eligibility = $user->getTaskEligibilityStatus();
        $activeSub = $user->activePackage;

        $completedTodayCount = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->count();

        // Dynamic referral matrix limit
        $dailyLimit = $user->effective_daily_task_limit;
        $remainingTasks = max(0, $dailyLimit - $completedTodayCount);

        // Fixed Rs 7 reward per task
        $rewardPerTask = 7.00;

        $completedTaskIds = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->pluck('task_id')
            ->toArray();

        $tasks = Task::where('is_active', true)->get();

        return view('tasks.index', compact(
            'tasks',
            'eligibility',
            'activeSub',
            'completedTodayCount',
            'dailyLimit',
            'rewardPerTask',
            'remainingTasks',
            'completedTaskIds'
        ));
    }

    // In app/Http/Controllers/TaskController.php

    public function view(Task $task)
    {
        $user = auth()->user();
        $today = now()->toDateString();
        $eligibility = $user->getTaskEligibilityStatus();

        if (!$eligibility['unlocked']) {
            return redirect()->route('tasks.index')->with('error', $eligibility['reason']);
        }

        $activeSub = $user->activePackage;
        if (!$activeSub) {
            return redirect()->route('packages.index')->with('error', 'Active package required.');
        }

        $completedTodayCount = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->count();

        if ($completedTodayCount >= $user->effective_daily_task_limit) {
            return redirect()->route('tasks.index')->with('error', 'You have reached your daily task limit.');
        }

        $alreadyDone = TaskLog::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->where('completed_date', $today)
            ->exists();

        if ($alreadyDone) {
            return redirect()->route('tasks.index')->with('error', 'You have already watched this ad today.');
        }

        return view('tasks.view', compact('task', 'activeSub'));
    }

    public function complete(Request $request, Task $task)
    {
        $user = auth()->user();

        // 1. Verify user has not already completed this specific ad today
        $todayStart = Carbon::now('Asia/Karachi')->startOfDay();
        $todayEnd   = Carbon::now('Asia/Karachi')->endOfDay();

        $alreadyCompleted = TaskLog::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->exists();

        if ($alreadyCompleted) {
            return redirect()->route('tasks.index')->with('error', 'You have already watched this ad today.');
        }

        // 2. Fetch Reward based on User's Referral Level
        $level = $user->referral_level ?? 0;

        // Check setting for this level (e.g. ad_reward_level_0, ad_reward_level_1)
        // Fallback to active package reward_per_task or default Rs 7
        $packageReward = optional(optional($user->activePackage)->package)->reward_per_task ?? 7.00;
        $rewardAmount = (float) Setting::get("ad_reward_level_{$level}", $packageReward);

        // 3. Credit User Wallet
        $wallet = $user->wallet;
        $wallet->main_balance += $rewardAmount;
        $wallet->withdrawable_balance += $rewardAmount;
        $wallet->task_earnings += $rewardAmount;
        $wallet->save();
        $userPackageId = optional($user->activePackage)->id;

        // 4. Log the Task Completion
        TaskLog::create([
            'user_id'         => $user->id,
            'task_id'         => $task->id,
            'user_package_id' => $userPackageId,
            'reward_earned'   => $rewardAmount,
            'completed_date'  => Carbon::now('Asia/Karachi')->toDateString(), // e.g. '2026-10-31'
        ]);

        // 5. Create Transaction Record
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'task_reward',
            'amount' => $rewardAmount,
            'description' => "Watched ad: {$task->title} (Level {$level} rate)",
        ]);

        return redirect()->route('tasks.index')->with('success', "Task completed! Rs {$rewardAmount} added to your balance.");
    }
}
