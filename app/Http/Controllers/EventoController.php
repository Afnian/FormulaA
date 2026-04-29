<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Resultado;
use App\Models\Temporada;

class EventoController extends Controller
{
    public function index()
    {
        $temporadaFA = Temporada::where('categoria', 'formula_a')
                                ->where('activa', true)
                                ->first();

        $eventos = collect();

        if ($temporadaFA) {
            $eventos = Evento::with(['circuito', 'resultados.inscripcion.piloto', 'resultados.inscripcion.escuderia'])
                             ->where('id_temporada', $temporadaFA->id)
                             ->orderBy('ronda', 'asc')
                             ->get()
                             ->map(function ($evento) {
                                 // Si está completado, adjuntamos el podio (top 3)
                                 if ($evento->completado) {
                                     $evento->podio = $evento->resultados
                                         ->whereNotNull('posicion')
                                         ->sortBy('posicion')
                                         ->take(3)
                                         ->values();
                                 }
                                 return $evento;
                             });
        }

        return view('eventos.index', compact('eventos', 'temporadaFA'));
    }

    public function show($id)
    {
        $evento = Evento::with(['circuito', 'temporada'])->findOrFail($id);

        $resultados = collect();

        if ($evento->completado) {
            $resultados = Resultado::with([
                                'inscripcion.piloto',
                                'inscripcion.escuderia'
                            ])
                            ->where('id_evento', $evento->id)
                            ->orderByRaw('CASE WHEN posicion IS NULL THEN 1 ELSE 0 END')
                            ->orderBy('posicion', 'asc')
                            ->get()
                            ->map(function ($resultado) {
                                $resultado->puntos_totales =
                                    $resultado->pts_carrera +
                                    $resultado->pts_pole +
                                    $resultado->pts_vuelta_rap;
                                return $resultado;
                            });
        }

        return view('eventos.show', compact('evento', 'resultados'));
    }
}