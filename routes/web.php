<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GuestBoardingHouseController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\KosController;
use App\Http\Controllers\Owner\RoomController;
use App\Http\Controllers\Owner\TicketController;
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
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Manajemen Kos
    Route::prefix('kos')->name('kos.')->group(function () {
        Route::get('/', [KosController::class, 'index'])->name('index');
        Route::post('/', [KosController::class, 'store'])->name('store');
        Route::get('/{id}', [KosController::class, 'show'])->name('show');
        Route::put('/{id}', [KosController::class, 'update'])->name('update');
        Route::delete('/{id}', [KosController::class, 'destroy'])->name('destroy');
    });
    
    // Manajemen Kamar
    Route::prefix('kamar')->name('rooms.')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::post('/', [RoomController::class, 'store'])->name('store');
        Route::get('/{id}', [RoomController::class, 'show'])->name('show');
        Route::put('/{id}', [RoomController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoomController::class, 'destroy'])->name('destroy');
    });
    
    // Laporan Kerusakan
    Route::get('/laporan', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/laporan/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::put('/laporan/{id}', [TicketController::class, 'updateStatus'])->name('tickets.update');
    
    // Pemesanan
    Route::get('/pemesanan', [\App\Http\Controllers\Owner\TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/pemesanan/{id}/approve', [\App\Http\Controllers\Owner\TransactionController::class, 'approve'])->name('transactions.approve');

    // Keuangan
    Route::get('/keuangan', [\App\Http\Controllers\Owner\FinanceController::class, 'index'])->name('keuangan.index');
    Route::post('/keuangan/tagihan/{id}/remind', [\App\Http\Controllers\Owner\FinanceController::class, 'remind'])->name('keuangan.remind');
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
Route::prefix('ux2')->name('ux2.')->group(function () {
    Route::get('/', [App\Http\Controllers\ux2\GuestBoardingHouseController::class, 'index'])->name('home');
    Route::get('/search', [App\Http\Controllers\ux2\GuestBoardingHouseController::class, 'search'])->name('search');
    Route::get('/kos/{id}', [App\Http\Controllers\ux2\GuestBoardingHouseController::class, 'show'])->name('kos.show');
    Route::get('/kos/{id}/booking', [App\Http\Controllers\ux2\BookingController::class, 'show'])->name('booking.show')->middleware('auth');
    Route::post('/booking', [App\Http\Controllers\ux2\BookingController::class, 'store'])->name('booking.store')->middleware('auth');
    Route::get('/booking/checkout', [App\Http\Controllers\ux2\BookingController::class, 'checkout'])->name('booking.checkout')->middleware('auth');
    Route::get('/bot', [App\Http\Controllers\ux2\GuestBoardingHouseController::class, 'bot'])->name('bot');

    Route::middleware('guest')->group(function () {
        Route::get('/login', [App\Http\Controllers\ux2\AuthController::class, 'showAuthForm'])->name('login');
        Route::get('/signup', [App\Http\Controllers\ux2\AuthController::class, 'showSignupForm'])->name('signup');
        Route::post('/login', [App\Http\Controllers\ux2\AuthController::class, 'login'])->name('login.store');
        Route::post('/register', [App\Http\Controllers\ux2\AuthController::class, 'register'])->name('register');
        Route::get('/auth/google', [App\Http\Controllers\ux2\AuthController::class, 'redirectToGoogle'])->name('auth.google');
        Route::get('/auth/google/callback', [App\Http\Controllers\ux2\AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [App\Http\Controllers\ux2\AuthController::class, 'logout'])->name('logout');
    });

    Route::middleware(['auth', 'role:tenant'])->prefix('tenant')->name('tenant.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\ux2\TenantDashboardController::class, 'index'])->name('dashboard');
        Route::get('/tagihan', [App\Http\Controllers\ux2\TenantDashboardController::class, 'payments'])->name('payments');
        Route::get('/tagihan/{payment}/checkout', [App\Http\Controllers\ux2\TenantDashboardController::class, 'paymentCheckout'])->name('payment.checkout');
        Route::post('/tagihan/{payment}/process', [App\Http\Controllers\ux2\TenantDashboardController::class, 'processPayment'])->name('payment.process');
        Route::get('/laporan', [App\Http\Controllers\ux2\TenantDashboardController::class, 'tickets'])->name('tickets');
        Route::get('/laporan/buat', [App\Http\Controllers\ux2\TenantDashboardController::class, 'createTicket'])->name('tickets.create');
        Route::post('/laporan', [App\Http\Controllers\ux2\TenantDashboardController::class, 'storeTicket'])->name('tickets.store');
        Route::get('/laporan/{ticket}', [App\Http\Controllers\ux2\TenantDashboardController::class, 'showTicket'])->name('tickets.show');
        Route::get('/kontrak', [App\Http\Controllers\ux2\TenantDashboardController::class, 'contract'])->name('contract');
        Route::get('/peraturan', [App\Http\Controllers\ux2\TenantDashboardController::class, 'rules'])->name('rules');
        Route::get('/pengaturan', [App\Http\Controllers\ux2\TenantDashboardController::class, 'settings'])->name('settings');
    });

    Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\ux2\Owner\DashboardController::class, 'index'])->name('dashboard');
        
        // Kelola Kos
        Route::get('/kos', [App\Http\Controllers\ux2\Owner\KosController::class, 'index'])->name('kos.index');
        Route::post('/kos', [App\Http\Controllers\ux2\Owner\KosController::class, 'store'])->name('kos.store');
        Route::put('/kos/{id}', [App\Http\Controllers\ux2\Owner\KosController::class, 'update'])->name('kos.update');
        Route::delete('/kos/{id}', [App\Http\Controllers\ux2\Owner\KosController::class, 'destroy'])->name('kos.destroy');
        
        // Kelola Kamar
        Route::get('/rooms', [App\Http\Controllers\ux2\Owner\RoomController::class, 'index'])->name('rooms.index');
        Route::post('/rooms', [App\Http\Controllers\ux2\Owner\RoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{id}', [App\Http\Controllers\ux2\Owner\RoomController::class, 'show'])->name('rooms.show');
        Route::put('/rooms/{id}', [App\Http\Controllers\ux2\Owner\RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{id}', [App\Http\Controllers\ux2\Owner\RoomController::class, 'destroy'])->name('rooms.destroy');
        
        // Laporan Kerusakan
        Route::get('/tickets', [App\Http\Controllers\ux2\Owner\TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{id}', [App\Http\Controllers\ux2\Owner\TicketController::class, 'show'])->name('tickets.show');
        Route::put('/tickets/{id}', [App\Http\Controllers\ux2\Owner\TicketController::class, 'updateStatus'])->name('tickets.update');
        
        // Keuangan
        Route::get('/keuangan', [App\Http\Controllers\ux2\Owner\FinanceController::class, 'index'])->name('keuangan.index');
        Route::get('/keuangan/laporan', [App\Http\Controllers\ux2\Owner\FinanceController::class, 'laporan'])->name('keuangan.laporan');
        Route::post('/keuangan/tagihan/{id}/remind', [App\Http\Controllers\ux2\Owner\FinanceController::class, 'remind'])->name('keuangan.remind');
    });
});
