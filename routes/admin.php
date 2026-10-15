<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentApprovalController;
use App\Http\Controllers\Admin\WithdrawalApprovalController;
use App\Http\Controllers\Admin\PackageManagementController;
use App\Http\Controllers\Admin\TaskManagementController;

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
