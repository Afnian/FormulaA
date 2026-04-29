{{-- resources/views/eventos/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Calendario — Fórmula A')

@section('content')
<div class="container">

    <div class="mb-5">
        <h1 class="fw-bold text-uppercase" style="letter-spacing:2px;">
            <i class="bi bi-calendar3 me-2 text-danger"></i>Calendario
        </h1>
        @if($temporadaFA)
            <p class="text-secondary">{{ $temporadaFA->nombre }} · {{ $temporadaFA->anio }}</p>
        @endif
    </div>

    @if($eventos->isEmpty())
        <div class="alert alert-secondary">No hay eventos registrados para esta temporada.</div>
    @else
        <div class="d-flex flex-column gap-4">
            @foreach($eventos as $evento)
            <div class="card-fa p-0 overflow-hidden">
                <div class="row g-0">

                    {{-- Número de ronda --}}
                    <div class="col-auto d-flex align-items-center justify-content-center px-4 {{ $evento->completado ? 'ronda-completada' : 'ronda-proxima' }}"
                         style="min-width:80px;">
                        <div class="text-center">
                            <div class="fw-black" style="font-size:1.8rem; line-height:1;">
                                {{ $evento->ronda }}
                            </div>
                            <div class="small text-uppercase" style="letter-spacing:1px; font-size:0.65rem;">
                                Ronda
                            </div>
                        </div>
                    </div>

                    {{-- Contenido principal --}}
                    <div class="col p-3">
                        <div class="row align-items-center g-3">

                            {{-- Info del evento --}}
                            <div class="col-md-5">
                                @if($evento->completado)
                                    <span class="badge bg-success mb-1">
                                        <i class="bi bi-check-circle me-1"></i>Completado
                                    </span>
                                @else
                                    <span class="badge bg-danger mb-1">
                                        <i class="bi bi-clock me-1"></i>Próximo
                                    </span>
                                @endif

                                <h4 class="fw-bold mb-1">{{ $evento->nombre }}</h4>

                                <p class="text-secondary small mb-0">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $evento->circuito->nombre }}
                                    &nbsp;·&nbsp;
                                    {{ $evento->circuito->pais }}
                                </p>
                                <p class="text-secondary small mb-0">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $evento->fecha->isoFormat('D [de] MMMM [de] YYYY') }}
                                    &nbsp;·&nbsp;
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $evento->fecha->format('H:i') }}h
                                </p>
                            </div>

                            {{-- Podio o imagen del circuito --}}
                            <div class="col-md-5">
                                @if($evento->completado && isset($evento->podio) && $evento->podio->count())
                                    {{-- Mini podio --}}
                                    <div class="d-flex gap-2 align-items-center">
                                        @foreach($evento->podio as $puesto)
                                        @php
                                            $clasePos = match($puesto->posicion) {
                                                1 => 'pos-p1',
                                                2 => 'pos-p2',
                                                3 => 'pos-p3',
                                                default => 'text-white'
                                            };
                                        @endphp
                                        <div class="text-center">
                                            <div class="fw-bold {{ $clasePos }}" style="font-size:1.1rem;">
                                                P{{ $puesto->posicion }}
                                            </div>
                                            <div class="small fw-bold">
                                                {{ $puesto->inscripcion->piloto->gamertag }}
                                            </div>
                                            <div class="small text-secondary" style="font-size:0.7rem;">
                                                {{ $puesto->inscripcion->escuderia->nombre }}
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <div class="text-secondary">·</div>
                                        @endif
                                        @endforeach
                                    </div>
                                @elseif(!$evento->completado)
                                    {{-- Info del circuito --}}
                                    @if($evento->circuito->imagen_url)
                                        <img src="{{ $evento->circuito->imagen_url }}"
                                             alt="{{ $evento->circuito->nombre }}"
                                             class="img-fluid rounded"
                                             style="max-height:80px; object-fit:cover;">
                                    @else
                                        <div class="d-flex align-items-center gap-2 text-secondary small">
                                            <i class="bi bi-flag text-danger fs-4"></i>
                                            <div>
                                                <div>{{ $evento->circuito->num_vueltas }} vueltas</div>
                                                <div>{{ $evento->circuito->pais }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            {{-- Botón detalle --}}
                            <div class="col-md-2 text-md-end">
                                <a href="{{ route('calendario.show', $evento->id) }}"
                                   class="btn btn-sm {{ $evento->completado ? 'btn-outline-success' : 'btn-outline-danger' }}">
                                    {{ $evento->completado ? 'Resultados' : 'Detalles' }}
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    .ronda-completada { background: #2a2a3a; }
    .ronda-proxima    { background: var(--fa-rojo); }
    .pos-p1 { color: #ffd700; }
    .pos-p2 { color: #c0c0c0; }
    .pos-p3 { color: #cd7f32; }
</style>
@endpush