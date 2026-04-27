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
use App\Http\Controllers\PerfilController;

Route::middleware(['auth', 'role:piloto'])->group(function () {
    Route::get('/mi-perfil', [PerfilController::class, 'show'])->name('perfil.show');
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

use App\Http\Controllers\NoticiaController;

Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
Route::get('/noticias/{id}', [NoticiaController::class, 'show'])->name('noticias.show');