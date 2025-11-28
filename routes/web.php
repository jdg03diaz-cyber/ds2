<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\CustomLogoutController;

Route::post('/logout', [CustomLogoutController::class, 'destroy'])
    ->name('logout');

// Ruta pública (página principal)
Route::get('/', function () {
    return view('welcome');
});

// Rutas que requieren autenticación (solo usuarios logueados)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Nueva ruta: Motos
    Route::get('/motos', function () {
        return view('motos');
    })->name('motos');

    // Nueva ruta: Carros
    Route::get('/carros', function () {
        return view('carros');
    })->name('carros');

    
});
