<?php

use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Marketplace homepage with marketplace data
Route::get('/', [MarketplaceController::class, 'index'])->name('home');

// Category browsing (public)
Route::get('/category/{slug}', [MarketplaceController::class, 'show'])->name('marketplace.category');

// Dashboard (authenticated users) - shows marketplace data
Route::get('/dashboard', [MarketplaceController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Product routes
Route::resource('products', ProductController::class)->middleware(['auth']);

// Order routes
Route::resource('orders', OrderController::class)->only(['index', 'store', 'show', 'update'])->middleware(['auth']);

// Wallet routes
Route::middleware('auth')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet', [WalletController::class, 'store'])->name('wallet.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';