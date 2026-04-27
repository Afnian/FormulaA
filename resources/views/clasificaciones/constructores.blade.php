@extends('layouts.app')

@section('title', 'Mundial de Constructores — Fórmula A')

@section('content')
<div class="container">

    {{-- Cabecera --}}
    <div class="mb-4">
        <h1 class="fw-bold text-uppercase" style="letter-spacing:2px;">
            <i class="bi bi-shield-fill me-2 text-danger"></i>Mundial de Constructores
        </h1>
        @if($temporadaFA)
            <p class="text-secondary">{{ $temporadaFA->nombre }} · {{ $temporadaFA->anio }}</p>
        @endif
    </div>

    {{-- Selector de filtro por evento --}}
    @if($eventos->count())
    <div class="card-fa p-3 mb-4">
        <form method="GET" action="{{ route('clasificaciones.constructores') }}"
              class="d-flex align-items-center gap-3 flex-wrap">
            <label class="text-secondary small text-uppercase mb-0" style="letter-spacing:1px;">
                <i class="bi bi-funnel me-1"></i>Ver mundial hasta:
            </label>
            <select name="hasta_evento" class="form-select form-select-sm bg-dark text-white border-secondary"
                    style="max-width:280px;" onchange="this.form.submit()">
                <option value="">— Temporada completa —</option>
                @foreach($eventos as $ev)
                    <option value="{{ $ev->id }}"
                        {{ $eventoFiltro?->id == $ev->id ? 'selected' : '' }}>
                        R{{ $ev->ronda }} · {{ $ev->nombre }}
                    </option>
                @endforeach
            </select>
            @if($eventoFiltro)
                <a href="{{ route('clasificaciones.constructores') }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Limpiar filtro
                </a>
            @endif
        </form>
    </div>
    @endif

    {{-- Tabla de clasificación --}}
    @if($clasificacion->isEmpty())
        <div class="alert alert-secondary">No hay resultados registrados aún.</div>
    @else
    <div class="card-fa p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead style="background:#1e1e2e; border-bottom:2px solid var(--fa-rojo);">
                    <tr>
                        <th class="ps-3" style="width:60px;">POS</th>
                        <th>Escudería</th>
                        <th>Pilotos</th>
                        <th class="text-center">Victorias</th>
                        <th class="text-center pe-3">Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clasificacion as $index => $item)
                    @php
                        $pos = $index + 1;
                        $clasePos = match($pos) {
                            1 => 'pos-p1',
                            2 => 'pos-p2',
                            3 => 'pos-p3',
                            default => 'text-white'
                        };
                        $claseFila = match($pos) {
                            1 => 'fila-p1',
                            2 => 'fila-p2',
                            3 => 'fila-p3',
                            default => ''
                        };
                    @endphp
                    <tr class="{{ $claseFila }}">
                        <td class="ps-3">
                            <span class="fw-black {{ $clasePos }}" style="font-size:1.1rem;">
                                {{ $pos }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($item['escuderia']->logo_url)
                                    <img src="{{ $item['escuderia']->logo_url }}"
                                         alt="{{ $item['escuderia']->nombre }}"
                                         style="height:36px; width:36px; object-fit:contain;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center rounded"
                                         style="width:36px; height:36px; background:#1e1e2e;">
                                        <i class="bi bi-shield-fill text-danger small"></i>
                                    </div>
                                @endif
                                <span class="fw-bold">{{ $item['escuderia']->nombre }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($item['pilotos'] as $gamertag)
                                    <span class="badge bg-secondary">{{ $gamertag }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="text-center">
                            @if($item['victorias'] > 0)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-trophy-fill me-1"></i>{{ $item['victorias'] }}
                                </span>
                            @else
                                <span class="text-secondary">0</span>
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            <span class="fw-black text-danger" style="font-size:1.2rem;">
                                {{ $item['puntos'] }}
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
    .fila-p1 { background-color: rgba(255, 215, 0, 0.08) !important; }
    .fila-p2 { background-color: rgba(192, 192, 192, 0.08) !important; }
    .fila-p3 { background-color: rgba(205, 127, 50, 0.08) !important; }
    .pos-p1  { color: #ffd700; }
    .pos-p2  { color: #c0c0c0; }
    .pos-p3  { color: #cd7f32; }
</style>
@endpush