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
use App\Http\Controllers\IAController;
use App\Http\Controllers\Dashboard\EscuderiaController as DashboardEscuderiaController;
use App\Http\Controllers\Dashboard\TemporadaController as DashboardTemporadaController;
use App\Http\Controllers\Dashboard\CircuitoController as DashboardCircuitoController;
use App\Http\Controllers\Dashboard\EventoController as DashboardEventoController;
use App\Http\Controllers\Dashboard\ResultadoController as DashboardResultadoController;
use App\Http\Controllers\Dashboard\NoticiaController as DashboardNoticiaController;
use App\Http\Controllers\Dashboard\SolicitudController as DashboardSolicitudController;
use App\Http\Controllers\Dashboard\PilotoController as DashboardPilotoController;

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

Route::get('/consultas-ia', [IAController::class, 'index'])->name('ia.index');
Route::post('/consultas-ia', [IAController::class, 'query'])->name('ia.query');

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

    // Pilotos CRUD
    Route::get('/pilotos', [DashboardPilotoController::class, 'index'])->name('pilotos.index');
    Route::get('/pilotos/create', [DashboardPilotoController::class, 'create'])->name('pilotos.create');
    Route::post('/pilotos', [DashboardPilotoController::class, 'store'])->name('pilotos.store');
    Route::get('/pilotos/{id}/edit', [DashboardPilotoController::class, 'edit'])->name('pilotos.edit');
    Route::put('/pilotos/{id}', [DashboardPilotoController::class, 'update'])->name('pilotos.update');
    Route::delete('/pilotos/{id}', [DashboardPilotoController::class, 'destroy'])->name('pilotos.destroy');

    // Escuderías CRUD
    Route::get('/escuderias', [DashboardEscuderiaController::class, 'index'])->name('escuderias.index');
    Route::get('/escuderias/create', [DashboardEscuderiaController::class, 'create'])->name('escuderias.create');
    Route::post('/escuderias', [DashboardEscuderiaController::class, 'store'])->name('escuderias.store');
    Route::get('/escuderias/{id}', [DashboardEscuderiaController::class, 'show'])->name('escuderias.show');
    Route::get('/escuderias/{id}/edit', [DashboardEscuderiaController::class, 'edit'])->name('escuderias.edit');
    Route::put('/escuderias/{id}', [DashboardEscuderiaController::class, 'update'])->name('escuderias.update');
    Route::delete('/escuderias/{id}', [DashboardEscuderiaController::class, 'destroy'])->name('escuderias.destroy');
    Route::post('/escuderias/{id}/pilotos', [DashboardEscuderiaController::class, 'asignarPiloto'])->name('escuderias.pilotos.store');
    Route::delete('/escuderias/{id}/pilotos/{inscripcionId}', [DashboardEscuderiaController::class, 'quitarPiloto'])->name('escuderias.pilotos.destroy');

    // Temporadas CRUD
    Route::get('/temporadas', [DashboardTemporadaController::class, 'index'])->name('temporadas.index');
    Route::get('/temporadas/create', [DashboardTemporadaController::class, 'create'])->name('temporadas.create');
    Route::post('/temporadas', [DashboardTemporadaController::class, 'store'])->name('temporadas.store');
    Route::get('/temporadas/{id}/edit', [DashboardTemporadaController::class, 'edit'])->name('temporadas.edit');
    Route::put('/temporadas/{id}', [DashboardTemporadaController::class, 'update'])->name('temporadas.update');
    Route::delete('/temporadas/{id}', [DashboardTemporadaController::class, 'destroy'])->name('temporadas.destroy');

    // Circuitos CRUD
    Route::get('/circuitos', [DashboardCircuitoController::class, 'index'])->name('circuitos.index');
    Route::get('/circuitos/create', [DashboardCircuitoController::class, 'create'])->name('circuitos.create');
    Route::post('/circuitos', [DashboardCircuitoController::class, 'store'])->name('circuitos.store');
    Route::get('/circuitos/{id}/edit', [DashboardCircuitoController::class, 'edit'])->name('circuitos.edit');
    Route::put('/circuitos/{id}', [DashboardCircuitoController::class, 'update'])->name('circuitos.update');
    Route::delete('/circuitos/{id}', [DashboardCircuitoController::class, 'destroy'])->name('circuitos.destroy');

    // Eventos CRUD
    Route::get('/eventos', [DashboardEventoController::class, 'index'])->name('eventos.index');
    Route::get('/eventos/create', [DashboardEventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [DashboardEventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{id}/edit', [DashboardEventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{id}', [DashboardEventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{id}', [DashboardEventoController::class, 'destroy'])->name('eventos.destroy');
    Route::post('/eventos/{id}/completar', [DashboardEventoController::class, 'completar'])->name('eventos.completar');

    // Resultados
    Route::get('/eventos/{id}/resultados', [DashboardResultadoController::class, 'edit'])->name('eventos.resultados');
    Route::put('/eventos/{id}/resultados', [DashboardResultadoController::class, 'update'])->name('eventos.resultados.update');

    // Solicitudes de inscripción
    Route::get('/solicitudes', [DashboardSolicitudController::class, 'index'])->name('solicitudes.index');
    Route::put('/solicitudes/{id}', [DashboardSolicitudController::class, 'update'])->name('solicitudes.update');

});

/*
|--------------------------------------------------------------------------
| Panel — admin y editor (noticias)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,editor'])->prefix('dashboard')->name('dashboard.')->group(function () {

    // Noticias CRUD
    Route::get('/noticias', [DashboardNoticiaController::class, 'index'])->name('noticias.index');
    Route::get('/noticias/create', [DashboardNoticiaController::class, 'create'])->name('noticias.create');
    Route::post('/noticias', [DashboardNoticiaController::class, 'store'])->name('noticias.store');
    Route::get('/noticias/{id}/edit', [DashboardNoticiaController::class, 'edit'])->name('noticias.edit');
    Route::put('/noticias/{id}', [DashboardNoticiaController::class, 'update'])->name('noticias.update');
    Route::delete('/noticias/{id}', [DashboardNoticiaController::class, 'destroy'])->name('noticias.destroy');
    Route::post('/noticias/{id}/publicar', [DashboardNoticiaController::class, 'publicar'])->name('noticias.publicar');

});