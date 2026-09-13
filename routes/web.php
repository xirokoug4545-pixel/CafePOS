<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MenuController::class, 'index'])->name('home');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
Route::middleware('auth')->group(function () {
    Route::middleware('role:cashier,manager,admin')->group(function () {
        Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::patch('/admin/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
        Route::get('/products', [ProductController::class, 'index'])->name('Products.index');
        Route::get('/categories', [CategoryController::class, 'index'])->name('Categories.index');
    });

    Route::middleware('role:manager,admin')->group(function () {
        Route::resource('products', ProductController::class)->names('Products')->except(['index', 'show']);
        Route::resource('categories', CategoryController::class)->names('Categories')->except(['index', 'show']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('admin/users', AdminUserController::class)->names('admin.users')->except(['show']);
    });
});

Route::get('/dashboard', [ReportsController::class, 'index'])->middleware(['auth', 'role:cashier,manager,admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware('role:cashier,manager,admin')->group(function () {
        Route::post('/pos/orders/{order}/pay', [PosController::class, 'pay'])->name('pos.orders.pay');
        Route::post('/pos/orders/{order}/items/{item}/void', [PosController::class, 'voidItem'])->name('pos.orders.items.void');
        Route::post('/pos/waste', [PosController::class, 'logWaste'])->name('pos.waste.store');
        Route::post('/pos/till/open', [PosController::class, 'openTill'])->name('pos.till.open');
        Route::post('/pos/till/{tillSession}/close', [PosController::class, 'closeTill'])->name('pos.till.close');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';