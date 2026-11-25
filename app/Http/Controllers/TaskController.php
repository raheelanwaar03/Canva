<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskLog;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::now('Asia/Karachi')->toDateString();
        $isPlanExpired = !$user->hasValidUnexpiredPlan();

        $eligibility = $user->getTaskEligibilityStatus();
        $activeSub = $user->activePackage;

        $completedTodayCount = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->count();

        // Dynamic referral matrix limit
        $dailyLimit = $user->effective_daily_task_limit;
        $remainingTasks = max(0, $dailyLimit - $completedTodayCount);

        // Reward per task (respects referral level / package setting, fallback Rs 7)
        $level = $user->referral_level ?? 0;
        $packageReward = optional(optional($user->activePackage)->package)->reward_per_task ?? 7.00;
        $rewardPerTask = (float) Setting::get("ad_reward_level_{$level}", $packageReward);

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
            'completedTaskIds',
            'isPlanExpired'
        ));
    }

    /**
     * Records the exact timestamp when the user opens the external task link in a new tab.
     */
    public function start(Request $request, Task $task)
    {
        $user = auth()->user();
        $today = Carbon::now('Asia/Karachi')->toDateString();

        if (!$user->hasValidUnexpiredPlan()) {
            return response()->json(['status' => 'error', 'message' => 'Your package has expired.'], 403);
        }

        $completedTodayCount = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->count();

        if ($completedTodayCount >= $user->effective_daily_task_limit) {
            return response()->json(['status' => 'error', 'message' => 'Daily task limit reached.'], 403);
        }

        // Save server-side start timestamp in session
        session(["task_started_at_{$task->id}" => now()->timestamp]);

        return response()->json([
            'status' => 'started',
            'wait_seconds' => 15,
        ]);
    }

    public function complete(Request $request, Task $task)
    {
        $user = auth()->user();
        $today = Carbon::now('Asia/Karachi')->toDateString();

        // 1. Check plan expiration
        if (!$user->hasValidUnexpiredPlan()) {
            return back()->with('error', 'Your package has expired. Please renew or upgrade your plan to continue completing daily tasks.');
        }

        // 2. Verify daily task limit
        $completedTodayCount = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->count();

        if ($completedTodayCount >= $user->effective_daily_task_limit) {
            return redirect()->route('tasks.index')->with('error', 'You have reached your daily task limit for today.');
        }

        // 3. Verify user has not already completed this specific task today (PKT)
        $alreadyCompleted = TaskLog::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->where('completed_date', $today)
            ->exists();

        if ($alreadyCompleted) {
            return redirect()->route('tasks.index')->with('error', 'You have already claimed this task today.');
        }

        // 4. Verify at least 15 seconds elapsed since the user opened the link
        $startedAt = session("task_started_at_{$task->id}");
        if (!$startedAt || (now()->timestamp - $startedAt) < 15) {
            return redirect()->route('tasks.index')->with('error', 'Please open the task link and wait at least 15 seconds before claiming your reward.');
        }

        // Clear session timer so it cannot be reused
        session()->forget("task_started_at_{$task->id}");

        // 5. Fetch Reward based on User's Referral Level
        $level = $user->referral_level ?? 0;
        $packageReward = optional(optional($user->activePackage)->package)->reward_per_task ?? 7.00;
        $rewardAmount = (float) Setting::get("ad_reward_level_{$level}", $packageReward);

        // 6. Credit User Personal Withdrawable Balance & Wallet
        $wallet = $user->wallet;
        $wallet->main_balance += $rewardAmount;
        $wallet->withdrawable_balance += $rewardAmount;
        $wallet->task_earnings += $rewardAmount;
        $wallet->save();

        $userPackageId = optional($user->activePackage)->id;

        // 7. Log the Task Completion
        TaskLog::create([
            'user_id'         => $user->id,
            'task_id'         => $task->id,
            'user_package_id' => $userPackageId,
            'reward_earned'   => $rewardAmount,
            'completed_date'  => $today,
        ]);

        // 8. Create Transaction Record
        Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'task_reward',
            'amount'      => $rewardAmount,
            'description' => "Completed task: {$task->title} (Level {$level} rate)",
        ]);

        return redirect()->route('tasks.index')->with('success', "Task claimed! Rs {$rewardAmount} added to your withdrawable balance.");
    }
}
