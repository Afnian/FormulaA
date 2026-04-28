<?php

namespace App\Http\Controllers;

use App\Models\SolicitudAcceso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InscripcionController extends Controller
{
    public function create()
    {
        $usuario   = Auth::user();
        $solicitud = $usuario->solicitudAcceso;

        // Si ya tiene solicitud pendiente o aceptada, redirigir al perfil
        if ($solicitud && in_array($solicitud->estado, ['pendiente', 'aceptada'])) {
            return redirect()->route('perfil.show')
                             ->with('info', 'Ya tienes una solicitud de inscripción activa.');
        }

        return view('perfil.solicitud', compact('solicitud'));
    }

    public function store(Request $request)
    {
        $usuario   = Auth::user();
        $solicitud = $usuario->solicitudAcceso;

        // Doble validación: no permitir duplicados activos
        if ($solicitud && in_array($solicitud->estado, ['pendiente', 'aceptada'])) {
            return redirect()->route('perfil.show')
                             ->with('info', 'Ya tienes una solicitud de inscripción activa.');
        }

        // Si tenía una rechazada, la eliminamos para crear una nueva
        if ($solicitud && $solicitud->estado === 'rechazada') {
            $solicitud->delete();
        }

        SolicitudAcceso::create([
            'id_usuario'      => $usuario->id,
            'fecha_solicitud' => now(),
            'estado'          => 'pendiente',
        ]);

        return redirect()->route('perfil.show')
                         ->with('success', 'Tu solicitud de inscripción ha sido enviada correctamente. El administrador la revisará pronto.');
    }
}