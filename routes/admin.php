<?php

use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CounterpartyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductReceiptController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin,manager,cashier,viewer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->middleware('can:categories.create')->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('can:categories.create')->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->middleware('can:categories.update')->name('categories.edit');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->middleware('can:categories.update')->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('can:categories.destroy')->name('categories.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->middleware('can:products.create')->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->middleware('can:products.create')->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->middleware('can:products.update')->name('products.edit');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->middleware('can:products.update')->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('can:products.destroy')->name('products.destroy');

    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->middleware('can:sales.create')->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->middleware('can:sales.create')->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->middleware('can:sales.destroy')->name('sales.destroy');

    Route::get('/cashiers', [CashierController::class, 'index'])->name('cashiers.index');
    Route::get('/cashiers/create', [CashierController::class, 'create'])->middleware('can:cashiers.create')->name('cashiers.create');
    Route::post('/cashiers', [CashierController::class, 'store'])->middleware('can:cashiers.create')->name('cashiers.store');
    Route::get('/cashiers/{cashier}/edit', [CashierController::class, 'edit'])->middleware('can:cashiers.update')->name('cashiers.edit');
    Route::patch('/cashiers/{cashier}', [CashierController::class, 'update'])->middleware('can:cashiers.update')->name('cashiers.update');
    Route::delete('/cashiers/{cashier}', [CashierController::class, 'destroy'])->middleware('can:cashiers.destroy')->name('cashiers.destroy');

    Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('/shifts/create', [ShiftController::class, 'create'])->middleware('can:shifts.create')->name('shifts.create');
    Route::post('/shifts', [ShiftController::class, 'store'])->middleware('can:shifts.create')->name('shifts.store');
    Route::get('/shifts/{shift}/edit', [ShiftController::class, 'edit'])->middleware('can:shifts.update')->name('shifts.edit');
    Route::patch('/shifts/{shift}', [ShiftController::class, 'update'])->middleware('can:shifts.update')->name('shifts.update');
    Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])->middleware('can:shifts.destroy')->name('shifts.destroy');

    Route::get('/counterparties', [CounterpartyController::class, 'index'])->name('counterparties.index');
    Route::get('/counterparties/create', [CounterpartyController::class, 'create'])->middleware('can:counterparties.create')->name('counterparties.create');
    Route::post('/counterparties', [CounterpartyController::class, 'store'])->middleware('can:counterparties.create')->name('counterparties.store');
    Route::get('/counterparties/{counterparty}', [CounterpartyController::class, 'show'])->name('counterparties.show');
    Route::get('/counterparties/{counterparty}/edit', [CounterpartyController::class, 'edit'])->middleware('can:counterparties.update')->name('counterparties.edit');
    Route::patch('/counterparties/{counterparty}', [CounterpartyController::class, 'update'])->middleware('can:counterparties.update')->name('counterparties.update');
    Route::delete('/counterparties/{counterparty}', [CounterpartyController::class, 'destroy'])->middleware('can:counterparties.destroy')->name('counterparties.destroy');

    Route::get('/product-receipts', [ProductReceiptController::class, 'index'])->name('product-receipts.index');
    Route::get('/product-receipts/create', [ProductReceiptController::class, 'create'])->middleware('can:product-receipts.create')->name('product-receipts.create');
    Route::post('/product-receipts', [ProductReceiptController::class, 'store'])->middleware('can:product-receipts.create')->name('product-receipts.store');
    Route::get('/product-receipts/{productReceipt}', [ProductReceiptController::class, 'show'])->name('product-receipts.show');
    Route::patch('/product-receipts/{productReceipt}', [ProductReceiptController::class, 'update'])->middleware('can:product-receipts.update')->name('product-receipts.update');
    Route::delete('/product-receipts/{productReceipt}', [ProductReceiptController::class, 'destroy'])->middleware('can:product-receipts.destroy')->name('product-receipts.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
