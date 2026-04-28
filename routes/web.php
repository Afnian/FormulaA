<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EscuderiaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ClasificacionController;
use App\Http\Controllers\FormulaBController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/escuderias', [EscuderiaController::class, 'index'])->name('escuderias.index');
Route::get('/escuderias/{id}', [EscuderiaController::class, 'show'])->name('escuderias.show');

Route::get('/calendario', [EventoController::class, 'index'])->name('calendario.index');
Route::get('/calendario/{id}', [EventoController::class, 'show'])->name('calendario.show');

Route::get('/resultados/pilotos', [ClasificacionController::class, 'pilotos'])->name('clasificaciones.pilotos');
Route::get('/resultados/constructores', [ClasificacionController::class, 'constructores'])->name('clasificaciones.constructores');

Route::get('/formula-b', [FormulaBController::class, 'index'])->name('formula-b.index');

Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
Route::get('/noticias/{id}', [NoticiaController::class, 'show'])->name('noticias.show');

/*
|--------------------------------------------------------------------------
| Rutas de autenticación (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Zona piloto
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:piloto'])->group(function () {
    Route::get('/mi-perfil', [PerfilController::class, 'show'])->name('perfil.show');
    Route::get('/inscripcion', [InscripcionController::class, 'create'])->name('inscripcion.create');
    Route::post('/inscripcion', [InscripcionController::class, 'store'])->name('inscripcion.store');
});

/*
|--------------------------------------------------------------------------
| Panel de administración — solo admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('dashboard')->name('dashboard.')->group(function () {

    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Rutas temporales (se reemplazarán en bloques 4.2 - 4.6)
    Route::get('/escuderias', fn() => 'Próximamente 4.2')->name('escuderias.index');
    Route::get('/escuderias/create', fn() => 'Próximamente 4.2')->name('escuderias.create');
    Route::get('/temporadas', fn() => 'Próximamente 4.3')->name('temporadas.index');
    Route::get('/circuitos', fn() => 'Próximamente 4.3')->name('circuitos.index');
    Route::get('/eventos', fn() => 'Próximamente 4.3')->name('eventos.index');
    Route::get('/eventos/create', fn() => 'Próximamente 4.3')->name('eventos.create');
    Route::get('/eventos/{id}/edit', fn() => 'Próximamente 4.3')->name('eventos.edit');
    Route::get('/eventos/{id}/resultados', fn() => 'Próximamente 4.4')->name('eventos.resultados');
    Route::get('/solicitudes', fn() => 'Próximamente 4.6')->name('solicitudes.index');

});

/*
|--------------------------------------------------------------------------
| Panel — admin y editor (noticias)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,editor'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/noticias', function () {
        return 'Gestión de noticias — próximamente (Bloque 4.5)';
    })->name('noticias.index');
});