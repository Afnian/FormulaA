<?php

namespace App\Http\Controllers;

use App\Models\Temporada;
use App\Models\Evento;
use App\Models\Resultado;
use App\Models\InscripcionPiloto;
use App\Models\Escuderia;
use Illuminate\Http\Request;

class ClasificacionController extends Controller
{
    // ── Mundial de pilotos ──────────────────────────────────────────
    public function pilotos(Request $request)
    {
        $temporadaFA = Temporada::where('categoria', 'formula_a')
                                ->where('activa', true)
                                ->first();

        if (!$temporadaFA) {
            return view('clasificaciones.pilotos', [
                'clasificacion' => collect(),
                'eventos'       => collect(),
                'eventoFiltro'  => null,
                'temporadaFA'   => null,
            ]);
        }

        // Todos los eventos completados de FA para el selector
        $eventos = Evento::where('id_temporada', $temporadaFA->id)
                         ->where('completado', true)
                         ->orderBy('ronda', 'asc')
                         ->get();

        // Filtro opcional por evento (hasta ese evento inclusive)
        $eventoFiltro = null;
        $eventoIds    = $eventos->pluck('id');

        if ($request->filled('hasta_evento')) {
            $eventoFiltro = Evento::find($request->hasta_evento);
            if ($eventoFiltro) {
                $eventoIds = $eventos
                    ->where('ronda', '<=', $eventoFiltro->ronda)
                    ->pluck('id');
            }
        }

        // Calcular puntos por piloto
        $inscripciones = InscripcionPiloto::with(['piloto.usuario', 'escuderia', 'resultados' => function ($q) use ($eventoIds) {
                            $q->whereIn('id_evento', $eventoIds);
                        }])
                        ->where('id_temporada', $temporadaFA->id)
                        ->get();

        $clasificacion = $inscripciones->map(function ($insc) use ($eventoIds) {
            $resultados = $insc->resultados;

            $puntos = $resultados->reduce(fn($carry, $r) => $carry + $r->pts_carrera + $r->pts_pole + $r->pts_vuelta_rap, 0);
            $victorias = $resultados->where('posicion', 1)->count();

            // Conteo de posiciones para desempate (P1..P20)
            $posiciones = [];
            for ($p = 1; $p <= 20; $p++) {
                $posiciones[$p] = $resultados->where('posicion', $p)->count();
            }

            // Última posición en la carrera más reciente
            $ultimaPos = $resultados->sortByDesc('id_evento')->first()?->posicion ?? 999;

            return [
                'inscripcion'  => $insc,
                'piloto'       => $insc->piloto,
                'escuderia'    => $insc->escuderia,
                'puntos'       => $puntos,
                'victorias'    => $victorias,
                'posiciones'   => $posiciones,
                'ultima_pos'   => $ultimaPos,
                'carreras'     => $resultados->count(),
            ];
        });

        // Ordenar: puntos desc → victorias desc → P2 desc → P3 desc → ... → última carrera asc
        $clasificacion = $clasificacion->sort(function ($a, $b) {
            if ($a['puntos'] !== $b['puntos']) {
                return $b['puntos'] <=> $a['puntos'];
            }
            for ($p = 1; $p <= 20; $p++) {
                if ($a['posiciones'][$p] !== $b['posiciones'][$p]) {
                    return $b['posiciones'][$p] <=> $a['posiciones'][$p];
                }
            }
            return $a['ultima_pos'] <=> $b['ultima_pos'];
        })->values();

        return view('clasificaciones.pilotos', compact(
            'clasificacion',
            'eventos',
            'eventoFiltro',
            'temporadaFA'
        ));
    }

    // ── Mundial de constructores ────────────────────────────────────
    public function constructores(Request $request)
    {
        $temporadaFA = Temporada::where('categoria', 'formula_a')
                                ->where('activa', true)
                                ->first();

        if (!$temporadaFA) {
            return view('clasificaciones.constructores', [
                'clasificacion' => collect(),
                'eventos'       => collect(),
                'eventoFiltro'  => null,
                'temporadaFA'   => null,
            ]);
        }

        $eventos = Evento::where('id_temporada', $temporadaFA->id)
                         ->where('completado', true)
                         ->orderBy('ronda', 'asc')
                         ->get();

        $eventoFiltro = null;
        $eventoIds    = $eventos->pluck('id');

        if ($request->filled('hasta_evento')) {
            $eventoFiltro = Evento::find($request->hasta_evento);
            if ($eventoFiltro) {
                $eventoIds = $eventos
                    ->where('ronda', '<=', $eventoFiltro->ronda)
                    ->pluck('id');
            }
        }

        // Agrupar puntos por escudería
        $escuderias = Escuderia::with(['inscripciones' => function ($q) use ($temporadaFA, $eventoIds) {
                    $q->where('id_temporada', $temporadaFA->id)
                      ->with(['resultados' => function ($r) use ($eventoIds) {
                          $r->whereIn('id_evento', $eventoIds);
                      }, 'piloto']);
                }])
                        ->whereHas('inscripciones', function ($q) use ($temporadaFA) {
                            $q->where('id_temporada', $temporadaFA->id);
                        })
                        ->get();

        $clasificacion = $escuderias->map(function ($escuderia) {
            $todosResultados = $escuderia->inscripciones->flatMap->resultados;

            $puntos    = $todosResultados->sum(fn($r) => $r->pts_carrera + $r->pts_pole + $r->pts_vuelta_rap);
            $victorias = $todosResultados->where('posicion', 1)->count();

            $posiciones = [];
            for ($p = 1; $p <= 20; $p++) {
                $posiciones[$p] = $todosResultados->where('posicion', $p)->count();
            }

            $pilotos = $escuderia->inscripciones->map(fn($i) => $i->piloto->gamertag ?? '—');

            return [
                'escuderia'  => $escuderia,
                'puntos'     => $puntos,
                'victorias'  => $victorias,
                'posiciones' => $posiciones,
                'pilotos'    => $pilotos,
                'carreras'   => $todosResultados->count(),
            ];
        });

        $clasificacion = $clasificacion->sort(function ($a, $b) {
            if ($a['puntos'] !== $b['puntos']) {
                return $b['puntos'] <=> $a['puntos'];
            }
            for ($p = 1; $p <= 20; $p++) {
                if ($a['posiciones'][$p] !== $b['posiciones'][$p]) {
                    return $b['posiciones'][$p] <=> $a['posiciones'][$p];
                }
            }
            return 0;
        })->values();

        return view('clasificaciones.constructores', compact(
            'clasificacion',
            'eventos',
            'eventoFiltro',
            'temporadaFA'
        ));
    }
}