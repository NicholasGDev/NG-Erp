<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Estoque\ArmazemController;
use App\Http\Controllers\Estoque\FornecedorController;
use App\Http\Controllers\Estoque\ProdutoController;
use App\Http\Controllers\Estoque\PedidoCompraController;
use App\Http\Controllers\Estoque\MovimentacaoEstoqueController;
use App\Http\Controllers\Estoque\InventarioController;
use Illuminate\Support\Facades\Route;

/* ── Auth — rotas públicas ────────────────────────────────────────── */
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

/* ── Auth — rotas protegidas ──────────────────────────────────────── */
Route::prefix('auth')->middleware('auth:api')->group(function () {
    Route::post('logout',  [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get ('me',      [AuthController::class, 'me']);
});

/* ── ERP — rotas protegidas por JWT ───────────────────────────────── */
Route::middleware('auth:api')->prefix('estoque')->group(function () {
    Route::apiResource('armazens',              ArmazemController::class);
    Route::apiResource('fornecedores',          FornecedorController::class);
    Route::apiResource('produtos',              ProdutoController::class);
    Route::apiResource('pedidos-compra',        PedidoCompraController::class);
    Route::apiResource('inventarios',           InventarioController::class);

    // Kardex — sem PUT/DELETE
    Route::get ('movimentacoes',      [MovimentacaoEstoqueController::class, 'index']);
    Route::post('movimentacoes',      [MovimentacaoEstoqueController::class, 'store']);
    Route::get ('movimentacoes/{id}', [MovimentacaoEstoqueController::class, 'show']);
});
