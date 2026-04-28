@extends('layouts.dashboard')

@section('title', 'Nuevo Evento — Panel')
@section('page-title', 'Nuevo evento')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <a href="{{ route('dashboard.eventos.index') }}"
           class="btn btn-sm btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>

        <div class="table-panel p-4">
            <form method="POST" action="{{ route('dashboard.eventos.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Nombre del evento *</label>
                    <input type="text" name="nombre"
                           class="form-control bg-dark text-white border-secondary @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}"
                           placeholder="Ej: Gran Premio de España" required>
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
                            <option value="">— Selecciona temporada —</option>
                            @foreach($temporadas as $temporada)
                                <option value="{{ $temporada->id }}"
                                    {{ old('id_temporada') == $temporada->id ? 'selected' : '' }}>
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
                               value="{{ old('ronda') }}"
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
                        <option value="">— Selecciona circuito —</option>
                        @foreach($circuitos as $circuito)
                            <option value="{{ $circuito->id }}"
                                {{ old('id_circuito') == $circuito->id ? 'selected' : '' }}>
                                {{ $circuito->nombre }} · {{ $circuito->pais }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_circuito')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Fecha y hora *</label>
                    <input type="datetime-local" name="fecha"
                           class="form-control bg-dark text-white border-secondary @error('fecha') is-invalid @enderror"
                           value="{{ old('fecha') }}" required>
                    @error('fecha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-plus-lg me-1"></i>Crear evento
                    </button>
                    <a href="{{ route('dashboard.eventos.index') }}"
                       class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection