<?php

namespace App\Http\Controllers;

use App\Models\Escuderia;
use App\Models\Temporada;
use App\Models\InscripcionPiloto;

class EscuderiaController extends Controller
{
    public function index()
    {
        $escuderias = Escuderia::withCount([
                            'inscripciones as pilotos_activos' => function ($query) {
                                $temporadaFA = Temporada::where('categoria', 'formula_a')
                                                        ->where('activa', true)
                                                        ->first();
                                if ($temporadaFA) {
                                    $query->where('id_temporada', $temporadaFA->id);
                                }
                            }
                        ])
                        ->orderBy('nombre')
                        ->get();

        return view('escuderias.index', compact('escuderias'));
    }

    public function show($id)
    {
        $escuderia = Escuderia::findOrFail($id);

        $temporadaFA = Temporada::where('categoria', 'formula_a')
                                ->where('activa', true)
                                ->first();

        $inscripciones = collect();

        if ($temporadaFA) {
            $inscripciones = InscripcionPiloto::with(['piloto.usuario'])
                                ->where('id_escuderia', $escuderia->id)
                                ->where('id_temporada', $temporadaFA->id)
                                ->orderBy('tipo')
                                ->get();
        }

        return view('escuderias.show', compact('escuderia', 'inscripciones', 'temporadaFA'));
    }
}