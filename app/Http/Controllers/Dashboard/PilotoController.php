<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Piloto;
use App\Models\User;
use Illuminate\Http\Request;

class PilotoController extends Controller
{
    public function index()
    {
        $pilotos = Piloto::with(['usuario', 'inscripciones.escuderia', 'inscripciones.temporada'])
                         ->orderBy('gamertag')
                         ->get();

        return view('dashboard.pilotos.index', compact('pilotos'));
    }

    public function create()
    {
        // Usuarios sin piloto asignado todavía
        $usuariosSinPiloto = User::whereDoesntHave('piloto')
                                 ->orderBy('nombre')
                                 ->get();

        return view('dashboard.pilotos.create', compact('usuariosSinPiloto'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gamertag'     => 'required|string|max:100|unique:pilotos,gamertag',
            'nacionalidad' => 'nullable|string|max:100',
            'id_usuario'   => 'nullable|exists:users,id|unique:pilotos,id_usuario',
        ]);

        // Si se vincula a un usuario, cambiar su rol a piloto
        if ($request->id_usuario) {
            User::where('id', $request->id_usuario)
                ->update(['rol' => 'piloto']);
        }

        Piloto::create([
            'id_usuario'   => $request->id_usuario ?: null,
            'gamertag'     => $request->gamertag,
            'nacionalidad' => $request->nacionalidad,
        ]);

        return redirect()->route('dashboard.pilotos.index')
                         ->with('success', 'Piloto creado correctamente.');
    }

    public function edit($id)
    {
        $piloto = Piloto::with('usuario')->findOrFail($id);

        // Usuarios sin piloto + el usuario actual del piloto
        $usuariosSinPiloto = User::whereDoesntHave('piloto')
                                 ->orWhere('id', $piloto->id_usuario)
                                 ->orderBy('nombre')
                                 ->get();

        return view('dashboard.pilotos.edit', compact('piloto', 'usuariosSinPiloto'));
    }

    public function update(Request $request, $id)
    {
        $piloto = Piloto::findOrFail($id);

        $request->validate([
            'gamertag'     => 'required|string|max:100|unique:pilotos,gamertag,' . $piloto->id,
            'nacionalidad' => 'nullable|string|max:100',
            'id_usuario'   => 'nullable|exists:users,id|unique:pilotos,id_usuario,' . $piloto->id,
        ]);

        // Si cambia el usuario vinculado
        if ($piloto->id_usuario && $piloto->id_usuario != $request->id_usuario) {
            // Revertir rol del usuario anterior a espectador
            User::where('id', $piloto->id_usuario)
                ->update(['rol' => 'espectador']);
        }

        // Asignar rol piloto al nuevo usuario vinculado
        if ($request->id_usuario) {
            User::where('id', $request->id_usuario)
                ->update(['rol' => 'piloto']);
        }

        $piloto->update([
            'id_usuario'   => $request->id_usuario ?: null,
            'gamertag'     => $request->gamertag,
            'nacionalidad' => $request->nacionalidad,
        ]);

        return redirect()->route('dashboard.pilotos.index')
                         ->with('success', 'Piloto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $piloto = Piloto::findOrFail($id);

        // Revertir rol del usuario vinculado a espectador
        if ($piloto->id_usuario) {
            User::where('id', $piloto->id_usuario)
                ->update(['rol' => 'espectador']);
        }

        $piloto->delete();

        return redirect()->route('dashboard.pilotos.index')
                         ->with('success', 'Piloto eliminado correctamente.');
    }
}