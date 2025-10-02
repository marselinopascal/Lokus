<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogbookController; // Tambahkan ini
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportsController;  // Tambahkan ini
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('logbook', LogbookController::class);
    
    Route::get('/stock/export', [StockController::class, 'export'])->name('stock.export');
    Route::get('/stock/{stock}/qr', [StockController::class, 'generateQrCode'])->name('stock.qr');
    Route::resource('stock', StockController::class);
    Route::get('/reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
    Route::resource('reports', ReportsController::class);
     
    
    // Rute untuk Settings sekarang ditangani oleh ProfileController@edit
    Route::get('/settings', [ProfileController::class, 'edit'])->name('settings');

    // Rute profil default dari Breeze
    // Kita biarkan ini karena form update dan delete mengarah ke sini
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
