<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Circuito;
use Illuminate\Http\Request;

class CircuitoController extends Controller
{
    public function index()
    {
        $circuitos = Circuito::withCount('eventos')->orderBy('nombre')->get();
        return view('dashboard.circuitos.index', compact('circuitos'));
    }

    public function create()
    {
        return view('dashboard.circuitos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'pais'        => 'required|string|max:100',
            'num_vueltas' => 'required|integer|min:1|max:999',
            'imagen_url'  => 'nullable|url|max:500',
        ]);

        Circuito::create($request->only('nombre', 'pais', 'num_vueltas', 'imagen_url'));

        return redirect()->route('dashboard.circuitos.index')
                         ->with('success', 'Circuito creado correctamente.');
    }

    public function edit($id)
    {
        $circuito = Circuito::findOrFail($id);
        return view('dashboard.circuitos.edit', compact('circuito'));
    }

    public function update(Request $request, $id)
    {
        $circuito = Circuito::findOrFail($id);

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'pais'        => 'required|string|max:100',
            'num_vueltas' => 'required|integer|min:1|max:999',
            'imagen_url'  => 'nullable|url|max:500',
        ]);

        $circuito->update($request->only('nombre', 'pais', 'num_vueltas', 'imagen_url'));

        return redirect()->route('dashboard.circuitos.index')
                         ->with('success', 'Circuito actualizado correctamente.');
    }

    public function destroy($id)
    {
        $circuito = Circuito::findOrFail($id);
        $circuito->delete();

        return redirect()->route('dashboard.circuitos.index')
                         ->with('success', 'Circuito eliminado correctamente.');
    }
}