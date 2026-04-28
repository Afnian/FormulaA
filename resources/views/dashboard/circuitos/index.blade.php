@extends('layouts.dashboard')

@section('title', 'Circuitos — Panel')
@section('page-title', 'Circuitos')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-secondary mb-0">{{ $circuitos->count() }} circuitos registrados</p>
        <a href="{{ route('dashboard.circuitos.create') }}" class="btn btn-danger">
            <i class="bi bi-plus-lg me-1"></i>Nuevo circuito
        </a>
    </div>

    <div class="table-panel">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead style="background:#0d0d14; border-bottom:2px solid var(--fa-rojo);">
                    <tr>
                        <th class="ps-3">Nombre</th>
                        <th>País</th>
                        <th class="text-center">Vueltas</th>
                        <th class="text-center">Eventos</th>
                        <th class="text-center">Imagen</th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($circuitos as $circuito)
                    <tr>
                        <td class="ps-3 fw-bold">{{ $circuito->nombre }}</td>
                        <td class="text-secondary">{{ $circuito->pais }}</td>
                        <td class="text-center text-secondary">{{ $circuito->num_vueltas }}</td>
                        <td class="text-center text-secondary">{{ $circuito->eventos_count }}</td>
                        <td class="text-center">
                            @if($circuito->imagen_url)
                                <i class="bi bi-check-circle-fill text-success"></i>
                            @else
                                <i class="bi bi-x-circle text-secondary"></i>
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('dashboard.circuitos.edit', $circuito->id) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('dashboard.circuitos.destroy', $circuito->id) }}"
                                      onsubmit="return confirm('¿Eliminar el circuito {{ $circuito->nombre }}?')">
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
                        <td colspan="6" class="text-center text-secondary py-4">
                            No hay circuitos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection