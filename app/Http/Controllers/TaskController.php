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
    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        // 1. Check referral & 15-day eligibility engine
        $eligibility = $user->getTaskEligibilityStatus();
        $activeSub = $user->activePackage;

        // 2. Count completed tasks today
        $completedTodayCount = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->count();

        $dailyLimit = $activeSub ? $activeSub->package->daily_task_limit : 0;
        $rewardPerTask = $activeSub ? $activeSub->package->reward_per_task : 0;
        $remainingTasks = max(0, $dailyLimit - $completedTodayCount);

        // 3. Fetch completed task IDs today
        $completedTaskIds = TaskLog::where('user_id', $user->id)
            ->where('completed_date', $today)
            ->pluck('task_id')
            ->toArray();

        // 4. Get active tasks
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

    // Verify & credit reward after timer completes
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
            // Lock to prevent race condition attacks
            $completedTodayCount = TaskLog::where('user_id', $user->id)
                ->where('completed_date', $today)
                ->lockForUpdate()
                ->count();

            if ($completedTodayCount >= $activeSub->package->daily_task_limit) {
                return response()->json(['success' => false, 'message' => 'Daily task limit reached!'], 400);
            }

            $alreadyDone = TaskLog::where('user_id', $user->id)
                ->where('task_id', $task->id)
                ->where('completed_date', $today)
                ->exists();

            if ($alreadyDone) {
                return response()->json(['success' => false, 'message' => 'Ad already completed today.'], 400);
            }

            $reward = $activeSub->package->reward_per_task;

            // 1. Create completion log
            TaskLog::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'user_package_id' => $activeSub->id,
                'reward_earned' => $reward,
                'completed_date' => $today,
            ]);

            // 2. Credit Wallet safely
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();
            $wallet->increment('main_balance', $reward);
            $wallet->increment('task_earnings', $reward);
            $wallet->increment('withdrawable_balance', $reward);

            // 3. Create Transaction record
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $reward,
                'type' => 'task_reward',
                'balance_type' => 'credit',
                'description' => "Ad reward: {$task->title}",
                'reference_id' => $task->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Congratulations! Rs " . number_format($reward, 2) . " credited to your wallet.",
                'new_balance' => $wallet->fresh()->withdrawable_balance,
            ]);
        });
    }
}
