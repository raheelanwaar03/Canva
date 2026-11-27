<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskLog;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::now('Asia/Karachi')->toDateString();
        $isPlanExpired = false; // Package expiry and renewal removed

        $eligibility = $user->getTaskEligibilityStatus();
        $activeSub = $user->activePackage;

        $completedTodayCount = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->count();

        // Dynamic referral matrix limit
        $dailyLimit = $user->effective_daily_task_limit;
        $remainingTasks = max(0, $dailyLimit - $completedTodayCount);

        // Check if this user is on their very first task with the Rs. 50 Joining Bonus
        $isFirstTaskBonus = $user->isEligibleForJoiningBonus();

        if ($isFirstTaskBonus) {
            $rewardPerTask = 5.00; // First task earning is Rs. 5
            $joiningBonusAmount = 50.00;
        } else {
            // Normal 2nd+ task rate
            $level = $user->referral_level ?? 0;
            $packageReward = optional(optional($user->activePackage)->package)->reward_per_task ?? 7.00;
            $rewardPerTask = (float) Setting::get("ad_reward_level_{$level}", $packageReward);
            $joiningBonusAmount = 0.00;
        }

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
            'isPlanExpired',
            'isFirstTaskBonus',
            'joiningBonusAmount'
        ));
    }

    /**
     * Records the exact timestamp when the user opens the external task link in a new tab.
     */
    public function start(Request $request, Task $task)
    {
        $user = auth()->user();
        $today = Carbon::now('Asia/Karachi')->toDateString();

        if (!$user->hasPurchasedPlan()) {
            return response()->json(['status' => 'error', 'message' => 'No active plan found.'], 403);
        }

        $eligibility = $user->getTaskEligibilityStatus();
        if (!$eligibility['unlocked']) {
            return response()->json(['status' => 'error', 'message' => $eligibility['reason']], 403);
        }

        $completedTodayCount = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->count();

        if ($completedTodayCount >= $user->effective_daily_task_limit) {
            return response()->json(['status' => 'error', 'message' => 'Daily task limit reached.'], 403);
        }

        session(["task_started_at_{$task->id}" => now()->timestamp]);

        return response()->json([
            'status'       => 'started',
            'wait_seconds' => 15,
        ]);
    }

    public function complete(Request $request, Task $task)
    {
        $user = auth()->user();
        $today = Carbon::now('Asia/Karachi')->toDateString();

        // 1. Check plan & referral cycle task lock
        if (!$user->hasPurchasedPlan()) {
            return redirect()->route('tasks.index')->with('error', 'Please activate a plan first.');
        }

        $eligibility = $user->getTaskEligibilityStatus();
        if (!$eligibility['unlocked']) {
            return redirect()->route('tasks.index')->with('error', $eligibility['reason']);
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

        // Clear session timer immediately
        session()->forget("task_started_at_{$task->id}");

        // 5. Atomic database transaction with row lock to prevent duplicate bonus credits
        $message = DB::transaction(function () use ($user, $task, $today) {
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();
            $wallet = $lockedUser->wallet()->lockForUpdate()->first();
            $userPackageId = optional($lockedUser->activePackage)->id;

            $hasAnyPreviousTask = TaskLog::where('user_id', $lockedUser->id)->exists();
            $isFirstTaskWithBonus = (bool) $lockedUser->joining_bonus_eligible
                && !(bool) $lockedUser->joining_bonus_claimed
                && !$hasAnyPreviousTask;

            if ($isFirstTaskWithBonus) {
                // First task for new user: Rs. 50 Joining Bonus + Rs. 5 First Task = Rs. 55
                $joiningBonus = 50.00;
                $firstTaskReward = 5.00;
                $totalCredit = $joiningBonus + $firstTaskReward; // Rs. 55.00

                $wallet->main_balance += $totalCredit;
                $wallet->withdrawable_balance += $totalCredit;
                $wallet->rewards_balance += $joiningBonus;
                $wallet->referral_earnings += $joiningBonus;
                $wallet->task_earnings += $firstTaskReward;
                $wallet->save();

                // Permanently mark joining bonus as claimed so it can never be credited again
                $lockedUser->joining_bonus_claimed = true;
                $lockedUser->save();

                TaskLog::create([
                    'user_id'         => $lockedUser->id,
                    'task_id'         => $task->id,
                    'user_package_id' => $userPackageId,
                    'reward_earned'   => $firstTaskReward,
                    'completed_date'  => $today,
                ]);

                Transaction::create([
                    'user_id'     => $lockedUser->id,
                    'type'        => 'referral_bonus',
                    'amount'      => $joiningBonus,
                    'description' => 'One-time New User Joining Bonus credited on first task claim',
                ]);

                Transaction::create([
                    'user_id'     => $lockedUser->id,
                    'type'        => 'task_reward',
                    'amount'      => $firstTaskReward,
                    'description' => "First task completed: {$task->title}",
                ]);

                return 'Congratulations! Rs 50 Joining Bonus + Rs 5 First Task Earning (Rs 55 total) added to your Main Wallet.';
            }

            // 2nd+ task (or existing users): Normal task rate
            $level = $lockedUser->referral_level ?? 0;
            $packageReward = optional(optional($lockedUser->activePackage)->package)->reward_per_task ?? 7.00;
            $rewardAmount = (float) Setting::get("ad_reward_level_{$level}", $packageReward);

            $wallet->main_balance += $rewardAmount;
            $wallet->withdrawable_balance += $rewardAmount;
            $wallet->task_earnings += $rewardAmount;
            $wallet->save();

            TaskLog::create([
                'user_id'         => $lockedUser->id,
                'task_id'         => $task->id,
                'user_package_id' => $userPackageId,
                'reward_earned'   => $rewardAmount,
                'completed_date'  => $today,
            ]);

            Transaction::create([
                'user_id'     => $lockedUser->id,
                'type'        => 'task_reward',
                'amount'      => $rewardAmount,
                'description' => "Completed task: {$task->title} (Level {$level} rate)",
            ]);

            return "Task claimed! Rs {$rewardAmount} added to your balance.";
        });

        return redirect()->route('tasks.index')->with('success', $message);
    }
}
