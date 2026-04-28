@extends('layouts.dashboard')

@section('title', 'Nueva Temporada — Panel')
@section('page-title', 'Nueva temporada')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <a href="{{ route('dashboard.temporadas.index') }}"
           class="btn btn-sm btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>

        <div class="table-panel p-4">
            <form method="POST" action="{{ route('dashboard.temporadas.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Nombre *</label>
                    <input type="text" name="nombre"
                           class="form-control bg-dark text-white border-secondary @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}"
                           placeholder="Ej: Fórmula A 2026" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Categoría *</label>
                    <select name="categoria"
                            class="form-select bg-dark text-white border-secondary @error('categoria') is-invalid @enderror"
                            required>
                        <option value="formula_a" {{ old('categoria') === 'formula_a' ? 'selected' : '' }}>
                            Fórmula A
                        </option>
                        <option value="formula_b" {{ old('categoria') === 'formula_b' ? 'selected' : '' }}>
                            Fórmula B
                        </option>
                    </select>
                    @error('categoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Año *</label>
                    <input type="number" name="anio"
                           class="form-control bg-dark text-white border-secondary @error('anio') is-invalid @enderror"
                           value="{{ old('anio', date('Y')) }}"
                           min="2020" max="2100" required>
                    @error('anio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="activa" value="1"
                               class="form-check-input"
                               id="activa"
                               {{ old('activa') ? 'checked' : '' }}>
                        <label class="form-check-label text-secondary" for="activa">
                            Marcar como temporada activa
                            <small class="d-block text-secondary" style="font-size:0.75rem;">
                                Desactivará las demás temporadas de la misma categoría
                            </small>
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-plus-lg me-1"></i>Crear temporada
                    </button>
                    <a href="{{ route('dashboard.temporadas.index') }}"
                       class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection