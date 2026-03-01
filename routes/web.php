<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// landing page pública
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

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
