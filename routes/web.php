<?php

use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (Login, Register, Password Reset, Logout)
require __DIR__.'/auth.php';

// User Protected Routes
Route::middleware(['auth', 'is_user'])->group(function () {
    require __DIR__.'/user.php';
});

// Admin Protected Routes
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        require __DIR__.'/admin.php';
    });
