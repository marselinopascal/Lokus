<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    
    // --- GRUP RUTE LOGBOOK ---
    Route::get('/logbook/export', [LogbookController::class, 'export'])->name('logbook.export');
    Route::resource('logbook', LogbookController::class);

    // --- GRUP RUTE STOCK ---
    Route::get('/stock/export', [StockController::class, 'export'])->name('stock.export');
    Route::get('/stock/{stock}/qr', [StockController::class, 'generateQrCode'])->name('stock.qr');
    Route::resource('stock', StockController::class);
    
    // --- GRUP RUTE REPORTS ---
    Route::get('/reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
    Route::resource('reports', ReportsController::class);
    
    // --- GRUP RUTE SETTINGS & PROFILE ---
    Route::get('/settings', [ProfileController::class, 'edit'])->name('settings');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';