<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/menu/{barcode}', [MenuController::class, 'show'])->name('menu.show');
Route::post('/menu/{barcode}/order', [MenuController::class, 'storeOrder'])->name('menu.order.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [OrderController::class, 'page'])->name('orders.index');
    Route::get('/orders/data', [OrderController::class, 'index'])->name('orders.data');
    Route::get('/orders/check-new', [OrderController::class, 'checkNewOrders'])->name('orders.checkNew');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/barcode', [DashboardController::class, 'barcode'])->name('barcode.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
});

require __DIR__.'/auth.php';
