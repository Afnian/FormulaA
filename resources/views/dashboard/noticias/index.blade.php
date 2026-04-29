@extends('layouts.dashboard')

@section('title', 'Noticias — Panel')
@section('page-title', 'Noticias')
@section('page-subtitle', 'Gestión de contenido')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex gap-2">
            <span class="badge bg-success">
                {{ $noticias->where('estado', 'publicada')->count() }} publicadas
            </span>
            <span class="badge bg-secondary">
                {{ $noticias->where('estado', 'borrador')->count() }} borradores
            </span>
        </div>
        <a href="{{ route('dashboard.noticias.create') }}" class="btn btn-danger">
            <i class="bi bi-plus-lg me-1"></i>Nueva noticia
        </a>
    </div>

    <div class="table-panel">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead style="background:#0d0d14; border-bottom:2px solid var(--fa-rojo);">
                    <tr>
                        <th class="ps-3">Título</th>
                        <th>Autor</th>
                        <th>Evento</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($noticias as $noticia)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold">{{ Str::limit($noticia->titulo, 55) }}</div>
                        </td>
                        <td class="text-secondary small">
                            {{ $noticia->autor->nombre }}
                        </td>
                        <td class="text-secondary small">
                            {{ $noticia->evento?->nombre ?? '—' }}
                        </td>
                        <td class="text-center">
                            @if($noticia->estado === 'publicada')
                                <span class="badge bg-success">Publicada</span>
                            @else
                                <span class="badge bg-secondary">Borrador</span>
                            @endif
                        </td>
                        <td class="text-center text-secondary small">
                            @if($noticia->publicado_en)
                                {{ $noticia->publicado_en->format('d M Y') }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- Publicar (solo borradores) --}}
                                @if($noticia->estado === 'borrador')
                                    <form method="POST"
                                          action="{{ route('dashboard.noticias.publicar', $noticia->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-success"
                                                title="Publicar">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Ver en público --}}
                                @if($noticia->estado === 'publicada')
                                    <a href="{{ route('noticias.show', $noticia->id) }}"
                                       class="btn btn-sm btn-outline-info"
                                       target="_blank"
                                       title="Ver noticia">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endif

                                {{-- Editar --}}
                                <a href="{{ route('dashboard.noticias.edit', $noticia->id) }}"
                                   class="btn btn-sm btn-outline-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- Eliminar --}}
                                <form method="POST"
                                      action="{{ route('dashboard.noticias.destroy', $noticia->id) }}"
                                      onsubmit="return confirm('¿Eliminar la noticia?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">
                            No hay noticias registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection