<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestBoardingHouseController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────────────────────
// Guest / Public routes — KosKu
// ──────────────────────────────────────────────────────────────
Route::get('/', [GuestBoardingHouseController::class, 'index'])->name('home');
Route::get('/search', [GuestBoardingHouseController::class, 'search'])->name('search');
Route::get('/kos/{id}', [GuestBoardingHouseController::class, 'show'])->name('kos.show');
Route::get('/bot', [GuestBoardingHouseController::class, 'bot'])->name('bot');

// ──────────────────────────────────────────────────────────────
// Authentication routes (guest only)
// ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showAuthForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Google OAuth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// ──────────────────────────────────────────────────────────────
// Authenticated routes
// ──────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ──────────────────────────────────────────────────────────────
// Owner routes (Protected by RoleMiddleware)
// ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:owner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', function () {
        return 'Welcome to Owner Dashboard';
    })->name('owner.dashboard');
});

// ──────────────────────────────────────────────────────────────
// Admin routes (Protected by RoleMiddleware)
// ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return 'Welcome to Admin Dashboard';
    })->name('admin.dashboard');
});

