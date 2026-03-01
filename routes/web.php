<?php

use App\Http\Customer\WarrantyController;
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

// Dominio: Customer
Route::middleware(['auth', 'verified'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('dashboard', [WarrantyController::class, 'dashboard'])
            ->name('dashboard');

        // Sección de Garantías
        Route::prefix('warranties')->name('warranties.')->group(function () {
            Route::get('/', [WarrantyController::class, 'index'])->name('index');
            Route::get('create', [WarrantyController::class, 'create'])->name('create');
            Route::post('/', [WarrantyController::class, 'store'])->name('store');
            Route::get('{warranty}', [WarrantyController::class, 'show'])->name('show');
            Route::delete('{warranty}', [WarrantyController::class, 'destroy'])->name('destroy');
        });
    });

Route::get('/error-cuenta', function () {
    return Inertia::render('errors/Cuenta');
})->name('error.cuenta')->middleware('auth');

require __DIR__.'/settings.php';
