@extends('layouts.dashboard')

@section('title', 'Solicitudes — Panel')
@section('page-title', 'Solicitudes de inscripción')
@section('page-subtitle', $pendientes . ' pendientes')

@section('content')

    {{-- Resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="stat-card text-center">
                <div class="fw-black text-warning" style="font-size:2rem;">
                    {{ $solicitudes->where('estado', 'pendiente')->count() }}
                </div>
                <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                    Pendientes
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card text-center">
                <div class="fw-black text-success" style="font-size:2rem;">
                    {{ $solicitudes->where('estado', 'aceptada')->count() }}
                </div>
                <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                    Aceptadas
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card text-center">
                <div class="fw-black text-danger" style="font-size:2rem;">
                    {{ $solicitudes->where('estado', 'rechazada')->count() }}
                </div>
                <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                    Rechazadas
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="table-panel">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead style="background:#0d0d14; border-bottom:2px solid var(--fa-rojo);">
                    <tr>
                        <th class="ps-3">Usuario</th>
                        <th>Email</th>
                        <th class="text-center">Fecha solicitud</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $solicitud)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold">{{ $solicitud->usuario->nombre }}</div>
                            <div class="text-secondary small">
                                Rol actual:
                                <span class="badge
                                    {{ $solicitud->usuario->rol === 'piloto' ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ ucfirst($solicitud->usuario->rol) }}
                                </span>
                            </div>
                        </td>
                        <td class="text-secondary small">
                            {{ $solicitud->usuario->email }}
                        </td>
                        <td class="text-center text-secondary small">
                            {{ $solicitud->fecha_solicitud->format('d M Y · H:i') }}
                        </td>
                        <td class="text-center">
                            @if($solicitud->estado === 'pendiente')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-hourglass-split me-1"></i>Pendiente
                                </span>
                            @elseif($solicitud->estado === 'aceptada')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Aceptada
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>Rechazada
                                </span>
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            @if($solicitud->estado === 'pendiente')
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Aceptar --}}
                                    <form method="POST"
                                          action="{{ route('dashboard.solicitudes.update', $solicitud->id) }}"
                                          onsubmit="return confirm('¿Aceptar la solicitud de {{ $solicitud->usuario->nombre }}? Se le asignará rol de piloto.')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="accion" value="aceptar">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg me-1"></i>Aceptar
                                        </button>
                                    </form>

                                    {{-- Rechazar --}}
                                    <form method="POST"
                                          action="{{ route('dashboard.solicitudes.update', $solicitud->id) }}"
                                          onsubmit="return confirm('¿Rechazar la solicitud de {{ $solicitud->usuario->nombre }}?')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="accion" value="rechazar">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-lg me-1"></i>Rechazar
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-secondary small">Sin acciones</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">
                            No hay solicitudes registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection