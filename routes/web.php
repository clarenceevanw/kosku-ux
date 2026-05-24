<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GuestBoardingHouseController;
use App\Http\Controllers\TenantDashboardController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────────────────────
// Guest / Public routes — KosKu
// ──────────────────────────────────────────────────────────────
Route::get('/', [GuestBoardingHouseController::class, 'index'])->name('home');
Route::get('/search', [GuestBoardingHouseController::class, 'search'])->name('search');
Route::get('/kos/{id}', [GuestBoardingHouseController::class, 'show'])->name('kos.show');
Route::get('/kos/{id}/booking', [BookingController::class, 'show'])->name('booking.show')->middleware('auth');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store')->middleware('auth');
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
    Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('tenant.dashboard');
    Route::get('/tagihan', [TenantDashboardController::class, 'payments'])->name('tenant.payments');
    Route::get('/tagihan/{payment}/checkout', [TenantDashboardController::class, 'paymentCheckout'])->name('tenant.payment.checkout');
    Route::post('/tagihan/{payment}/process', [TenantDashboardController::class, 'processPayment'])->name('tenant.payment.process');
    Route::get('/laporan', [TenantDashboardController::class, 'tickets'])->name('tenant.tickets');
    Route::get('/laporan/buat', [TenantDashboardController::class, 'createTicket'])->name('tenant.tickets.create');
    Route::post('/laporan', [TenantDashboardController::class, 'storeTicket'])->name('tenant.tickets.store');
    Route::get('/laporan/{ticket}', [TenantDashboardController::class, 'showTicket'])->name('tenant.tickets.show');
    Route::get('/kontrak', [TenantDashboardController::class, 'contract'])->name('tenant.contract');
    Route::get('/peraturan', [TenantDashboardController::class, 'rules'])->name('tenant.rules');
    Route::get('/pengaturan', [TenantDashboardController::class, 'settings'])->name('tenant.settings');
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

