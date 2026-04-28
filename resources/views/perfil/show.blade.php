@extends('layouts.app')

@section('title', 'Mi Perfil — Fórmula A')

@section('content')
<div class="container">

    <div class="mb-4">
        <h1 class="fw-bold text-uppercase" style="letter-spacing:2px;">
            <i class="bi bi-person-circle me-2 text-danger"></i>Mi Perfil
        </h1>
    </div>

    {{-- ══ CABECERA DEL PILOTO ══ --}}
    <div class="card-fa p-4 mb-4">
        <div class="row align-items-center g-4">

            {{-- Avatar --}}
            <div class="col-md-2 text-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto"
                     style="width:90px; height:90px; background:#1e1e2e; border:3px solid var(--fa-rojo);">
                    <i class="bi bi-person-fill text-danger" style="font-size:3rem;"></i>
                </div>
            </div>

            {{-- Datos principales --}}
            <div class="col-md-5">
                <h2 class="fw-bold mb-1">{{ $piloto->gamertag }}</h2>
                <p class="text-secondary mb-1">
                    <i class="bi bi-person me-1"></i>{{ $usuario->nombre }}
                </p>
                <p class="text-secondary mb-1">
                    <i class="bi bi-geo-alt me-1"></i>
                    {{ $piloto->nacionalidad ?? 'Sin especificar' }}
                </p>
                <p class="text-secondary mb-0">
                    <i class="bi bi-envelope me-1"></i>{{ $usuario->email }}
                </p>
            </div>

            {{-- Escudería activa --}}
            <div class="col-md-5">
                @if($inscripcionActiva)
                    <div class="p-3 rounded" style="background:#1e1e2e; border-left:4px solid var(--fa-rojo);">
                        <p class="text-secondary small text-uppercase mb-1" style="letter-spacing:1px;">
                            Escudería {{ $temporadaFA?->nombre }}
                        </p>
                        <h5 class="fw-bold mb-1">
                            {{ $inscripcionActiva->escuderia->nombre }}
                        </h5>
                        <span class="badge bg-danger">
                            {{ ucfirst($inscripcionActiva->tipo) }}
                        </span>
                    </div>
                @else
                    <div class="p-3 rounded text-center" style="background:#1e1e2e;">
                        <i class="bi bi-shield-x text-secondary fs-3"></i>
                        <p class="text-secondary small mt-2 mb-0">
                            Sin escudería en la temporada activa
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ══ ESTADÍSTICAS ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="card-fa p-3 text-center">
                <div class="fw-black text-danger" style="font-size:2rem;">
                    {{ $stats['carreras'] }}
                </div>
                <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                    Carreras
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-fa p-3 text-center">
                <div class="fw-black" style="font-size:2rem; color:#ffd700;">
                    {{ $stats['victorias'] }}
                </div>
                <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                    Victorias
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-fa p-3 text-center">
                <div class="fw-black" style="font-size:2rem; color:#cd7f32;">
                    {{ $stats['podios'] }}
                </div>
                <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                    Podios
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-fa p-3 text-center">
                <div class="fw-black text-warning" style="font-size:2rem;">
                    {{ $stats['poles'] }}
                </div>
                <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                    Poles
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-fa p-3 text-center">
                <div class="fw-black text-info" style="font-size:2rem;">
                    {{ $stats['vueltas_rapidas'] }}
                </div>
                <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                    V. Rápidas
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-fa p-3 text-center">
                <div class="fw-black text-danger" style="font-size:2rem;">
                    {{ $stats['puntos_totales'] }}
                </div>
                <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                    Puntos
                </div>
            </div>
        </div>
    </div>

    {{-- ══ PUNTOS TEMPORADA ACTIVA ══ --}}
    @if($temporadaFA && $inscripcionActiva)
    <div class="card-fa p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3"
         style="border-left:4px solid var(--fa-rojo);">
        <div>
            <p class="text-secondary small text-uppercase mb-0" style="letter-spacing:1px;">
                <i class="bi bi-trophy me-1"></i>Puntos en {{ $temporadaFA->nombre }}
            </p>
        </div>
        <div>
            <span class="fw-black text-danger" style="font-size:2.5rem;">
                {{ $puntosTemporada }}
            </span>
            <span class="text-secondary ms-1">pts</span>
        </div>
    </div>
    @endif

    {{-- ══ SOLICITUD DE INSCRIPCIÓN ══ --}}
    @if($solicitud)
    <div class="mb-4">
        @php
            $badgeSolicitud = match($solicitud->estado) {
                'pendiente' => 'bg-warning text-dark',
                'aceptada'  => 'bg-success',
                'rechazada' => 'bg-danger',
                default     => 'bg-secondary'
            };
            $iconoSolicitud = match($solicitud->estado) {
                'pendiente' => 'bi-hourglass-split',
                'aceptada'  => 'bi-check-circle-fill',
                'rechazada' => 'bi-x-circle-fill',
                default     => 'bi-question-circle'
            };
        @endphp
        <div class="card-fa p-3 d-flex align-items-center gap-3">
            <i class="bi {{ $iconoSolicitud }} fs-4
                {{ $solicitud->estado === 'aceptada' ? 'text-success' :
                   ($solicitud->estado === 'rechazada' ? 'text-danger' : 'text-warning') }}">
            </i>
            <div>
                <p class="fw-bold mb-0">Solicitud de inscripción</p>
                <p class="text-secondary small mb-0">
                    Enviada el {{ $solicitud->fecha_solicitud->format('d M Y') }}
                </p>
            </div>
            <span class="badge {{ $badgeSolicitud }} ms-auto">
                {{ ucfirst($solicitud->estado) }}
            </span>
        </div>
    </div>
    @else
    <div class="mb-4">
        <div class="card-fa p-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <p class="fw-bold mb-0">¿Quieres inscribirte en la próxima temporada?</p>
                <p class="text-secondary small mb-0">
                    Envía una solicitud y el administrador la revisará.
                </p>
            </div>
            <a href="{{ route('inscripcion.create') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-send me-1"></i>Solicitar inscripción
            </a>
        </div>
    </div>
    @endif

    {{-- ══ HISTORIAL DE RESULTADOS ══ --}}
    <h4 class="fw-bold text-uppercase mb-3" style="letter-spacing:1px;">
        <i class="bi bi-list-ol me-2 text-danger"></i>Historial de carreras
    </h4>

    @if($historial->isEmpty())
        <div class="alert alert-secondary">No has participado en ninguna carrera aún.</div>
    @else
    <div class="card-fa p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead style="background:#1e1e2e; border-bottom:2px solid var(--fa-rojo);">
                    <tr>
                        <th class="ps-3">Evento</th>
                        <th>Temporada</th>
                        <th>Escudería</th>
                        <th class="text-center">POS</th>
                        <th class="text-center">Pole</th>
                        <th class="text-center">V. Rápida</th>
                        <th class="text-center">DNF</th>
                        <th class="text-center pe-3">Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historial as $resultado)
                    @php
                        $clasePos = match($resultado->posicion) {
                            1 => 'pos-p1', 2 => 'pos-p2', 3 => 'pos-p3',
                            default => 'text-white'
                        };
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold small">
                                {{ $resultado->evento->nombre }}
                            </div>
                            <div class="text-secondary" style="font-size:0.72rem;">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $resultado->evento->circuito->nombre }}
                            </div>
                        </td>
                        <td class="text-secondary small">
                            {{ $resultado->evento->temporada->nombre }}
                        </td>
                        <td class="text-secondary small">
                            {{ $resultado->inscripcion->escuderia->nombre }}
                        </td>
                        <td class="text-center">
                            @if($resultado->dnf)
                                <span class="badge bg-secondary">DNF</span>
                            @else
                                <span class="fw-black {{ $clasePos }}">
                                    {{ $resultado->posicion }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($resultado->pts_pole > 0)
                                <i class="bi bi-p-circle-fill text-warning"></i>
                            @else
                                <span class="text-secondary">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($resultado->pts_vuelta_rap > 0)
                                <i class="bi bi-lightning-fill text-info"></i>
                            @else
                                <span class="text-secondary">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($resultado->dnf)
                                <i class="bi bi-x-circle-fill text-danger"></i>
                            @else
                                <span class="text-secondary">—</span>
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            <span class="fw-bold text-danger">
                                {{ $resultado->puntos_totales }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    .pos-p1 { color: #ffd700; }
    .pos-p2 { color: #c0c0c0; }
    .pos-p3 { color: #cd7f32; }
</style>
@endpush