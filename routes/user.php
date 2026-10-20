<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\ProfileController;
use App\Models\Withdrawal;
use App\Models\Channel;
use App\Models\Transaction;
use App\Models\TaskLog;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC TO ALL AUTHENTICATED USERS (Including new unverified signups)
|--------------------------------------------------------------------------
*/

// Package Selection, Checkout & Proof Submission
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/pending', [PackageController::class, 'pending'])->name('packages.pending');
Route::get('/packages/{package}/checkout', [PackageController::class, 'checkout'])->name('packages.checkout');
Route::post('/packages/{package}/submit-payment', [PackageController::class, 'submitPayment'])->name('packages.submitPayment');

// Account Settings & Profile
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


/*
|--------------------------------------------------------------------------
| 2. MEMBER FEATURES (Locked until admin approves package payment)
|--------------------------------------------------------------------------
*/
Route::middleware(['has_active_package'])->group(function () {

    // Main Member Dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user()->load(['wallet']);
        $channels = Channel::where('is_active', true)->get();
        return view('dashboard', compact('user', 'channels'));
    })->name('dashboard');

    // Wallet Overview & History
    Route::get('/wallet', function () {
        $user = auth()->user();
        $wallet = $user->wallet ?: $user->wallet()->create([
            'main_balance' => 0.00,
            'rewards_balance' => 0.00,
            'task_earnings' => 0.00,
            'referral_earnings' => 0.00,
            'staff_earnings' => 0.00,
            'withdrawable_balance' => 0.00,
        ]);

        $withdrawals = Withdrawal::where('user_id', $user->id)->latest()->take(10)->get();
        $approvedWithdrawTotal = Withdrawal::where('user_id', $user->id)->where('status', 'approved')->sum('amount');
        $pendingWithdrawTotal = Withdrawal::where('user_id', $user->id)->where('status', 'pending')->sum('amount');
        $rejectedWithdrawTotal = Withdrawal::where('user_id', $user->id)->where('status', 'rejected')->sum('amount');

        return view('wallet', compact('wallet', 'withdrawals', 'approvedWithdrawTotal', 'pendingWithdrawTotal', 'rejectedWithdrawTotal'));
    })->name('wallet');

    // Rewards Breakdown & Activity
    Route::get('/rewards', function () {
        $user = auth()->user();
        $wallet = $user->wallet;
        $totalAdsCompleted = TaskLog::where('user_id', $user->id)->count();
        $rewardTransactions = Transaction::where('user_id', $user->id)
            ->whereIn('type', ['task_reward', 'referral_bonus'])
            ->latest()
            ->paginate(15);

        return view('rewards', compact('wallet', 'totalAdsCompleted', 'rewardTransactions'));
    })->name('rewards');

    // Advertising Tasks & Timer Verification
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}/view', [TaskController::class, 'view'])->name('tasks.view');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

    // Withdrawals (Easypaisa / JazzCash)
    Route::get('/withdraw', [WithdrawalController::class, 'create'])->name('withdraw.create');
    Route::post('/withdraw', [WithdrawalController::class, 'store'])->name('withdraw.store');

    // P2P Member-to-Member Balance Transfers
    Route::get('/transfer', [TransferController::class, 'index'])->name('transfer.index');
    Route::post('/transfer', [TransferController::class, 'store'])->name('transfer.store');

    // Direct Referral Team
    Route::get('/team', [TeamController::class, 'index'])->name('team');

    // Plan Upgrade Shortcut
    Route::get('/upgrade', [PackageController::class, 'index'])->name('packages.upgrade');

    // In routes/user.php
    Route::get('/packages/rejected', [PackageController::class, 'rejected'])->name('packages.rejected');
    Route::post('/packages/{payment}/resubmit', [PackageController::class, 'resubmit'])->name('packages.resubmit');
});
