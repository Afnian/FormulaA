@extends('layouts.dashboard')

@section('title', 'Cargar Resultados — Panel')
@section('page-title', 'Cargar resultados')
@section('page-subtitle', $evento->nombre)

@section('content')

    <a href="{{ route('dashboard.eventos.index') }}"
       class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-1"></i>Volver a eventos
    </a>

    {{-- Info del evento --}}
    <div class="table-panel p-3 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-danger">Ronda {{ $evento->ronda }}</span>
                    <span class="badge bg-success">Completado</span>
                    <span class="badge {{ $evento->temporada->categoria === 'formula_a' ? 'bg-danger' : 'bg-warning text-dark' }}">
                        {{ $evento->temporada->nombre }}
                    </span>
                </div>
                <h5 class="fw-bold mb-0">{{ $evento->nombre }}</h5>
                <p class="text-secondary small mb-0">
                    <i class="bi bi-geo-alt me-1"></i>
                    {{ $evento->circuito->nombre }} · {{ $evento->circuito->pais }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-calendar me-1"></i>
                    {{ $evento->fecha->format('d M Y') }}
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <div class="text-secondary small">
                    <i class="bi bi-info-circle me-1"></i>
                    Los puntos se calculan automáticamente
                </div>
            </div>
        </div>
    </div>

    {{-- Leyenda --}}
    <div class="d-flex flex-wrap gap-3 mb-3 small text-secondary">
        <div><span class="badge bg-warning text-dark me-1">P</span> Pole position (+2 pts)</div>
        <div><span class="badge bg-info text-dark me-1">VR</span> Vuelta rápida (+1 pt si top 10)</div>
        <div><span class="badge bg-secondary me-1">DNF</span> No finalizó (0 pts)</div>
    </div>

    @if($inscripciones->isEmpty())
        <div class="table-panel p-4 text-center text-secondary">
            <i class="bi bi-people-fill fs-2 mb-2"></i>
            <p class="mb-0">No hay pilotos inscritos en esta temporada.</p>
        </div>
    @else

    <form method="POST"
          action="{{ route('dashboard.eventos.resultados.update', $evento->id) }}">
        @csrf
        @method('PUT')

        <div class="table-panel p-0 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle"
                       id="tabla-resultados">
                    <thead style="background:#0d0d14; border-bottom:2px solid var(--fa-rojo);">
                        <tr>
                            <th class="ps-3" style="width:60px;">POS</th>
                            <th>Piloto</th>
                            <th>Escudería</th>
                            <th style="width:160px;">Diferencia</th>
                            <th class="text-center" style="width:60px;">Pole</th>
                            <th class="text-center" style="width:80px;">V. Rápida</th>
                            <th class="text-center" style="width:60px;">DNF</th>
                            <th class="text-center" style="width:80px;">Pts est.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inscripciones as $inscripcion)
                        @php
                            $res = $resultadosExistentes->get($inscripcion->id);
                        @endphp
                        <tr class="fila-resultado" data-inscripcion="{{ $inscripcion->id }}">

                            {{-- Posición --}}
                            <td class="ps-3">
                                <input type="number"
                                       name="pilotos[{{ $inscripcion->id }}][posicion]"
                                       class="form-control form-control-sm bg-dark text-white border-secondary input-posicion"
                                       style="width:60px;"
                                       min="1" max="99"
                                       value="{{ $res && !$res->dnf ? $res->posicion : '' }}"
                                       placeholder="—">
                            </td>

                            {{-- Piloto --}}
                            <td>
                                <div class="fw-bold">{{ $inscripcion->piloto->gamertag }}</div>
                                <div class="text-secondary small">
                                    {{ $inscripcion->piloto->nacionalidad ?? '' }}
                                </div>
                            </td>

                            {{-- Escudería --}}
                            <td class="text-secondary small">
                                {{ $inscripcion->escuderia->nombre }}
                            </td>

                            {{-- Diferencia --}}
                            <td>
                                <input type="text"
                                       name="pilotos[{{ $inscripcion->id }}][diferencia]"
                                       class="form-control form-control-sm bg-dark text-white border-secondary"
                                       value="{{ $res && !$res->dnf ? ($res->diferencia !== 'DNF' ? $res->diferencia : '') : '' }}"
                                       placeholder="Ej: +5.432s">
                            </td>

                            {{-- Pole --}}
                            <td class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input type="radio"
                                           name="pilotos_pole"
                                           value="{{ $inscripcion->id }}"
                                           class="form-check-input input-pole"
                                           {{ $res && $res->pts_pole > 0 ? 'checked' : '' }}>
                                    <input type="hidden"
                                           name="pilotos[{{ $inscripcion->id }}][pole]"
                                           class="hidden-pole"
                                           value="">
                                </div>
                            </td>

                            {{-- Vuelta rápida --}}
                            <td class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input type="radio"
                                           name="pilotos_vuelta_rap"
                                           value="{{ $inscripcion->id }}"
                                           class="form-check-input input-vr"
                                           {{ $res && $res->pts_vuelta_rap > 0 ? 'checked' : '' }}>
                                    <input type="hidden"
                                           name="pilotos[{{ $inscripcion->id }}][vuelta_rapida]"
                                           class="hidden-vr"
                                           value="">
                                </div>
                            </td>

                            {{-- DNF --}}
                            <td class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input type="checkbox"
                                           name="pilotos[{{ $inscripcion->id }}][dnf]"
                                           value="1"
                                           class="form-check-input input-dnf"
                                           {{ $res && $res->dnf ? 'checked' : '' }}>
                                </div>
                            </td>

                            {{-- Puntos estimados (JS) --}}
                            <td class="text-center">
                                <span class="fw-bold text-danger pts-estimados">
                                    {{ $res ? ($res->pts_carrera + $res->pts_pole + $res->pts_vuelta_rap) : '—' }}
                                </span>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-save me-1"></i>Guardar resultados
            </button>
            <a href="{{ route('dashboard.eventos.index') }}"
               class="btn btn-outline-secondary">Cancelar</a>
        </div>

    </form>
    @endif

@endsection

@push('scripts')
<script>
// Sistema de puntos FA
const PUNTOS_FA = {1:25,2:18,3:15,4:12,5:10,6:8,7:6,8:4,9:2,10:1};

// Sincronizar radio de pole → hidden
document.querySelectorAll('.input-pole').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.hidden-pole').forEach(h => h.value = '');
        if (radio.checked) {
            const fila = radio.closest('tr');
            fila.querySelector('.hidden-pole').value = '1';
        }
        actualizarTodosLosPuntos();
    });
});

// Sincronizar radio de vuelta rápida → hidden
document.querySelectorAll('.input-vr').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.hidden-vr').forEach(h => h.value = '');
        if (radio.checked) {
            const fila = radio.closest('tr');
            fila.querySelector('.hidden-vr').value = '1';
        }
        actualizarTodosLosPuntos();
    });
});

// DNF deshabilita posición y diferencia
document.querySelectorAll('.input-dnf').forEach(chk => {
    chk.addEventListener('change', () => {
        const fila = chk.closest('tr');
        const inputPos  = fila.querySelector('.input-posicion');
        const inputDif  = fila.querySelector('input[name*="diferencia"]');

        if (chk.checked) {
            inputPos.value    = '';
            inputPos.disabled = true;
            inputDif.value    = 'DNF';
            inputDif.disabled = true;
        } else {
            inputPos.disabled = false;
            inputDif.disabled = false;
            inputDif.value    = '';
        }
        actualizarPuntosFila(fila);
    });
    // Aplicar estado inicial
    if (chk.checked) {
        const fila = chk.closest('tr');
        fila.querySelector('.input-posicion').disabled = true;
        const inputDif = fila.querySelector('input[name*="diferencia"]');
        inputDif.disabled = true;
    }
});

// Recalcular puntos al cambiar posición
document.querySelectorAll('.input-posicion').forEach(input => {
    input.addEventListener('input', () => {
        actualizarPuntosFila(input.closest('tr'));
    });
});

function actualizarPuntosFila(fila) {
    const pos       = parseInt(fila.querySelector('.input-posicion').value) || 0;
    const esDnf     = fila.querySelector('.input-dnf').checked;
    const esPole    = fila.querySelector('.input-pole').checked;
    const esVR      = fila.querySelector('.input-vr').checked;

    let pts = 0;
    if (!esDnf && pos > 0) {
        pts += PUNTOS_FA[pos] || 0;
    }
    if (esPole) pts += 2;
    if (esVR && !esDnf && pos > 0 && pos <= 10) pts += 1;

    fila.querySelector('.pts-estimados').textContent = esDnf ? '0' : (pts || '—');
}

function actualizarTodosLosPuntos() {
    document.querySelectorAll('.fila-resultado').forEach(fila => {
        actualizarPuntosFila(fila);
    });
}

// Inicializar sincronización de radios al cargar
document.querySelectorAll('.input-pole:checked').forEach(radio => {
    radio.closest('tr').querySelector('.hidden-pole').value = '1';
});
document.querySelectorAll('.input-vr:checked').forEach(radio => {
    radio.closest('tr').querySelector('.hidden-vr').value = '1';
});
</script>
@endpush