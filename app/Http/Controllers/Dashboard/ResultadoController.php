<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Resultado;
use App\Models\InscripcionPiloto;
use App\Models\SistemaPuntos;
use Illuminate\Http\Request;

class ResultadoController extends Controller
{
    public function edit($id)
    {
        $evento = Evento::with(['temporada', 'circuito'])->findOrFail($id);

        if (!$evento->completado) {
            return redirect()->route('dashboard.eventos.index')
                             ->with('error', 'El evento debe estar marcado como completado para cargar resultados.');
        }

        // Todos los pilotos inscritos en la temporada del evento
        $inscripciones = InscripcionPiloto::with(['piloto', 'escuderia'])
                            ->where('id_temporada', $evento->id_temporada)
                            ->orderBy('id_escuderia')
                            ->get();

        // Resultados ya guardados (si existen)
        $resultadosExistentes = Resultado::where('id_evento', $evento->id)
                                         ->get()
                                         ->keyBy('id_inscripcion');

        return view('dashboard.resultados.edit', compact(
            'evento',
            'inscripciones',
            'resultadosExistentes'
        ));
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        if (!$evento->completado) {
            return redirect()->route('dashboard.eventos.index')
                             ->with('error', 'El evento no está marcado como completado.');
        }

        $pilotos = $request->input('pilotos', []);

        // Validar que no haya posiciones duplicadas (ignorando DNF y nulos)
        $posiciones = collect($pilotos)
            ->filter(fn($p) => !isset($p['dnf']) && !empty($p['posicion']))
            ->pluck('posicion')
            ->map(fn($p) => (int) $p);

        if ($posiciones->count() !== $posiciones->unique()->count()) {
            return redirect()->back()
                             ->with('error', 'Hay posiciones duplicadas. Cada piloto debe tener una posición única.')
                             ->withInput();
        }

        // Sistema de puntos de la temporada
        $sistemaPuntos = SistemaPuntos::where('id_temporada', $evento->id_temporada)
                                       ->pluck('puntos', 'posicion');

        // Determinar quién tiene vuelta rápida (solo 1 por carrera)
        $vueltaRapidaInscripcionId = null;
        foreach ($pilotos as $inscripcionId => $datos) {
            if (isset($datos['vuelta_rapida'])) {
                $vueltaRapidaInscripcionId = $inscripcionId;
                break;
            }
        }

        // Eliminar resultados anteriores del evento
        Resultado::where('id_evento', $evento->id)->delete();

        // Guardar nuevos resultados
        foreach ($pilotos as $inscripcionId => $datos) {
            $esDnf      = isset($datos['dnf']);
            $esPole     = isset($datos['pole']);
            $esVueltaRap = ($inscripcionId == $vueltaRapidaInscripcionId);
            $posicion   = $esDnf ? null : (int) ($datos['posicion'] ?? 0);

            // Calcular puntos de carrera
            $ptsCarrera = 0;
            if (!$esDnf && $posicion > 0) {
                $ptsCarrera = $sistemaPuntos->get($posicion, 0);
            }

            // Puntos pole
            $ptsPole = $esPole ? 2 : 0;

            // Puntos vuelta rápida (solo si top 10 y no DNF)
            $ptsVueltaRap = 0;
            if ($esVueltaRap && !$esDnf && $posicion > 0 && $posicion <= 10) {
                $ptsVueltaRap = 1;
            }

            Resultado::create([
                'id_evento'      => $evento->id,
                'id_inscripcion' => $inscripcionId,
                'posicion'       => $posicion ?: null,
                'diferencia'     => $esDnf ? 'DNF' : ($datos['diferencia'] ?? null),
                'pts_carrera'    => $ptsCarrera,
                'pts_pole'       => $ptsPole,
                'pts_vuelta_rap' => $ptsVueltaRap,
                'dnf'            => $esDnf,
            ]);
        }

        return redirect()->route('dashboard.eventos.resultados', $evento->id)
                         ->with('success', 'Resultados guardados correctamente. Los puntos han sido calculados automáticamente.');
    }
}