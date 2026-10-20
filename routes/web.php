<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ImpersonateController;
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

// USER ROUTES ONLY (Blocked for Admin)
Route::middleware(['auth', 'is_user'])->group(function () {
    require __DIR__ . '/user.php';
});


// Dedicated route to serve payment receipts directly via PHP
Route::get('/receipts/{filename}', [ReceiptController::class, 'show'])->name('receipts.show');

Route::get('/leave-impersonation', [ImpersonateController::class, 'leave'])->name('impersonate.leave');

// ADMIN ROUTES ONLY (Blocked for Regular Users)
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        require __DIR__ . '/admin.php';
    });
