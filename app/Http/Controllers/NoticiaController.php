<?php

namespace App\Http\Controllers;

use App\Models\Noticias;

class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticias::with(['autor', 'evento.circuito'])
                           ->publicadas()
                           ->orderBy('publicado_en', 'desc')
                           ->paginate(10);

        return view('noticias.index', compact('noticias'));
    }

    public function show($id)
    {
        $noticia = Noticias::with(['autor', 'evento.circuito'])
                          ->publicadas()
                          ->findOrFail($id);

        // 3 noticias relacionadas (misma categoría, excluyendo la actual)
        $relacionadas = Noticias::with(['autor'])
                               ->publicadas()
                               ->where('id', '!=', $noticia->id)
                               ->orderBy('publicado_en', 'desc')
                               ->take(3)
                               ->get();

        return view('noticias.show', compact('noticia', 'relacionadas'));
    }
}