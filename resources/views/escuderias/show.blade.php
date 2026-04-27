@extends('layouts.app')

@section('title', $escuderia->nombre . ' — Fórmula A')

@section('content')
<div class="container">

    {{-- Cabecera --}}
    <div class="mb-4">
        <a href="{{ route('escuderias.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left me-1"></i>Volver a escuderías
        </a>

        <div class="card-fa p-4">
            <div class="row align-items-center">
                {{-- Logo --}}
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    @if($escuderia->logo_url)
                        <img src="{{ $escuderia->logo_url }}"
                             alt="Logo {{ $escuderia->nombre }}"
                             class="img-fluid"
                             style="max-height:140px; object-fit:contain;">
                    @else
                        <div class="d-flex align-items-center justify-content-center rounded"
                             style="height:140px; background:#1e1e2e;">
                            <i class="bi bi-shield-fill text-danger" style="font-size:4rem;"></i>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="col-md-9">
                    <h1 class="fw-bold mb-2">{{ $escuderia->nombre }}</h1>
                    @if($escuderia->descripcion)
                        <p class="text-secondary mb-0">{{ $escuderia->descripcion }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Plantilla --}}
    <h3 class="fw-bold text-uppercase mb-3" style="letter-spacing:1px;">
        <i class="bi bi-people-fill me-2 text-danger"></i>
        Plantilla
        @if($temporadaFA)
            <span class="fs-6 text-secondary fw-normal ms-2">— {{ $temporadaFA->nombre }}</span>
        @endif
    </h3>

    @if($inscripciones->isEmpty())
        <div class="alert alert-secondary">
            No hay pilotos inscritos en esta escudería para la temporada activa.
        </div>
    @else
        <div class="row g-3">
            @foreach($inscripciones as $inscripcion)
            @php
                $badgeColor = match($inscripcion->tipo) {
                    'oficial'  => 'bg-danger',
                    'reserva'  => 'bg-warning text-dark',
                    'academia' => 'bg-secondary',
                    default    => 'bg-secondary'
                };
                $icono = match($inscripcion->tipo) {
                    'oficial'  => 'bi-star-fill',
                    'reserva'  => 'bi-star-half',
                    'academia' => 'bi-mortarboard-fill',
                    default    => 'bi-person'
                };
            @endphp
            <div class="col-md-4">
                <div class="card-fa p-3 d-flex flex-row align-items-center gap-3">
                    {{-- Avatar --}}
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                         style="width:56px; height:56px; background:#1e1e2e;">
                        <i class="bi bi-person-fill text-danger fs-4"></i>
                    </div>

                    {{-- Datos --}}
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="fw-bold mb-0 text-truncate">
                            {{ $inscripcion->piloto->gamertag }}
                        </h6>
                        <p class="text-secondary small mb-1">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $inscripcion->piloto->nacionalidad ?? 'Sin especificar' }}
                        </p>
                        <span class="badge {{ $badgeColor }}">
                            <i class="bi {{ $icono }} me-1"></i>
                            {{ ucfirst($inscripcion->tipo) }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection