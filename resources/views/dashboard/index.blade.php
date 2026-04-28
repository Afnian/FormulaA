@extends('layouts.dashboard')

@section('title', 'Dashboard — Fórmula A')
@section('page-title', 'Dashboard')
@section('page-subtitle', $temporadaFA?->nombre ?? 'Sin temporada activa')

@section('content')

    {{-- ══ TARJETAS DE RESUMEN ══ --}}
    <div class="row g-3 mb-5">

        {{-- Pilotos inscritos --}}
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                        Pilotos inscritos
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded"
                         style="width:40px; height:40px; background:rgba(225,6,0,0.15);">
                        <i class="bi bi-people-fill text-danger"></i>
                    </div>
                </div>
                <div class="fw-black text-danger" style="font-size:2.5rem; line-height:1;">
                    {{ $pilotosInscritos }}
                </div>
                <div class="text-secondary small mt-1">En temporada activa</div>
            </div>
        </div>

        {{-- Solicitudes pendientes --}}
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                        Solicitudes
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded"
                         style="width:40px; height:40px; background:rgba(255,193,7,0.15);">
                        <i class="bi bi-person-check text-warning"></i>
                    </div>
                </div>
                <div class="fw-black text-warning" style="font-size:2.5rem; line-height:1;">
                    {{ $solicitudesPendientes }}
                </div>
                <div class="text-secondary small mt-1">Pendientes de revisión</div>
                @if($solicitudesPendientes > 0)
                    <a href="{{ route('dashboard.solicitudes.index') }}"
                       class="btn btn-sm btn-outline-warning mt-2">
                        Revisar <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
        </div>

        {{-- Noticias en borrador --}}
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                        Borradores
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded"
                         style="width:40px; height:40px; background:rgba(108,117,125,0.2);">
                        <i class="bi bi-newspaper text-secondary"></i>
                    </div>
                </div>
                <div class="fw-black text-secondary" style="font-size:2.5rem; line-height:1;">
                    {{ $noticiasBorrador }}
                </div>
                <div class="text-secondary small mt-1">Noticias sin publicar</div>
                @if($noticiasBorrador > 0)
                    <a href="{{ route('dashboard.noticias.index') }}"
                       class="btn btn-sm btn-outline-secondary mt-2">
                        Ver borradores <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
        </div>

        {{-- Temporada activa --}}
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                        Temporada
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded"
                         style="width:40px; height:40px; background:rgba(25,135,84,0.15);">
                        <i class="bi bi-calendar3 text-success"></i>
                    </div>
                </div>
                @if($temporadaFA)
                    <div class="fw-black text-success" style="font-size:1.5rem; line-height:1;">
                        {{ $temporadaFA->anio }}
                    </div>
                    <div class="text-secondary small mt-1">{{ $temporadaFA->nombre }}</div>
                    <span class="badge bg-success mt-2">Activa</span>
                @else
                    <div class="text-secondary">Sin temporada activa</div>
                @endif
            </div>
        </div>

    </div>

    {{-- ══ ÚLTIMO Y PRÓXIMO EVENTO ══ --}}
    <div class="row g-4">

        {{-- Último evento --}}
        <div class="col-md-6">
            <div class="table-panel">
                <div class="table-panel-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-check-circle me-2 text-success"></i>Último evento
                    </h6>
                    <a href="{{ route('dashboard.eventos.index') }}"
                       class="btn btn-sm btn-outline-secondary">
                        Ver todos
                    </a>
                </div>
                <div class="p-3">
                    @if($ultimoEvento)
                        <p class="text-secondary small text-uppercase mb-1" style="letter-spacing:1px;">
                            Ronda {{ $ultimoEvento->ronda }}
                        </p>
                        <h5 class="fw-bold mb-1">{{ $ultimoEvento->nombre }}</h5>
                        <p class="text-secondary small mb-2">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $ultimoEvento->circuito->nombre }}
                            · {{ $ultimoEvento->circuito->pais }}
                        </p>
                        <p class="text-secondary small mb-3">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $ultimoEvento->fecha->format('d M Y') }}
                        </p>
                        <a href="{{ route('dashboard.eventos.resultados', $ultimoEvento->id) }}"
                           class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-list-ol me-1"></i>Ver resultados
                        </a>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-calendar-x text-secondary fs-2"></i>
                            <p class="text-secondary small mt-2 mb-0">
                                No hay eventos completados aún
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Próximo evento --}}
        <div class="col-md-6">
            <div class="table-panel">
                <div class="table-panel-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-clock me-2 text-warning"></i>Próximo evento
                    </h6>
                    <a href="{{ route('dashboard.eventos.create') }}"
                       class="btn btn-sm btn-danger">
                        <i class="bi bi-plus me-1"></i>Nuevo
                    </a>
                </div>
                <div class="p-3">
                    @if($proximoEvento)
                        <p class="text-secondary small text-uppercase mb-1" style="letter-spacing:1px;">
                            Ronda {{ $proximoEvento->ronda }}
                        </p>
                        <h5 class="fw-bold mb-1">{{ $proximoEvento->nombre }}</h5>
                        <p class="text-secondary small mb-2">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $proximoEvento->circuito->nombre }}
                            · {{ $proximoEvento->circuito->pais }}
                        </p>
                        <p class="text-secondary small mb-3">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $proximoEvento->fecha->format('d M Y · H:i') }}h
                        </p>
                        <a href="{{ route('dashboard.eventos.edit', $proximoEvento->id) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil me-1"></i>Editar evento
                        </a>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-calendar-plus text-secondary fs-2"></i>
                            <p class="text-secondary small mt-2 mb-0">
                                No hay eventos futuros programados
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection