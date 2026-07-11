<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Stock\WarehouseController;
use App\Http\Controllers\Stock\SupplierController;
use App\Http\Controllers\Stock\ProductController;
use App\Http\Controllers\Stock\PurchaseOrderController;
use App\Http\Controllers\Stock\StockMovementController;
use App\Http\Controllers\Stock\PhysicalInventoryController;
use Illuminate\Support\Facades\Route;

/* ── Auth — public routes ─────────────────────────────────────────── */
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

/* ── Auth — protected routes ──────────────────────────────────────── */
Route::prefix('auth')->middleware('auth:api')->group(function () {
    Route::post('logout',  [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get ('me',      [AuthController::class, 'me']);
});

/* ── ERP — JWT-protected routes ───────────────────────────────────── */
Route::middleware('auth:api')->prefix('estoque')->group(function () {
    Route::apiResource('warehouses',        WarehouseController::class);
    Route::apiResource('suppliers',         SupplierController::class);
    Route::apiResource('products',          ProductController::class);
    Route::apiResource('purchase-orders',   PurchaseOrderController::class);
    Route::apiResource('inventories',       PhysicalInventoryController::class);

    // Kardex — no PUT/DELETE
    Route::get ('stock-movements',       [StockMovementController::class, 'index']);
    Route::post('stock-movements',       [StockMovementController::class, 'store']);
    Route::get ('stock-movements/{id}',  [StockMovementController::class, 'show']);
});
