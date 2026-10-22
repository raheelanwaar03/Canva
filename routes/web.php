<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ReceiptController;

// Public Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return view('welcome');
});

// Authentication routes (Login, Register, Logout)
require __DIR__ . '/auth.php';

// USER ROUTES (For authenticated members)
Route::middleware(['auth'])->group(function () {
    require __DIR__ . '/user.php';
});

// Impersonation leave route (Must be outside admin middleware so impersonated users can exit)
Route::post('/impersonate/leave', [UserManagementController::class, 'leaveImpersonation'])
    ->middleware('auth')
    ->name('impersonate.leave');

// Receipts Serving Route
Route::get('/receipts/{filename}', [ReceiptController::class, 'show'])->name('receipts.show');

// ADMIN ROUTES (Protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        require __DIR__ . '/admin.php';
    });
