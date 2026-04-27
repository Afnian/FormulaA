<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ruta pública temporal (se ampliará en módulos siguientes)
|--------------------------------------------------------------------------
*/
// routes/web.php  — reemplaza la ruta '/' existente
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Rutas de autenticación (generadas por Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Zona piloto
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:piloto'])->group(function () {
    Route::get('/mi-perfil', function () {
        return 'Perfil del piloto — próximamente (Bloque 3.1)';
    })->name('perfil.show');
});

/*
|--------------------------------------------------------------------------
| Panel de administración
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', function () {
        return 'Dashboard admin — próximamente (Bloque 4.1)';
    })->name('index');
});

/*
|--------------------------------------------------------------------------
| Rutas editor (noticias)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,editor'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/noticias', function () {
        return 'Gestión de noticias — próximamente (Bloque 4.5)';
    })->name('noticias.index');
});