<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentApprovalController;
use App\Http\Controllers\Admin\WithdrawalApprovalController;
use App\Http\Controllers\Admin\PackageManagementController;
use App\Http\Controllers\Admin\TaskManagementController;
use App\Http\Controllers\Admin\ChannelManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\PlatformSettingController;
use App\Http\Controllers\Admin\RefundManagementController;

// Main Admin Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// Payment Approvals
Route::get('/payments', [PaymentApprovalController::class, 'index'])->name('payments.index');
Route::post('/payments/{payment}/approve', [PaymentApprovalController::class, 'approve'])->name('payments.approve');
Route::post('/payments/{payment}/reject', [PaymentApprovalController::class, 'reject'])->name('payments.reject');

// Withdrawal Requests
Route::get('/withdrawals', [WithdrawalApprovalController::class, 'index'])->name('withdrawals.index');
Route::post('/withdrawals/{withdrawal}/approve', [WithdrawalApprovalController::class, 'approve'])->name('withdrawals.approve');
Route::post('/withdrawals/{withdrawal}/reject', [WithdrawalApprovalController::class, 'reject'])->name('withdrawals.reject');

// Packages & Task Limits Config
Route::get('/packages', [PackageManagementController::class, 'index'])->name('packages.index');
Route::put('/packages/{package}', [PackageManagementController::class, 'update'])->name('packages.update');

// Ad Tasks Inventory
Route::get('/tasks', [TaskManagementController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskManagementController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{task}', [TaskManagementController::class, 'update'])->name('tasks.update');
Route::post('/tasks/{task}/toggle', [TaskManagementController::class, 'toggle'])->name('tasks.toggle');
Route::delete('/tasks/{task}', [TaskManagementController::class, 'destroy'])->name('tasks.destroy');

// Community Channels Management
Route::get('/channels', [ChannelManagementController::class, 'index'])->name('channels.index');
Route::post('/channels', [ChannelManagementController::class, 'store'])->name('channels.store');
Route::put('/channels/{channel}', [ChannelManagementController::class, 'update'])->name('channels.update');
Route::post('/channels/{channel}/toggle', [ChannelManagementController::class, 'toggle'])->name('channels.toggle');
Route::delete('/channels/{channel}', [ChannelManagementController::class, 'destroy'])->name('channels.destroy');

// ==========================================
// USER MANAGEMENT (Status Sub-routes first to avoid 404 wildcard collisions)
// ==========================================
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('index');
    Route::get('/pending', [UserManagementController::class, 'pending'])->name('pending');
    Route::get('/approved', [UserManagementController::class, 'approved'])->name('approved');
    Route::get('/rejected', [UserManagementController::class, 'rejected'])->name('rejected');

    // User Operations on Specific ID (Wildcards placed AFTER static paths)
    Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
    Route::post('/{user}/adjust-balance', [UserManagementController::class, 'adjustBalance'])->name('adjustBalance');
    Route::post('/{user}/impersonate', [UserManagementController::class, 'impersonate'])->name('impersonate');
    Route::post('/{user}/login-as', [UserManagementController::class, 'impersonate'])->name('loginAs');
});

// Platform Settings
Route::get('/settings', [PlatformSettingController::class, 'index'])->name('settings.index');
Route::post('/settings', [PlatformSettingController::class, 'update'])->name('settings.update');

// Admin Refund Requests
Route::get('/refunds', [RefundManagementController::class, 'index'])->name('refunds.index');
Route::post('/refunds/{refund}/approve', [RefundManagementController::class, 'approve'])->name('refunds.approve');
Route::post('/refunds/{refund}/reject', [RefundManagementController::class, 'reject'])->name('refunds.reject');
