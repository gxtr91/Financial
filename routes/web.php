<?php

use App\Http\Controllers\CuentasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaccionesController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('pages.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('index');
    Route::prefix('cuentas')->name('cuentas.')->group(function () {
        Route::get('/', CuentasController::class)->name('index');
        Route::get('/json', [CuentasController::class, 'json'])->name('json');
        Route::post('/store', [CuentasController::class, 'store'])->name('store');
        Route::post('/update', [CuentasController::class, 'update'])->name('update');


    });

    Route::prefix('transacciones')->name('transacciones.')->group(function () {
        Route::get('/', TransaccionesController::class)->name('index');
        Route::get('/json', [TransaccionesController::class, 'json'])->name('json');
        Route::post('/store', [TransaccionesController::class, 'store'])->name('store');
        Route::get('/load-transacciones', [TransaccionesController::class, 'loadMore'])->name('load');


    });
});

require __DIR__.'/auth.php';
