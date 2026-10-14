<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\Admin\PaymentApprovalController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\Admin\WithdrawalApprovalController;
use App\Models\Withdrawal;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    $user = auth()->user()->load(['wallet']);
    return view('dashboard', compact('user'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::get('/wallet', function () {
        return view('wallet');
    })->name('wallet');
    Route::get('/tasks', function () {
        return "Tasks Page (Coming in Module 3)";
    })->name('tasks.index');
    Route::get('/withdraw', function () {
        return view('withdraw');
    })->name('withdraw.create');
    Route::get('/upgrade', function () {
        return "Upgrade Page (Coming in Module 2)";
    })->name('packages.upgrade');
    Route::get('/team', function () {
        return "Team Page (Coming in Module 2)";
    })->name('team');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/{package}/checkout', [PackageController::class, 'checkout'])->name('packages.checkout');
    Route::post('/packages/{package}/submit-payment', [PackageController::class, 'submitPayment'])->name('packages.submitPayment');
});

Route::get('/upgrade', [PackageController::class, 'index'])->name('packages.upgrade');


// Tasks

Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}/view', [TaskController::class, 'view'])->name('tasks.view');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
});


// withdrawal routes

Route::middleware(['auth'])->group(function () {
    // Wallet
    Route::get('/wallet', function () {
        $user = auth()->user();
        $wallet = $user->wallet;
        $withdrawals = Withdrawal::where('user_id', $user->id)->latest()->take(10)->get();

        $approvedWithdrawTotal = Withdrawal::where('user_id', $user->id)->where('status', 'approved')->sum('amount');
        $pendingWithdrawTotal = Withdrawal::where('user_id', $user->id)->where('status', 'pending')->sum('amount');
        $rejectedWithdrawTotal = Withdrawal::where('user_id', $user->id)->where('status', 'rejected')->sum('amount');

        return view('wallet', compact('wallet', 'withdrawals', 'approvedWithdrawTotal', 'pendingWithdrawTotal', 'rejectedWithdrawTotal'));
    })->name('wallet');

    // Withdrawals
    Route::get('/withdraw', [WithdrawalController::class, 'create'])->name('withdraw.create');
    Route::post('/withdraw', [WithdrawalController::class, 'store'])->name('withdraw.store');

    // Team
    Route::get('/team', [TeamController::class, 'index'])->name('team');
});




                                // Admin Routes



// Admin Withdrawals
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/withdrawals', [WithdrawalApprovalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/approve', [WithdrawalApprovalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [WithdrawalApprovalController::class, 'reject'])->name('withdrawals.reject');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/payments', [PaymentApprovalController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/approve', [PaymentApprovalController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [PaymentApprovalController::class, 'reject'])->name('payments.reject');
});



require __DIR__ . '/auth.php';
