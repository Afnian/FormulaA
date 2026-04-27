<?php

namespace App\Http\Controllers;

use App\Models\Temporada;
use App\Models\InscripcionPiloto;
use App\Models\Resultado;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function show()
    {
        $usuario = Auth::user();
        $piloto  = $usuario->piloto;

        if (!$piloto) {
            return redirect()->route('home')
                             ->with('error', 'No tienes un perfil de piloto asignado.');
        }

        $temporadaFA = Temporada::where('categoria', 'formula_a')
                                ->where('activa', true)
                                ->first();

        // Inscripción activa en FA
        $inscripcionActiva = null;
        $puntosTemporada   = 0;

        if ($temporadaFA) {
            $inscripcionActiva = InscripcionPiloto::with(['escuderia', 'temporada'])
                                    ->where('id_piloto', $piloto->id)
                                    ->where('id_temporada', $temporadaFA->id)
                                    ->first();

            if ($inscripcionActiva) {
                $puntosTemporada = Resultado::where('id_inscripcion', $inscripcionActiva->id)
                                            ->get()
                                            ->sum(fn($r) => $r->pts_carrera + $r->pts_pole + $r->pts_vuelta_rap);
            }
        }

        // Historial completo de resultados en todas las temporadas
        $todasInscripciones = InscripcionPiloto::where('id_piloto', $piloto->id)->pluck('id');

        $historial = Resultado::with([
                        'evento.circuito',
                        'evento.temporada',
                        'inscripcion.escuderia'
                    ])
                    ->whereIn('id_inscripcion', $todasInscripciones)
                    ->orderByDesc('id_evento')
                    ->get()
                    ->map(function ($r) {
                        $r->puntos_totales = $r->pts_carrera + $r->pts_pole + $r->pts_vuelta_rap;
                        return $r;
                    });

        // Estadísticas globales
        $stats = [
            'carreras'  => $historial->count(),
            'victorias' => $historial->where('posicion', 1)->count(),
            'podios'    => $historial->whereIn('posicion', [1, 2, 3])->count(),
            'poles'     => $historial->where('pts_pole', '>', 0)->count(),
            'vueltas_rapidas' => $historial->where('pts_vuelta_rap', '>', 0)->count(),
            'puntos_totales'  => $historial->sum('puntos_totales'),
        ];

        // Solicitud de inscripción si existe
        $solicitud = $usuario->solicitudAcceso;

        return view('perfil.show', compact(
            'usuario',
            'piloto',
            'inscripcionActiva',
            'puntosTemporada',
            'historial',
            'stats',
            'solicitud',
            'temporadaFA'
        ));
    }
}