<?php

namespace App\Http\Controllers;

use App\Models\Noticias;
use App\Models\Evento;
use App\Models\Resultado;
use App\Models\Temporada;

class HomeController extends Controller
{
    public function index()
    {
        // Temporada activa de Fórmula A
        $temporadaFA = Temporada::where('categoria', 'formula_a')
                                ->where('activa', true)
                                ->first();

        // 3 noticias publicadas más recientes
        $noticias = Noticias::with(['autor', 'evento.circuito'])
                           ->publicadas()
                           ->orderBy('publicado_en', 'desc')
                           ->take(3)
                           ->get();

        $noticiaDestacada  = $noticias->first();
        $noticiasSecundarias = $noticias->skip(1)->take(2)->values();

        // Próximo evento no completado de FA
        $proximoEvento = null;
        if ($temporadaFA) {
            $proximoEvento = Evento::with('circuito')
                                   ->where('id_temporada', $temporadaFA->id)
                                   ->where('completado', false)
                                   ->orderBy('fecha', 'asc')
                                   ->first();
        }

        // Último evento completado de FA con podio (top 3)
        $ultimoEvento = null;
        $podio        = collect();
        if ($temporadaFA) {
            $ultimoEvento = Evento::with('circuito')
                                  ->where('id_temporada', $temporadaFA->id)
                                  ->where('completado', true)
                                  ->orderBy('fecha', 'desc')
                                  ->first();

            if ($ultimoEvento) {
                $podio = Resultado::with(['inscripcion.piloto', 'inscripcion.escuderia'])
                                  ->where('id_evento', $ultimoEvento->id)
                                  ->whereNotNull('posicion')
                                  ->orderBy('posicion', 'asc')
                                  ->take(3)
                                  ->get();
            }
        }

        return view('home', compact(
            'noticiaDestacada',
            'noticiasSecundarias',
            'proximoEvento',
            'ultimoEvento',
            'podio'
        ));
    }
}