<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ไฟล์ routes/web.php
Route::middleware(['auth'])->group(function () {
    
    // ▼▼▼ บรรทัดนี้ครับ ▼▼▼
    Route::resource('products', ProductController::class); 
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('transactions', \App\Http\Controllers\TransactionController::class)->only(['index', 'create', 'store']);
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/stock', [\App\Http\Controllers\ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/transactions', [\App\Http\Controllers\ReportController::class, 'transactions'])->name('reports.transactions');

    Route::get('/test-font', function() {
        return view('reports.stock_pdf', [
            'products' => \App\Models\Product::with('category')->take(5)->get(),
            'generated_at' => now(),
        ]);
    });

});
