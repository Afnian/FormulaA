<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Noticias;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticias::with(['autor', 'evento'])
                           ->orderByDesc('created_at')
                           ->get();

        return view('dashboard.noticias.index', compact('noticias'));
    }

    public function create()
    {
        $eventos = Evento::orderByDesc('fecha')->get();
        return view('dashboard.noticias.create', compact('eventos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'     => 'required|string|max:255',
            'contenido'  => 'required|string',
            'estado'     => 'required|in:borrador,publicada',
            'id_evento'  => 'nullable|exists:eventos,id',
        ]);

        Noticias::create([
            'titulo'       => $request->titulo,
            'contenido'    => $request->contenido,
            'estado'       => $request->estado,
            'id_evento'    => $request->id_evento ?: null,
            'id_autor'     => Auth::id(),
            'publicado_en' => $request->estado === 'publicada' ? now() : null,
        ]);

        return redirect()->route('dashboard.noticias.index')
                         ->with('success', 'Noticia creada correctamente.');
    }

    public function edit($id)
    {
        $noticia = Noticias::findOrFail($id);
        $eventos = Evento::orderByDesc('fecha')->get();

        if (Auth::user()->hasRole('editor') && $noticia->id_autor !== Auth::id()) {
            abort(403, 'Solo puedes editar tus propias noticias.');
        }

        return view('dashboard.noticias.edit', compact('noticia', 'eventos'));
    }

    public function update(Request $request, $id)
    {
        $noticia = Noticias::findOrFail($id);

        if (Auth::user()->hasRole('editor') && $noticia->id_autor !== Auth::id()) {
            abort(403, 'Solo puedes editar tus propias noticias.');
        }

        $request->validate([
            'titulo'    => 'required|string|max:255',
            'contenido' => 'required|string',
            'estado'    => 'required|in:borrador,publicada',
            'id_evento' => 'nullable|exists:eventos,id',
        ]);

        $publicadoEn = $noticia->publicado_en;
        if ($request->estado === 'publicada' && !$publicadoEn) {
            $publicadoEn = now();
        }

        $noticia->update([
            'titulo'       => $request->titulo,
            'contenido'    => $request->contenido,
            'estado'       => $request->estado,
            'id_evento'    => $request->id_evento ?: null,
            'publicado_en' => $publicadoEn,
        ]);

        return redirect()->route('dashboard.noticias.index')
                         ->with('success', 'Noticia actualizada correctamente.');
    }

    public function destroy($id)
    {
        $noticia = Noticias::findOrFail($id);

        if (Auth::user()->hasRole('editor') && $noticia->id_autor !== Auth::id()) {
            abort(403, 'Solo puedes eliminar tus propias noticias.');
        }

        $noticia->delete();

        return redirect()->route('dashboard.noticias.index')
                         ->with('success', 'Noticia eliminada correctamente.');
    }

    public function publicar($id)
    {
        $noticia = Noticias::findOrFail($id);

        if (Auth::user()->hasRole('editor') && $noticia->id_autor !== Auth::id()) {
            abort(403, 'Solo puedes publicar tus propias noticias.');
        }

        $noticia->update([
            'estado'       => 'publicada',
            'publicado_en' => $noticia->publicado_en ?? now(),
        ]);

        return redirect()->route('dashboard.noticias.index')
                         ->with('success', 'Noticia publicada correctamente.');
    }
}