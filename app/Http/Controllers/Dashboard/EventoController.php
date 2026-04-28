<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Temporada;
use App\Models\Circuito;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::with(['temporada', 'circuito'])
                         ->orderByDesc('id_temporada')
                         ->orderBy('ronda')
                         ->get();

        return view('dashboard.eventos.index', compact('eventos'));
    }

    public function create()
    {
        $temporadas = Temporada::orderBy('anio', 'desc')->get();
        $circuitos  = Circuito::orderBy('nombre')->get();

        return view('dashboard.eventos.create', compact('temporadas', 'circuitos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_temporada' => 'required|exists:temporadas,id',
            'id_circuito'  => 'required|exists:circuitos,id',
            'nombre'       => 'required|string|max:255',
            'ronda'        => 'required|integer|min:1|max:99',
            'fecha'        => 'required|date',
        ]);

        Evento::create([
            'id_temporada' => $request->id_temporada,
            'id_circuito'  => $request->id_circuito,
            'nombre'       => $request->nombre,
            'ronda'        => $request->ronda,
            'fecha'        => $request->fecha,
            'completado'   => false,
        ]);

        return redirect()->route('dashboard.eventos.index')
                         ->with('success', 'Evento creado correctamente.');
    }

    public function edit($id)
    {
        $evento     = Evento::findOrFail($id);
        $temporadas = Temporada::orderBy('anio', 'desc')->get();
        $circuitos  = Circuito::orderBy('nombre')->get();

        return view('dashboard.eventos.edit', compact('evento', 'temporadas', 'circuitos'));
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $request->validate([
            'id_temporada' => 'required|exists:temporadas,id',
            'id_circuito'  => 'required|exists:circuitos,id',
            'nombre'       => 'required|string|max:255',
            'ronda'        => 'required|integer|min:1|max:99',
            'fecha'        => 'required|date',
            'completado'   => 'nullable|boolean',
        ]);

        $evento->update([
            'id_temporada' => $request->id_temporada,
            'id_circuito'  => $request->id_circuito,
            'nombre'       => $request->nombre,
            'ronda'        => $request->ronda,
            'fecha'        => $request->fecha,
            'completado'   => $request->boolean('completado'),
        ]);

        return redirect()->route('dashboard.eventos.index')
                         ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->delete();

        return redirect()->route('dashboard.eventos.index')
                         ->with('success', 'Evento eliminado correctamente.');
    }

    public function completar($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->update(['completado' => true]);

        return redirect()->route('dashboard.eventos.resultados', $evento->id)
                         ->with('success', 'Evento marcado como completado. Ya puedes cargar los resultados.');
    }
}