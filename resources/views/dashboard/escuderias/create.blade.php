@extends('layouts.dashboard')

@section('title', 'Nueva Escudería — Panel')
@section('page-title', 'Nueva escudería')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        <a href="{{ route('dashboard.escuderias.index') }}"
           class="btn btn-sm btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>

        <div class="table-panel p-4">
            <form method="POST" action="{{ route('dashboard.escuderias.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Nombre *</label>
                    <input type="text" name="nombre"
                           class="form-control bg-dark text-white border-secondary @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Descripción</label>
                    <textarea name="descripcion" rows="4"
                              class="form-control bg-dark text-white border-secondary @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">URL del logo</label>
                    <input type="url" name="logo_url"
                           class="form-control bg-dark text-white border-secondary @error('logo_url') is-invalid @enderror"
                           value="{{ old('logo_url') }}"
                           placeholder="https://...">
                    @error('logo_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-plus-lg me-1"></i>Crear escudería
                    </button>
                    <a href="{{ route('dashboard.escuderias.index') }}"
                       class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection