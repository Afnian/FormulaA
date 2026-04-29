@extends('layouts.dashboard')

@section('title', 'Nueva Noticia — Panel')
@section('page-title', 'Nueva noticia')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <a href="{{ route('dashboard.noticias.index') }}"
           class="btn btn-sm btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>

        <div class="table-panel p-4">
            <form method="POST" action="{{ route('dashboard.noticias.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Título *</label>
                    <input type="text" name="titulo"
                           class="form-control bg-dark text-white border-secondary @error('titulo') is-invalid @enderror"
                           value="{{ old('titulo') }}"
                           placeholder="Título de la noticia" required>
                    @error('titulo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Contenido *</label>
                    <textarea name="contenido" rows="12"
                              class="form-control bg-dark text-white border-secondary @error('contenido') is-invalid @enderror"
                              placeholder="Escribe el contenido de la noticia..."
                              required>{{ old('contenido') }}</textarea>
                    @error('contenido')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small text-uppercase"
                               style="letter-spacing:1px;">Evento relacionado</label>
                        <select name="id_evento"
                                class="form-select bg-dark text-white border-secondary @error('id_evento') is-invalid @enderror">
                            <option value="">— Sin evento relacionado —</option>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}"
                                    {{ old('id_evento') == $evento->id ? 'selected' : '' }}>
                                    {{ $evento->nombre }} ({{ $evento->fecha->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_evento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small text-uppercase"
                               style="letter-spacing:1px;">Estado *</label>
                        <select name="estado"
                                class="form-select bg-dark text-white border-secondary @error('estado') is-invalid @enderror"
                                required>
                            <option value="borrador"
                                {{ old('estado', 'borrador') === 'borrador' ? 'selected' : '' }}>
                                Borrador
                            </option>
                            <option value="publicada"
                                {{ old('estado') === 'publicada' ? 'selected' : '' }}>
                                Publicada
                            </option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-plus-lg me-1"></i>Crear noticia
                    </button>
                    <a href="{{ route('dashboard.noticias.index') }}"
                       class="btn btn-outline-secondary">Cancelar</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection