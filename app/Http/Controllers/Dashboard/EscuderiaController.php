<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Escuderia;
use App\Models\Piloto;
use App\Models\Temporada;
use App\Models\InscripcionPiloto;
use Illuminate\Http\Request;

class EscuderiaController extends Controller
{
    public function index()
    {
        $escuderias = Escuderia::withCount('inscripciones')->orderBy('nombre')->get();
        return view('dashboard.escuderias.index', compact('escuderias'));
    }

    public function create()
    {
        return view('dashboard.escuderias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255|unique:escuderias,nombre',
            'descripcion' => 'nullable|string',
            'logo_url'    => 'nullable|url|max:500',
        ]);

        Escuderia::create($request->only('nombre', 'descripcion', 'logo_url'));

        return redirect()->route('dashboard.escuderias.index')
                         ->with('success', 'Escudería creada correctamente.');
    }

    public function show($id)
    {
        $escuderia  = Escuderia::findOrFail($id);
        $temporadas = Temporada::orderBy('anio', 'desc')->get();

        $inscripciones = InscripcionPiloto::with(['piloto.usuario', 'temporada'])
                            ->where('id_escuderia', $escuderia->id)
                            ->orderByDesc('id_temporada')
                            ->get();

        // Pilotos disponibles (sin inscripción activa en cada temporada)
        $pilotos = Piloto::with('usuario')->get();

        return view('dashboard.escuderias.show', compact(
            'escuderia',
            'temporadas',
            'inscripciones',
            'pilotos'
        ));
    }

    public function edit($id)
    {
        $escuderia = Escuderia::findOrFail($id);
        return view('dashboard.escuderias.edit', compact('escuderia'));
    }

    public function update(Request $request, $id)
    {
        $escuderia = Escuderia::findOrFail($id);

        $request->validate([
            'nombre'      => 'required|string|max:255|unique:escuderias,nombre,' . $escuderia->id,
            'descripcion' => 'nullable|string',
            'logo_url'    => 'nullable|url|max:500',
        ]);

        $escuderia->update($request->only('nombre', 'descripcion', 'logo_url'));

        return redirect()->route('dashboard.escuderias.index')
                         ->with('success', 'Escudería actualizada correctamente.');
    }

    public function destroy($id)
    {
        $escuderia = Escuderia::findOrFail($id);
        $escuderia->delete();

        return redirect()->route('dashboard.escuderias.index')
                         ->with('success', 'Escudería eliminada correctamente.');
    }

    // ── Asignación de pilotos ──────────────────────────────────────

    public function asignarPiloto(Request $request, $id)
    {
        $escuderia = Escuderia::findOrFail($id);

        $request->validate([
            'id_piloto'    => 'required|exists:pilotos,id',
            'id_temporada' => 'required|exists:temporadas,id',
            'tipo'         => 'required|in:oficial,reserva,academia',
        ]);

        // Validar: un piloto solo puede estar en una escudería por temporada
        $yaInscrito = InscripcionPiloto::where('id_piloto', $request->id_piloto)
                                        ->where('id_temporada', $request->id_temporada)
                                        ->exists();

        if ($yaInscrito) {
            return redirect()->route('dashboard.escuderias.show', $escuderia->id)
                             ->with('error', 'Este piloto ya está inscrito en una escudería para esa temporada.');
        }

        InscripcionPiloto::create([
            'id_piloto'    => $request->id_piloto,
            'id_escuderia' => $escuderia->id,
            'id_temporada' => $request->id_temporada,
            'tipo'         => $request->tipo,
        ]);

        return redirect()->route('dashboard.escuderias.show', $escuderia->id)
                         ->with('success', 'Piloto asignado correctamente.');
    }

    public function quitarPiloto($id, $inscripcionId)
    {
        $escuderia   = Escuderia::findOrFail($id);
        $inscripcion = InscripcionPiloto::where('id_escuderia', $escuderia->id)
                                         ->findOrFail($inscripcionId);
        $inscripcion->delete();

        return redirect()->route('dashboard.escuderias.show', $escuderia->id)
                         ->with('success', 'Piloto eliminado de la escudería.');
    }
}