<?php

namespace App\Http\Controllers;

use App\Models\Temporada;
use App\Models\Evento;
use App\Models\Resultado;
use App\Models\InscripcionPiloto;
use App\Models\Escuderia;

class FormulaBController extends Controller
{
    public function index()
    {
        $temporadaFB = Temporada::where('categoria', 'formula_b')
                                ->where('activa', true)
                                ->first();

        if (!$temporadaFB) {
            return view('formula-b.index', [
                'temporadaFB'       => null,
                'clasificacionPilotos'      => collect(),
                'clasificacionConstructores'=> collect(),
                'ultimoEvento'      => null,
                'resultadosUltimo'  => collect(),
                'proximoEvento'     => null,
                'noticias'          => collect(),
            ]);
        }

        // ── Próximo evento FB ──────────────────────────────────────
        $proximoEvento = Evento::with('circuito')
                               ->where('id_temporada', $temporadaFB->id)
                               ->where('completado', false)
                               ->orderBy('fecha', 'asc')
                               ->first();

        // ── Último evento completado FB ────────────────────────────
        $ultimoEvento = Evento::with('circuito')
                              ->where('id_temporada', $temporadaFB->id)
                              ->where('completado', true)
                              ->orderBy('fecha', 'desc')
                              ->first();

        $resultadosUltimo = collect();
        if ($ultimoEvento) {
            $resultadosUltimo = Resultado::with([
                                    'inscripcion.piloto',
                                    'inscripcion.escuderia'
                                ])
                                ->where('id_evento', $ultimoEvento->id)
                                ->whereNotNull('posicion')
                                ->orderBy('posicion', 'asc')
                                ->take(10)
                                ->get()
                                ->map(function ($r) {
                                    $r->puntos_totales = $r->pts_carrera + $r->pts_pole + $r->pts_vuelta_rap;
                                    return $r;
                                });
        }

        // ── Eventos completados para puntos ───────────────────────
        $eventosCompletados = Evento::where('id_temporada', $temporadaFB->id)
                                    ->where('completado', true)
                                    ->pluck('id');

        // ── Mundial de pilotos FB ──────────────────────────────────
        $inscripciones = InscripcionPiloto::with([
                            'piloto',
                            'escuderia',
                            'resultados' => fn($q) => $q->whereIn('id_evento', $eventosCompletados)
                         ])
                         ->where('id_temporada', $temporadaFB->id)
                         ->get();

        $clasificacionPilotos = $inscripciones->map(function ($insc) {
            $resultados = $insc->resultados;
            $puntos     = $resultados->reduce(
                fn($carry, $r) => $carry + $r->pts_carrera + $r->pts_pole + $r->pts_vuelta_rap, 0
            );
            $victorias  = $resultados->where('posicion', 1)->count();
            $posiciones = [];
            for ($p = 1; $p <= 20; $p++) {
                $posiciones[$p] = $resultados->where('posicion', $p)->count();
            }
            return [
                'piloto'    => $insc->piloto,
                'escuderia' => $insc->escuderia,
                'puntos'    => $puntos,
                'victorias' => $victorias,
                'posiciones'=> $posiciones,
            ];
        })->sort(function ($a, $b) {
            if ($a['puntos'] !== $b['puntos']) return $b['puntos'] <=> $a['puntos'];
            for ($p = 1; $p <= 20; $p++) {
                if ($a['posiciones'][$p] !== $b['posiciones'][$p]) {
                    return $b['posiciones'][$p] <=> $a['posiciones'][$p];
                }
            }
            return 0;
        })->values();

        // ── Mundial de constructores FB ────────────────────────────
        $escuderias = Escuderia::with(['inscripciones' => function ($q) use ($temporadaFB, $eventosCompletados) {
                            $q->where('id_temporada', $temporadaFB->id)
                              ->with(['resultados' => function ($r) use ($eventosCompletados) {
                                  $r->whereIn('id_evento', $eventosCompletados);
                              }, 'piloto']);
                        }])
                        ->whereHas('inscripciones', function ($q) use ($temporadaFB) {
                            $q->where('id_temporada', $temporadaFB->id);
                        })
                        ->get();

        $clasificacionConstructores = $escuderias->map(function ($escuderia) {
            $todosResultados = $escuderia->inscripciones->flatMap->resultados;
            $puntos  = $todosResultados->reduce(
                fn($carry, $r) => $carry + $r->pts_carrera + $r->pts_pole + $r->pts_vuelta_rap, 0
            );
            $victorias = $todosResultados->where('posicion', 1)->count();
            $posiciones = [];
            for ($p = 1; $p <= 20; $p++) {
                $posiciones[$p] = $todosResultados->where('posicion', $p)->count();
            }
            $pilotos = $escuderia->inscripciones->map(fn($i) => $i->piloto->gamertag ?? '—');
            return [
                'escuderia' => $escuderia,
                'puntos'    => $puntos,
                'victorias' => $victorias,
                'posiciones'=> $posiciones,
                'pilotos'   => $pilotos,
            ];
        })->sort(function ($a, $b) {
            if ($a['puntos'] !== $b['puntos']) return $b['puntos'] <=> $a['puntos'];
            for ($p = 1; $p <= 20; $p++) {
                if ($a['posiciones'][$p] !== $b['posiciones'][$p]) {
                    return $b['posiciones'][$p] <=> $a['posiciones'][$p];
                }
            }
            return 0;
        })->values();

        // ── Últimas noticias FB ────────────────────────────────────
        // Noticias vinculadas a eventos de FB
        $eventosIdsFB = Evento::where('id_temporada', $temporadaFB->id)->pluck('id');
        $noticias = \App\Models\Noticias::with(['autor', 'evento'])
                        ->publicadas()
                        ->where(function ($q) use ($eventosIdsFB) {
                            $q->whereIn('id_evento', $eventosIdsFB)
                              ->orWhereNull('id_evento');
                        })
                        ->orderBy('publicado_en', 'desc')
                        ->take(3)
                        ->get();

        return view('formula-b.index', compact(
            'temporadaFB',
            'clasificacionPilotos',
            'clasificacionConstructores',
            'ultimoEvento',
            'resultadosUltimo',
            'proximoEvento',
            'noticias'
        ));
    }
}