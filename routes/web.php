<?php

use Illuminate\Support\Facades\Route;

// Serve o SPA Vue para qualquer rota não-API (landing + /app/*)
Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '^(?!api).*');
