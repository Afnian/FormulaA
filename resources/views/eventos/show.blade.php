@extends('layouts.app')

@section('title', $evento->nombre . ' — Fórmula A')

@section('content')
<div class="container">

    {{-- Botón volver --}}
    <a href="{{ route('calendario.index') }}" class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-1"></i>Volver al calendario
    </a>

    {{-- Cabecera del evento --}}
    <div class="card-fa p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-danger">Ronda {{ $evento->ronda }}</span>
                    @if($evento->completado)
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Completado
                        </span>
                    @else
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-clock me-1"></i>Pendiente
                        </span>
                    @endif
                </div>
                <h1 class="fw-bold mb-1">{{ $evento->nombre }}</h1>
                <p class="text-secondary mb-0">
                    <i class="bi bi-calendar me-1"></i>
                    {{ $evento->fecha->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-clock me-1"></i>
                    {{ $evento->fecha->format('H:i') }}h
                </p>
                <p class="text-secondary small mt-1 mb-0">
                    <i class="bi bi-trophy me-1"></i>
                    {{ $evento->temporada->nombre }}
                </p>
            </div>

            {{-- Imagen circuito --}}
            <div class="col-md-4 text-center mt-3 mt-md-0">
                @if($evento->circuito->imagen_url)
                    <img src="{{ $evento->circuito->imagen_url }}"
                         alt="{{ $evento->circuito->nombre }}"
                         class="img-fluid rounded"
                         style="max-height:120px; object-fit:cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center rounded"
                         style="height:120px; background:#1e1e2e;">
                        <i class="bi bi-map text-danger" style="font-size:3rem;"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ EVENTO FUTURO: ficha del circuito ══ --}}
    @if(!$evento->completado)
    <h3 class="fw-bold text-uppercase mb-3" style="letter-spacing:1px;">
        <i class="bi bi-map me-2 text-danger"></i>Información del circuito
    </h3>

    <div class="card-fa p-4 mb-4">
        <div class="row g-4 align-items-center">
            <div class="col-md-6">
                @if($evento->circuito->imagen_url)
                    <img src="{{ $evento->circuito->imagen_url }}"
                         alt="{{ $evento->circuito->nombre }}"
                         class="img-fluid rounded w-100"
                         style="max-height:240px; object-fit:cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center rounded"
                         style="height:240px; background:#1e1e2e;">
                        <i class="bi bi-map text-danger" style="font-size:5rem;"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <h4 class="fw-bold mb-3">{{ $evento->circuito->nombre }}</h4>

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded"
                             style="width:48px; height:48px; background:#1e1e2e;">
                            <i class="bi bi-geo-alt-fill text-danger fs-5"></i>
                        </div>
                        <div>
                            <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">País</div>
                            <div class="fw-bold">{{ $evento->circuito->pais }}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded"
                             style="width:48px; height:48px; background:#1e1e2e;">
                            <i class="bi bi-arrow-repeat text-danger fs-5"></i>
                        </div>
                        <div>
                            <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">Número de vueltas</div>
                            <div class="fw-bold">{{ $evento->circuito->num_vueltas }} vueltas</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded"
                             style="width:48px; height:48px; background:#1e1e2e;">
                            <i class="bi bi-calendar-event text-danger fs-5"></i>
                        </div>
                        <div>
                            <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">Fecha de carrera</div>
                            <div class="fw-bold">
                                {{ $evento->fecha->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ══ EVENTO COMPLETADO: tabla de resultados ══ --}}
    @if($evento->completado)
    <h3 class="fw-bold text-uppercase mb-3" style="letter-spacing:1px;">
        <i class="bi bi-list-ol me-2 text-danger"></i>Resultados
    </h3>

    @if($resultados->isEmpty())
        <div class="alert alert-secondary">No hay resultados cargados para este evento.</div>
    @else
    <div class="card-fa p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead style="background:#1e1e2e; border-bottom: 2px solid var(--fa-rojo);">
                    <tr>
                        <th class="ps-3" style="width:60px;">POS</th>
                        <th>Piloto</th>
                        <th>Escudería</th>
                        <th class="text-center">Distancia</th>
                        <th class="text-center">Pole</th>
                        <th class="text-center">V. Rápida</th>
                        <th class="text-center">Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resultados as $resultado)
                    @php
                        $claseFila = match($resultado->posicion) {
                            1       => 'fila-p1',
                            2       => 'fila-p2',
                            3       => 'fila-p3',
                            default => ''
                        };
                        $clasePos = match($resultado->posicion) {
                            1       => 'pos-p1',
                            2       => 'pos-p2',
                            3       => 'pos-p3',
                            default => 'text-white'
                        };
                    @endphp
                    <tr class="{{ $claseFila }}">
                        <td class="ps-3">
                            @if($resultado->dnf)
                                <span class="badge bg-secondary">DNF</span>
                            @else
                                <span class="fw-black {{ $clasePos }}" style="font-size:1.1rem;">
                                    {{ $resultado->posicion }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">
                                {{ $resultado->inscripcion->piloto->gamertag }}
                            </div>
                            <div class="text-secondary small">
                                {{ $resultado->inscripcion->piloto->nacionalidad ?? '' }}
                            </div>
                        </td>
                        <td>
                            <span class="text-secondary">
                                {{ $resultado->inscripcion->escuderia->nombre }}
                            </span>
                        </td>
                        <td class="text-center text-secondary">
                            {{ $resultado->diferencia ?? '—' }}
                        </td>
                        <td class="text-center">
                            @if($resultado->pts_pole > 0)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-p-circle-fill me-1"></i>+{{ $resultado->pts_pole }}
                                </span>
                            @else
                                <span class="text-secondary">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($resultado->pts_vuelta_rap > 0)
                                <span class="badge bg-info text-dark">
                                    <i class="bi bi-lightning-fill me-1"></i>+{{ $resultado->pts_vuelta_rap }}
                                </span>
                            @else
                                <span class="text-secondary">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($resultado->dnf)
                                <span class="text-secondary">0</span>
                            @else
                                <span class="fw-bold text-danger">
                                    {{ $resultado->puntos_totales }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endif

</div>
@push('styles')
<style>
    .fila-p1 { background-color: rgba(255, 215, 0, 0.08) !important; }
    .fila-p2 { background-color: rgba(192, 192, 192, 0.08) !important; }
    .fila-p3 { background-color: rgba(205, 127, 50, 0.08) !important; }
    .pos-p1  { color: #ffd700; }
    .pos-p2  { color: #c0c0c0; }
    .pos-p3  { color: #cd7f32; }
</style>
@endpush
@endsection