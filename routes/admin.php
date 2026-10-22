<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentApprovalController;
use App\Http\Controllers\Admin\WithdrawalApprovalController;
use App\Http\Controllers\Admin\PackageManagementController;
use App\Http\Controllers\Admin\TaskManagementController;
use App\Http\Controllers\Admin\ChannelManagementController;
use App\Http\Controllers\Admin\UserManagementController;

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
Route::post('/tasks/{task}/toggle', [TaskManagementController::class, 'toggle'])->name('tasks.toggle');
Route::delete('/tasks/{task}', [TaskManagementController::class, 'destroy'])->name('tasks.destroy');

// Community Channels Management
Route::get('/channels', [ChannelManagementController::class, 'index'])->name('channels.index');
Route::post('/channels', [ChannelManagementController::class, 'store'])->name('channels.store');
Route::put('/channels/{channel}', [ChannelManagementController::class, 'update'])->name('channels.update');
Route::post('/channels/{channel}/toggle', [ChannelManagementController::class, 'toggle'])->name('channels.toggle');
Route::delete('/channels/{channel}', [ChannelManagementController::class, 'destroy'])->name('channels.destroy');

// Impersonate / Login as user
Route::post('/users/{user}/impersonate', [UserManagementController::class, 'impersonate'])->name('users.impersonate');

// User Management (Clean, single definitions)
Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
Route::post('/users/{user}/adjust-balance', [UserManagementController::class, 'adjustBalance'])->name('users.adjustBalance');
