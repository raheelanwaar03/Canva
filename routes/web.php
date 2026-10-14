<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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



require __DIR__ . '/auth.php';
