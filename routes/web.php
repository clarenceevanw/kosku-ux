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
Route::get('/kos/{id}/booking', [App\Http\Controllers\BookingController::class, 'show'])->name('booking.show')->middleware('auth');
Route::post('/booking', [App\Http\Controllers\BookingController::class, 'store'])->name('booking.store')->middleware('auth');
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
// Tenant routes (Protected by RoleMiddleware)
// ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:tenant'])->prefix('tenant')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\TenantDashboardController::class, 'index'])->name('tenant.dashboard');
    Route::get('/tagihan', [App\Http\Controllers\TenantDashboardController::class, 'payments'])->name('tenant.payments');
    Route::get('/tagihan/{payment}/checkout', [App\Http\Controllers\TenantDashboardController::class, 'paymentCheckout'])->name('tenant.payment.checkout');
    Route::post('/tagihan/{payment}/process', [App\Http\Controllers\TenantDashboardController::class, 'processPayment'])->name('tenant.payment.process');
    Route::get('/laporan', [App\Http\Controllers\TenantDashboardController::class, 'tickets'])->name('tenant.tickets');
    Route::get('/laporan/buat', [App\Http\Controllers\TenantDashboardController::class, 'createTicket'])->name('tenant.tickets.create');
    Route::post('/laporan', [App\Http\Controllers\TenantDashboardController::class, 'storeTicket'])->name('tenant.tickets.store');
    Route::get('/laporan/{ticket}', [App\Http\Controllers\TenantDashboardController::class, 'showTicket'])->name('tenant.tickets.show');
    Route::get('/kontrak', [App\Http\Controllers\TenantDashboardController::class, 'contract'])->name('tenant.contract');
    Route::get('/peraturan', [App\Http\Controllers\TenantDashboardController::class, 'rules'])->name('tenant.rules');
    Route::get('/pengaturan', [App\Http\Controllers\TenantDashboardController::class, 'settings'])->name('tenant.settings');
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



// ──────────────────────────────────────────────────────────────
// UX2 routes
// ──────────────────────────────────────────────────────────────

