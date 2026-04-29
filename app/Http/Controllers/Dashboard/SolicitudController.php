<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SolicitudAcceso;
use App\Models\Piloto;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function index()
    {
        $solicitudes = SolicitudAcceso::with(['usuario'])
                                      ->orderByRaw("FIELD(estado, 'pendiente', 'aceptada', 'rechazada')")
                                      ->orderByDesc('fecha_solicitud')
                                      ->get();

        $pendientes = $solicitudes->where('estado', 'pendiente')->count();

        return view('dashboard.solicitudes.index', compact('solicitudes', 'pendientes'));
    }

    public function update(Request $request, $id)
    {
        $solicitud = SolicitudAcceso::with('usuario')->findOrFail($id);

        $request->validate([
            'accion' => 'required|in:aceptar,rechazar',
        ]);

        if ($request->accion === 'aceptar') {
            $usuario = $solicitud->usuario;

            // Cambiar rol del usuario a piloto
            $usuario->update(['rol' => 'piloto']);

            // Crear registro en pilotos si no existe
            if (!$usuario->piloto) {
                Piloto::create([
                    'id_usuario'   => $usuario->id,
                    'gamertag'     => $usuario->nombre,
                    'nacionalidad' => null,
                ]);
            }

            $solicitud->update(['estado' => 'aceptada']);

            return redirect()->route('dashboard.solicitudes.index')
                             ->with('success', "Solicitud de {$usuario->nombre} aceptada. El usuario ahora tiene rol de piloto.");
        }

        // Rechazar
        $solicitud->update(['estado' => 'rechazada']);

        return redirect()->route('dashboard.solicitudes.index')
                         ->with('success', "Solicitud de {$solicitud->usuario->nombre} rechazada.");
    }
}