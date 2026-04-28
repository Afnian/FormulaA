@extends('layouts.dashboard')

@section('title', 'Temporadas — Panel')
@section('page-title', 'Temporadas')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-secondary mb-0">{{ $temporadas->count() }} temporadas registradas</p>
        <a href="{{ route('dashboard.temporadas.create') }}" class="btn btn-danger">
            <i class="bi bi-plus-lg me-1"></i>Nueva temporada
        </a>
    </div>

    <div class="table-panel">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead style="background:#0d0d14; border-bottom:2px solid var(--fa-rojo);">
                    <tr>
                        <th class="ps-3">Nombre</th>
                        <th>Categoría</th>
                        <th class="text-center">Año</th>
                        <th class="text-center">Eventos</th>
                        <th class="text-center">Pilotos</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temporadas as $temporada)
                    <tr>
                        <td class="ps-3 fw-bold">{{ $temporada->nombre }}</td>
                        <td>
                            <span class="badge {{ $temporada->categoria === 'formula_a' ? 'bg-danger' : 'bg-warning text-dark' }}">
                                {{ $temporada->categoria === 'formula_a' ? 'Fórmula A' : 'Fórmula B' }}
                            </span>
                        </td>
                        <td class="text-center text-secondary">{{ $temporada->anio }}</td>
                        <td class="text-center text-secondary">{{ $temporada->eventos_count }}</td>
                        <td class="text-center text-secondary">{{ $temporada->inscripciones_count }}</td>
                        <td class="text-center">
                            @if($temporada->activa)
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-secondary">Inactiva</span>
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('dashboard.temporadas.edit', $temporada->id) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('dashboard.temporadas.destroy', $temporada->id) }}"
                                      onsubmit="return confirm('¿Eliminar la temporada {{ $temporada->nombre }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">
                            No hay temporadas registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection