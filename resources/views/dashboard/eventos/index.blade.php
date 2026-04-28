@extends('layouts.dashboard')

@section('title', 'Eventos — Panel')
@section('page-title', 'Eventos')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-secondary mb-0">{{ $eventos->count() }} eventos registrados</p>
        <a href="{{ route('dashboard.eventos.create') }}" class="btn btn-danger">
            <i class="bi bi-plus-lg me-1"></i>Nuevo evento
        </a>
    </div>

    <div class="table-panel">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead style="background:#0d0d14; border-bottom:2px solid var(--fa-rojo);">
                    <tr>
                        <th class="ps-3" style="width:60px;">R</th>
                        <th>Evento</th>
                        <th>Temporada</th>
                        <th>Circuito</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventos as $evento)
                    <tr>
                        <td class="ps-3">
                            <span class="fw-black text-danger">{{ $evento->ronda }}</span>
                        </td>
                        <td class="fw-bold">{{ $evento->nombre }}</td>
                        <td>
                            <span class="badge {{ $evento->temporada->categoria === 'formula_a' ? 'bg-danger' : 'bg-warning text-dark' }}">
                                {{ $evento->temporada->nombre }}
                            </span>
                        </td>
                        <td class="text-secondary small">
                            {{ $evento->circuito->nombre }}
                        </td>
                        <td class="text-center text-secondary small">
                            {{ $evento->fecha->format('d M Y · H:i') }}
                        </td>
                        <td class="text-center">
                            @if($evento->completado)
                                <span class="badge bg-success">Completado</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                @if($evento->completado)
                                    <a href="{{ route('dashboard.eventos.resultados', $evento->id) }}"
                                       class="btn btn-sm btn-outline-success"
                                       title="Resultados">
                                        <i class="bi bi-list-ol"></i>
                                    </a>
                                @else
                                    <form method="POST"
                                          action="{{ route('dashboard.eventos.completar', $evento->id) }}"
                                          onsubmit="return confirm('¿Marcar {{ $evento->nombre }} como completado?')">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-success"
                                                title="Marcar como completado">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('dashboard.eventos.edit', $evento->id) }}"
                                   class="btn btn-sm btn-outline-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('dashboard.eventos.destroy', $evento->id) }}"
                                      onsubmit="return confirm('¿Eliminar el evento {{ $evento->nombre }}?')">
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
                        <td colspan="7" class="text-center text-secondary py-4">
                            No hay eventos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection