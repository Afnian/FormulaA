<?php

namespace App\Http\Controllers;

use App\Models\InscripcionPiloto;
use App\Models\SolicitudAcceso;
use App\Models\Noticias;
use App\Models\Evento;
use App\Models\Temporada;

class DashboardController extends Controller
{
    public function index()
    {
        $temporadaFA = Temporada::where('categoria', 'formula_a')
                                ->where('activa', true)
                                ->first();

        // Nº pilotos inscritos en temporada activa
        $pilotosInscritos = $temporadaFA
            ? InscripcionPiloto::where('id_temporada', $temporadaFA->id)->count()
            : 0;

        // Nº solicitudes pendientes
        $solicitudesPendientes = SolicitudAcceso::where('estado', 'pendiente')->count();

        // Nº noticias en borrador
        $noticiasBorrador = Noticias::where('estado', 'borrador')->count();

        // Último evento completado
        $ultimoEvento = null;
        $proximoEvento = null;

        if ($temporadaFA) {
            $ultimoEvento = Evento::with('circuito')
                                  ->where('id_temporada', $temporadaFA->id)
                                  ->where('completado', true)
                                  ->orderBy('fecha', 'desc')
                                  ->first();

            $proximoEvento = Evento::with('circuito')
                                   ->where('id_temporada', $temporadaFA->id)
                                   ->where('completado', false)
                                   ->orderBy('fecha', 'asc')
                                   ->first();
        }

        return view('dashboard.index', compact(
            'pilotosInscritos',
            'solicitudesPendientes',
            'noticiasBorrador',
            'ultimoEvento',
            'proximoEvento',
            'temporadaFA'
        ));
    }
}