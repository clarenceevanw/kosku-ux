<?php

use App\Http\Controllers\KosBotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — KosKu
|--------------------------------------------------------------------------
*/

// ── KosBot AI ─────────────────────────────────────────────────────────
// Public endpoint — no authentication required.
// Rate-limited to prevent abuse.
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/bot/chat', [KosBotController::class, 'chat'])->name('api.bot.chat');
});
