@extends('layouts.dashboard')

@section('title', 'Escuderías — Panel')
@section('page-title', 'Escuderías')
@section('page-subtitle', 'Gestión de equipos')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-secondary mb-0">
            {{ $escuderias->count() }} escuderías registradas
        </p>
        <a href="{{ route('dashboard.escuderias.create') }}" class="btn btn-danger">
            <i class="bi bi-plus-lg me-1"></i>Nueva escudería
        </a>
    </div>

    @if($escuderias->isEmpty())
        <div class="table-panel p-4 text-center text-secondary">
            <i class="bi bi-shield-x fs-2 mb-2"></i>
            <p class="mb-0">No hay escuderías registradas aún.</p>
        </div>
    @else
    <div class="table-panel">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead style="background:#0d0d14; border-bottom:2px solid var(--fa-rojo);">
                    <tr>
                        <th class="ps-3">Escudería</th>
                        <th class="text-center">Inscripciones</th>
                        <th class="text-center">Logo</th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($escuderias as $escuderia)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold">{{ $escuderia->nombre }}</div>
                            @if($escuderia->descripcion)
                                <div class="text-secondary small">
                                    {{ Str::limit($escuderia->descripcion, 60) }}
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">
                                {{ $escuderia->inscripciones_count }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($escuderia->logo_url)
                                <i class="bi bi-check-circle-fill text-success"></i>
                            @else
                                <i class="bi bi-x-circle text-secondary"></i>
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('dashboard.escuderias.show', $escuderia->id) }}"
                                   class="btn btn-sm btn-outline-info"
                                   title="Gestionar pilotos">
                                    <i class="bi bi-people"></i>
                                </a>
                                <a href="{{ route('dashboard.escuderias.edit', $escuderia->id) }}"
                                   class="btn btn-sm btn-outline-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('dashboard.escuderias.destroy', $escuderia->id) }}"
                                      onsubmit="return confirm('¿Eliminar la escudería {{ $escuderia->nombre }}? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

@endsection