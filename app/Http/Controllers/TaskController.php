<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskLog;
use App\Models\Wallet;
use App\Models\Transaction;
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
            'tasks', 'eligibility', 'activeSub', 'completedTodayCount',
            'dailyLimit', 'rewardPerTask', 'remainingTasks', 'completedTaskIds'
        ));
    }

    public function complete(Request $request, Task $task)
    {
        $user = auth()->user();
        $today = now()->toDateString();
        $eligibility = $user->getTaskEligibilityStatus();

        if (!$eligibility['unlocked']) {
            return response()->json(['success' => false, 'message' => $eligibility['reason']], 403);
        }

        $activeSub = $user->activePackage;
        if (!$activeSub) {
            return response()->json(['success' => false, 'message' => 'Active plan required.'], 403);
        }

        return DB::transaction(function () use ($user, $task, $activeSub, $today) {
            // Enforce dynamic matrix limit
            $completedTodayCount = TaskLog::where('user_id', $user->id)
                ->where('completed_date', $today)
                ->lockForUpdate()
                ->count();

            if ($completedTodayCount >= $user->effective_daily_task_limit) {
                return response()->json(['success' => false, 'message' => 'Daily task limit reached!'], 400);
            }

            $alreadyDone = TaskLog::where('user_id', $user->id)
                ->where('task_id', $task->id)
                ->where('completed_date', $today)
                ->exists();

            if ($alreadyDone) {
                return response()->json(['success' => false, 'message' => 'Task already completed today.'], 400);
            }

            // Section 2 & 3: Standard rewards
            $mainReward = 7.00;
            $rewardsBonus = 200.00;
            $firstTaskBonus = 0.00;

            // Check if first-ever task completed
            $freshUser = User::where('id', $user->id)->lockForUpdate()->first();
            if (!$freshUser->first_task_bonus_received) {
                $firstTaskBonus = 50.00;
                $freshUser->update(['first_task_bonus_received' => true]);
            }

            $totalMainCredited = $mainReward + $firstTaskBonus;

            // 1. Create completion log
            TaskLog::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'user_package_id' => $activeSub->id,
                'reward_earned' => $totalMainCredited,
                'completed_date' => $today,
            ]);

            // 2. Credit Wallet safely
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();
            $wallet->increment('main_balance', $totalMainCredited);
            $wallet->increment('withdrawable_balance', $totalMainCredited);
            $wallet->increment('task_earnings', $totalMainCredited);
            $wallet->increment('rewards_balance', $rewardsBonus);

            // 3. Transactions
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $mainReward,
                'type' => 'task_reward',
                'balance_type' => 'credit',
                'description' => "Task Reward: {$task->title}",
                'reference_id' => $task->id,
            ]);

            if ($firstTaskBonus > 0) {
                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => $firstTaskBonus,
                    'type' => 'task_reward',
                    'balance_type' => 'credit',
                    'description' => "First-Ever Task Welcome Bonus",
                    'reference_id' => $task->id,
                ]);
            }

            $message = "Ad completed! Credited Rs {$mainReward} to Main Balance and Rs {$rewardsBonus} to Rewards Balance.";
            if ($firstTaskBonus > 0) {
                $message .= " + Rs 50 First-Task Bonus received!";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_balance' => $wallet->fresh()->withdrawable_balance,
            ]);
        });
    }
    // View & watch single ad task
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

        // Daily limit check
        $completedTodayCount = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->count();

        if ($completedTodayCount >= $activeSub->package->daily_task_limit) {
            return redirect()->route('tasks.index')->with('error', 'You have reached your daily task limit.');
        }

        // Already completed this specific ad today check
        $alreadyDone = TaskLog::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->where('completed_date', $today)
            ->exists();

        if ($alreadyDone) {
            return redirect()->route('tasks.index')->with('error', 'You have already watched this ad today.');
        }

        return view('tasks.view', compact('task', 'activeSub'));
    }
}
