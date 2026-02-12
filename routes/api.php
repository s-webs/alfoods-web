<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CashierController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CounterpartyController;
use App\Http\Controllers\Api\DebtPaymentController;
use App\Http\Controllers\Api\DebtorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductReceiptController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SetController;
use App\Http\Controllers\Api\ShiftController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes (no auth)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/sets', [SetController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('categories', CategoryController::class)->except(['index']);
    Route::apiResource('products', ProductController::class)->except(['index']);
    Route::apiResource('sets', SetController::class)->except(['index']);
    Route::post('returns', [SaleController::class, 'storeReturn']);
    Route::post('sales/{sale}/return', [SaleController::class, 'returnSale']);
    Route::post('sales/{sale}/pay-debt', [SaleController::class, 'payDebt']);
    Route::apiResource('sales', SaleController::class);
    Route::apiResource('cashiers', CashierController::class);
    Route::apiResource('shifts', ShiftController::class);
    Route::apiResource('counterparties', CounterpartyController::class);
    Route::apiResource('product-receipts', ProductReceiptController::class);
    Route::get('debt-payments', [DebtPaymentController::class, 'index']);
    Route::post('debt-payments', [DebtPaymentController::class, 'store']);
    Route::get('debtors', [DebtorController::class, 'index']);
});
