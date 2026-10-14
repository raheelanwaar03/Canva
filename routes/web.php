<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PackageController;

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




// Admin Routes

use App\Http\Controllers\Admin\PaymentApprovalController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/payments', [PaymentApprovalController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/approve', [PaymentApprovalController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [PaymentApprovalController::class, 'reject'])->name('payments.reject');
});



require __DIR__ . '/auth.php';
