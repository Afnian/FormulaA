@extends('layouts.app')

@section('title', 'Consultas IA — Fórmula A')

@section('content')
<div class="container">

    {{-- Cabecera --}}
    <div class="mb-5">
        <h1 class="fw-bold text-uppercase" style="letter-spacing:2px;">
            <i class="bi bi-robot me-2 text-primary"></i>Consultas en lenguaje natural
        </h1>
        <p class="text-secondary">
            Pregunta cualquier cosa sobre la liga y la IA generará la consulta SQL automáticamente.
        </p>
    </div>

    {{-- Formulario --}}
    <div class="card-fa p-4 mb-4">
        <form method="POST" action="{{ route('ia.query') }}" id="form-ia">
            @csrf
            <div class="mb-3">
                <label class="form-label text-secondary small text-uppercase"
                       style="letter-spacing:1px;">
                    <i class="bi bi-chat-dots me-1"></i>Tu pregunta
                </label>
                <div class="d-flex gap-2">
                    <input type="text"
                           name="pregunta"
                           id="pregunta"
                           class="form-control bg-dark text-white border-secondary @error('pregunta') is-invalid @enderror"
                           value="{{ old('pregunta', $pregunta ?? '') }}"
                           placeholder="Ej: ¿Quién lidera el mundial de pilotos?"
                           autocomplete="off"
                           required>
                    <button type="submit" class="btn btn-primary px-4" id="btn-consultar">
                        <i class="bi bi-search me-1"></i>Consultar
                    </button>
                </div>
                @error('pregunta')
                    <div class="text-primary small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </form>

        {{-- Ejemplos de preguntas --}}
        <div class="mt-3">
            <p class="text-secondary small mb-2">
                <i class="bi bi-lightbulb me-1"></i>Ejemplos de preguntas:
            </p>
            <div class="d-flex flex-wrap gap-2">
                @foreach([
                    '¿Quién lidera el mundial de pilotos?',
                    '¿Cuáles son los resultados del último evento?',
                    '¿Qué piloto tiene más victorias?',
                    '¿Qué escudería suma más puntos?',
                    '¿Cuántos pilotos hay inscritos en la temporada activa?',
                    '¿Cuál es el próximo evento?',
                    '¿Qué piloto ha conseguido más poles?',
                ] as $ejemplo)
                <button type="button"
                        class="btn btn-sm btn-outline-secondary ejemplo-pregunta">
                    {{ $ejemplo }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Mensajes de error --}}
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Resultados --}}
    @if(isset($resultados))
    <div class="mb-4">

        {{-- SQL generado --}}
        @if(isset($sql))
        <div class="card-fa p-3 mb-3">
            <p class="text-secondary small text-uppercase mb-1" style="letter-spacing:1px;">
                <i class="bi bi-code-slash me-1"></i>Consulta SQL generada
            </p>
            <code class="text-success" style="font-size:0.85rem; word-break:break-all;">
                {{ $sql }}
            </code>
        </div>
        @endif

        {{-- Tabla de resultados --}}
        @if(empty($resultados))
            <div class="card-fa p-4 text-center">
                <i class="bi bi-inbox text-secondary fs-2 mb-2"></i>
                <p class="text-secondary mb-0">
                    La consulta no devolvió resultados.
                </p>
            </div>
        @else
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-table me-2 text-primary"></i>
                {{ count($resultados) }} resultado{{ count($resultados) !== 1 ? 's' : '' }}
            </h5>
            <span class="text-secondary small">
                Pregunta: <em>{{ $pregunta }}</em>
            </span>
        </div>

        <div class="card-fa p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead style="background:#1e1e2e; border-bottom:2px solid var(--fa-rojo);">
                        <tr>
                            @foreach($columnas as $columna)
                            <th class="ps-3 text-uppercase small" style="letter-spacing:1px;">
                                {{ str_replace('_', ' ', $columna) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultados as $fila)
                        <tr>
                            @foreach((array) $fila as $valor)
                            <td class="ps-3">
                                @if(is_null($valor))
                                    <span class="text-secondary">—</span>
                                @elseif(is_numeric($valor) && str_contains((string)$valor, '.'))
                                    {{ number_format((float)$valor, 2) }}
                                @else
                                    {{ $valor }}
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
    @endif

    {{-- Info de seguridad --}}
    <div class="card-fa p-3 mt-4">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-shield-check text-success fs-4 mt-1"></i>
            <div>
                <p class="fw-bold small mb-1">Módulo seguro</p>
                <p class="text-secondary small mb-0">
                    Solo se permiten consultas de lectura (SELECT).
                    Las operaciones de escritura, modificación o eliminación están bloqueadas.
                    Las consultas se validan antes de ejecutarse.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Rellenar el input con el ejemplo al hacer clic
    document.querySelectorAll('.ejemplo-pregunta').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('pregunta').value = btn.textContent.trim();
            document.getElementById('pregunta').focus();
        });
    });

    // Indicador de carga al enviar
    document.getElementById('form-ia').addEventListener('submit', () => {
        const btn = document.getElementById('btn-consultar');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Consultando...';
    });
</script>
@endpush