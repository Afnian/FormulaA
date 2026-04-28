@extends('layouts.dashboard')

@section('title', 'Editar Evento — Panel')
@section('page-title', 'Editar evento')
@section('page-subtitle', $evento->nombre)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <a href="{{ route('dashboard.eventos.index') }}"
           class="btn btn-sm btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>

        <div class="table-panel p-4">
            <form method="POST"
                  action="{{ route('dashboard.eventos.update', $evento->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Nombre del evento *</label>
                    <input type="text" name="nombre"
                           class="form-control bg-dark text-white border-secondary @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $evento->nombre) }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small text-uppercase"
                               style="letter-spacing:1px;">Temporada *</label>
                        <select name="id_temporada"
                                class="form-select bg-dark text-white border-secondary @error('id_temporada') is-invalid @enderror"
                                required>
                            @foreach($temporadas as $temporada)
                                <option value="{{ $temporada->id }}"
                                    {{ old('id_temporada', $evento->id_temporada) == $temporada->id ? 'selected' : '' }}>
                                    {{ $temporada->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_temporada')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small text-uppercase"
                               style="letter-spacing:1px;">Ronda *</label>
                        <input type="number" name="ronda"
                               class="form-control bg-dark text-white border-secondary @error('ronda') is-invalid @enderror"
                               value="{{ old('ronda', $evento->ronda) }}"
                               min="1" max="99" required>
                        @error('ronda')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Circuito *</label>
                    <select name="id_circuito"
                            class="form-select bg-dark text-white border-secondary @error('id_circuito') is-invalid @enderror"
                            required>
                        @foreach($circuitos as $circuito)
                            <option value="{{ $circuito->id }}"
                                {{ old('id_circuito', $evento->id_circuito) == $circuito->id ? 'selected' : '' }}>
                                {{ $circuito->nombre }} · {{ $circuito->pais }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_circuito')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Fecha y hora *</label>
                    <input type="datetime-local" name="fecha"
                           class="form-control bg-dark text-white border-secondary @error('fecha') is-invalid @enderror"
                           value="{{ old('fecha', $evento->fecha->format('Y-m-d\TH:i')) }}"
                           required>
                    @error('fecha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="completado" value="1"
                               class="form-check-input" id="completado"
                               {{ old('completado', $evento->completado) ? 'checked' : '' }}>
                        <label class="form-check-label text-secondary" for="completado">
                            Marcar como completado
                            <small class="d-block" style="font-size:0.75rem;">
                                Habilitará la carga de resultados
                            </small>
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="bi bi-save me-1"></i>Guardar cambios
                    </button>
                    <a href="{{ route('dashboard.eventos.index') }}"
                       class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection