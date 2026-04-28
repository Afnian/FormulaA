<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Temporada;
use Illuminate\Http\Request;

class TemporadaController extends Controller
{
    public function index()
    {
        $temporadas = Temporada::withCount(['eventos', 'inscripciones'])
                               ->orderBy('anio', 'desc')
                               ->get();

        return view('dashboard.temporadas.index', compact('temporadas'));
    }

    public function create()
    {
        return view('dashboard.temporadas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'categoria' => 'required|in:formula_a,formula_b',
            'anio'      => 'required|integer|min:2020|max:2100',
            'activa'    => 'nullable|boolean',
        ]);

        // Si se marca como activa, desactivar las demás de la misma categoría
        if ($request->boolean('activa')) {
            Temporada::where('categoria', $request->categoria)
                     ->update(['activa' => false]);
        }

        Temporada::create([
            'nombre'    => $request->nombre,
            'categoria' => $request->categoria,
            'anio'      => $request->anio,
            'activa'    => $request->boolean('activa'),
        ]);

        return redirect()->route('dashboard.temporadas.index')
                         ->with('success', 'Temporada creada correctamente.');
    }

    public function edit($id)
    {
        $temporada = Temporada::findOrFail($id);
        return view('dashboard.temporadas.edit', compact('temporada'));
    }

    public function update(Request $request, $id)
    {
        $temporada = Temporada::findOrFail($id);

        $request->validate([
            'nombre'    => 'required|string|max:255',
            'categoria' => 'required|in:formula_a,formula_b',
            'anio'      => 'required|integer|min:2020|max:2100',
            'activa'    => 'nullable|boolean',
        ]);

        if ($request->boolean('activa')) {
            Temporada::where('categoria', $request->categoria)
                     ->where('id', '!=', $temporada->id)
                     ->update(['activa' => false]);
        }

        $temporada->update([
            'nombre'    => $request->nombre,
            'categoria' => $request->categoria,
            'anio'      => $request->anio,
            'activa'    => $request->boolean('activa'),
        ]);

        return redirect()->route('dashboard.temporadas.index')
                         ->with('success', 'Temporada actualizada correctamente.');
    }

    public function destroy($id)
    {
        $temporada = Temporada::findOrFail($id);
        $temporada->delete();

        return redirect()->route('dashboard.temporadas.index')
                         ->with('success', 'Temporada eliminada correctamente.');
    }
}