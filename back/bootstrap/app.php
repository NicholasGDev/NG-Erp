<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // Backend API-only — sem web routes; o SPA é servido pelo container frontend.
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Rota raiz — identifica a API para quem acessar / no browser
            \Illuminate\Support\Facades\Route::get('/', fn () => response()->json([
                'api'       => 'Caronte ERP',
                'version'   => '1.0',
                'status'    => 'online',
                'health'    => '/up',
                'endpoints' => '/api/auth, /api/estoque/*',
            ]));

            \Illuminate\Support\Facades\Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api_erp_estoque.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Backend puro: todas as respostas de erro são JSON.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => true,
        );
    })->create();
