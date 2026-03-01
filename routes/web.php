<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// 1. Landing page pública (Exclusiva para Clientes)
Route::get('/', function () {
    return Inertia::render('welcome/Client', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');


// 2. Landing page corporativa (Exclusiva para Empleados/Equipo Interno)
Route::get('/interno', function () {
    return Inertia::render('welcome/Employee', [
        'canRegister' => false,
    ]);
})->name('home.employee');


// Agrupamos las rutas exclusivas del cliente
Route::prefix('cliente')->group(function () {

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});

Route::get('/error-cuenta', function () {
    return Inertia::render('errors/Cuenta');
})->name('error.cuenta')->middleware('auth');

require __DIR__ . '/settings.php';
