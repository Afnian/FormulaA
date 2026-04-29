@extends('layouts.dashboard')

@section('title', 'Pilotos — Panel')
@section('page-title', 'Pilotos')
@section('page-subtitle', 'Gestión de pilotos')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-secondary mb-0">{{ $pilotos->count() }} pilotos registrados</p>
        <a href="{{ route('dashboard.pilotos.create') }}" class="btn btn-danger">
            <i class="bi bi-plus-lg me-1"></i>Nuevo piloto
        </a>
    </div>

    <div class="table-panel">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead style="background:#0d0d14; border-bottom:2px solid var(--fa-rojo);">
                    <tr>
                        <th class="ps-3">Gamertag</th>
                        <th>Usuario vinculado</th>
                        <th>Nacionalidad</th>
                        <th>Escudería actual</th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pilotos as $piloto)
                    @php
                        $inscripcionActiva = $piloto->inscripciones
                            ->sortByDesc('id_temporada')
                            ->first();
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold">{{ $piloto->gamertag }}</div>
                        </td>
                        <td>
                            @if($piloto->usuario)
                                <div class="small fw-bold">{{ $piloto->usuario->nombre }}</div>
                                <div class="text-secondary" style="font-size:0.75rem;">
                                    {{ $piloto->usuario->email }}
                                </div>
                            @else
                                <span class="text-secondary small">
                                    <i class="bi bi-person-x me-1"></i>Sin usuario
                                </span>
                            @endif
                        </td>
                        <td class="text-secondary small">
                            {{ $piloto->nacionalidad ?? '—' }}
                        </td>
                        <td class="text-secondary small">
                            @if($inscripcionActiva)
                                <div>{{ $inscripcionActiva->escuderia->nombre }}</div>
                                <div style="font-size:0.7rem;">
                                    {{ $inscripcionActiva->temporada->nombre }}
                                </div>
                            @else
                                <span class="text-secondary">Sin escudería</span>
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('dashboard.pilotos.edit', $piloto->id) }}"
                                   class="btn btn-sm btn-outline-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('dashboard.pilotos.destroy', $piloto->id) }}"
                                      onsubmit="return confirm('¿Eliminar al piloto {{ $piloto->gamertag }}? Esta acción no se puede deshacer.')">
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
                        <td colspan="5" class="text-center text-secondary py-4">
                            No hay pilotos registrados aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection