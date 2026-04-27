<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ruta pública temporal (se ampliará en módulos siguientes)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

use App\Http\Controllers\EscuderiaController;

Route::get('/escuderias', [EscuderiaController::class, 'index'])->name('escuderias.index');
Route::get('/escuderias/{id}', [EscuderiaController::class, 'show'])->name('escuderias.show');

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

use App\Http\Controllers\EventoController;

Route::get('/calendario', [EventoController::class, 'index'])->name('calendario.index');
Route::get('/calendario/{id}', [EventoController::class, 'show'])->name('calendario.show');

use App\Http\Controllers\ClasificacionController;

Route::get('/resultados/pilotos', [ClasificacionController::class, 'pilotos'])->name('clasificaciones.pilotos');
Route::get('/resultados/constructores', [ClasificacionController::class, 'constructores'])->name('clasificaciones.constructores');


use App\Http\Controllers\FormulaBController;

Route::get('/formula-b', [FormulaBController::class, 'index'])->name('formula-b.index');