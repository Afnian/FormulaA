@extends('layouts.dashboard')

@section('title', 'Editar Circuito — Panel')
@section('page-title', 'Editar circuito')
@section('page-subtitle', $circuito->nombre)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <a href="{{ route('dashboard.circuitos.index') }}"
           class="btn btn-sm btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>

        <div class="table-panel p-4">
            <form method="POST"
                  action="{{ route('dashboard.circuitos.update', $circuito->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Nombre *</label>
                    <input type="text" name="nombre"
                           class="form-control bg-dark text-white border-secondary @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $circuito->nombre) }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">País *</label>
                    <input type="text" name="pais"
                           class="form-control bg-dark text-white border-secondary @error('pais') is-invalid @enderror"
                           value="{{ old('pais', $circuito->pais) }}" required>
                    @error('pais')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Número de vueltas *</label>
                    <input type="number" name="num_vueltas"
                           class="form-control bg-dark text-white border-secondary @error('num_vueltas') is-invalid @enderror"
                           value="{{ old('num_vueltas', $circuito->num_vueltas) }}"
                           min="1" max="999" required>
                    @error('num_vueltas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">URL de imagen</label>
                    <input type="url" name="imagen_url"
                           class="form-control bg-dark text-white border-secondary @error('imagen_url') is-invalid @enderror"
                           value="{{ old('imagen_url', $circuito->imagen_url) }}"
                           placeholder="https://...">
                    @error('imagen_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="bi bi-save me-1"></i>Guardar cambios
                    </button>
                    <a href="{{ route('dashboard.circuitos.index') }}"
                       class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection