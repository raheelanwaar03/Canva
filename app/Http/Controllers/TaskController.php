<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskLog;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
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
        $today = now()->toDateString();

        try {
            $eligibility = $user->getTaskEligibilityStatus();
            if (!$eligibility['unlocked']) {
                return response()->json(['success' => false, 'message' => $eligibility['reason']], 403);
            }

            $activeSub = $user->activePackage;
            if (!$activeSub) {
                return response()->json(['success' => false, 'message' => 'Active plan required.'], 403);
            }

            return DB::transaction(function () use ($user, $task, $activeSub, $today) {
                // Prevent race conditions
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

                $mainReward = 7.00;
                $rewardsBonus = 200.00;
                $firstTaskBonus = 0.00;

                // Safe check for first task bonus
                $freshUser = User::where('id', $user->id)->lockForUpdate()->first();
                if (isset($freshUser->first_task_bonus_received) && !$freshUser->first_task_bonus_received) {
                    $firstTaskBonus = 50.00;
                    $freshUser->update(['first_task_bonus_received' => true]);
                }

                $totalMainCredited = $mainReward + $firstTaskBonus;

                // 1. Task Log
                TaskLog::create([
                    'user_id' => $user->id,
                    'task_id' => $task->id,
                    'user_package_id' => $activeSub->id,
                    'reward_earned' => $totalMainCredited,
                    'completed_date' => $today,
                ]);

                // 2. Ensure Wallet exists safely
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'main_balance' => 0.00,
                        'rewards_balance' => 0.00,
                        'task_earnings' => 0.00,
                        'referral_earnings' => 0.00,
                        'staff_earnings' => 0.00,
                        'withdrawable_balance' => 0.00,
                    ]
                );

                $wallet->increment('main_balance', $totalMainCredited);
                $wallet->increment('withdrawable_balance', $totalMainCredited);
                $wallet->increment('task_earnings', $totalMainCredited);

                if (\Illuminate\Support\Facades\Schema::hasColumn('wallets', 'rewards_balance')) {
                    $wallet->increment('rewards_balance', $rewardsBonus);
                }

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
                ]);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Task Complete Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
