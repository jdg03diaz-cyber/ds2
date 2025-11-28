<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\HomeController; // dashboard

// Página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas protegidas
Route::middleware(['auth'])->group(function() {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Formulario cambio de contraseña
    Route::get('/change', [ChangePasswordController::class, 'showChangeForm'])
        ->name('change.password.form');

    // Procesar cambio de contraseña
    Route::post('/change', [ChangePasswordController::class, 'update'])
        ->name('change.password');
});
