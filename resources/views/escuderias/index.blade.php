@extends('layouts.app')

@section('title', 'Escuderías — Fórmula A')

@section('content')
<div class="container">

    <div class="mb-5">
        <h1 class="fw-bold text-uppercase" style="letter-spacing:2px;">
            <i class="bi bi-shield-fill me-2 text-primary"></i>Escuderías
        </h1>
        <p class="text-secondary">
            Todos los equipos inscritos en la temporada activa de Fórmula A.
        </p>
    </div>

    @if($escuderias->isEmpty())
        <div class="alert alert-secondary">No hay escuderías registradas aún.</div>
    @else
    <div class="row g-4">
        @foreach($escuderias as $escuderia)
        <div class="col-md-4">
            <div class="card-fa p-4 h-100 d-flex flex-column">

                {{-- Logo --}}
                <div class="text-center mb-3">
                    @if($escuderia->logo_url)
                        <img src="{{ $escuderia->logo_url }}"
                             alt="Logo {{ $escuderia->nombre }}"
                             class="img-fluid rounded"
                             style="max-height:100px; object-fit:contain;">
                    @else
                        <div class="d-flex align-items-center justify-content-center rounded"
                             style="height:100px; background:#1e1e2e;">
                            <i class="bi bi-shield-fill text-primary" style="font-size:3rem;"></i>
                        </div>
                    @endif
                </div>

                {{-- Nombre --}}
                <h4 class="fw-bold text-center mb-2">{{ $escuderia->nombre }}</h4>

                {{-- Pilotos activos --}}
                <p class="text-center mb-3">
                    <span class="badge bg-primary">
                        <i class="bi bi-people-fill me-1"></i>
                        {{ $escuderia->pilotos_activos }}
                        {{ $escuderia->pilotos_activos === 1 ? 'piloto' : 'pilotos' }}
                    </span>
                </p>

                {{-- Descripción --}}
                @if($escuderia->descripcion)
                <p class="text-secondary small flex-grow-1">
                    {{ Str::limit($escuderia->descripcion, 120) }}
                </p>
                @endif

                <a href="{{ route('escuderias.show', $escuderia->id) }}"
                   class="btn btn-outline-primary btn-sm mt-3 align-self-center">
                    Ver equipo <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection