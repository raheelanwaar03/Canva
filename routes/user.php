<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\TeamController;
use App\Models\Withdrawal;
use App\Http\Controllers\ProfileController;

// Dashboard
Route::get('/dashboard', function () {
    $user = auth()->user()->load(['wallet']);
    return view('dashboard', compact('user'));
})->name('dashboard');

// Wallet
Route::get('/wallet', function () {
    $user = auth()->user();

    // Auto-create wallet if missing (e.g. for admin accounts)
    $wallet = $user->wallet ?: $user->wallet()->create([
        'main_balance' => 0.00,
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

// Packages & Checkout
Route::get('/upgrade', [PackageController::class, 'index'])->name('packages.upgrade');
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package}/checkout', [PackageController::class, 'checkout'])->name('packages.checkout');
Route::post('/packages/{package}/submit-payment', [PackageController::class, 'submitPayment'])->name('packages.submitPayment');

// Advertising Tasks
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/{task}/view', [TaskController::class, 'view'])->name('tasks.view');
Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

// Withdrawals
Route::get('/withdraw', [WithdrawalController::class, 'create'])->name('withdraw.create');
Route::post('/withdraw', [WithdrawalController::class, 'store'])->name('withdraw.store');

// Team / Referrals
Route::get('/team', [TeamController::class, 'index'])->name('team');

// profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
