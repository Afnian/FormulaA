@extends('layouts.dashboard')

@section('title', 'Editar Piloto — Panel')
@section('page-title', 'Editar piloto')
@section('page-subtitle', $piloto->gamertag)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">

        <a href="{{ route('dashboard.pilotos.index') }}"
           class="btn btn-sm btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>

        <div class="table-panel p-4">
            <form method="POST"
                  action="{{ route('dashboard.pilotos.update', $piloto->id) }}">
                @csrf
                @method('PUT')

                {{-- Gamertag --}}
                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Gamertag *</label>
                    <input type="text"
                           name="gamertag"
                           class="form-control bg-dark text-white border-secondary @error('gamertag') is-invalid @enderror"
                           value="{{ old('gamertag', $piloto->gamertag) }}"
                           required>
                    @error('gamertag')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nacionalidad --}}
                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Nacionalidad</label>
                    <input type="text"
                           name="nacionalidad"
                           class="form-control bg-dark text-white border-secondary @error('nacionalidad') is-invalid @enderror"
                           value="{{ old('nacionalidad', $piloto->nacionalidad) }}"
                           placeholder="Ej: Española">
                    @error('nacionalidad')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Usuario vinculado --}}
                <div class="mb-4">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">
                        Usuario vinculado
                        <span class="text-secondary fw-normal">(opcional)</span>
                    </label>
                    <select name="id_usuario"
                            class="form-select bg-dark text-white border-secondary @error('id_usuario') is-invalid @enderror">
                        <option value="">— Sin usuario vinculado —</option>
                        @foreach($usuariosSinPiloto as $usuario)
                            <option value="{{ $usuario->id }}"
                                {{ old('id_usuario', $piloto->id_usuario) == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->nombre }} — {{ $usuario->email }}
                                ({{ ucfirst($usuario->rol) }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_usuario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($piloto->usuario)
                    <div class="form-text text-secondary small mt-1">
                        <i class="bi bi-info-circle me-1"></i>
                        Actualmente vinculado a
                        <strong>{{ $piloto->usuario->nombre }}</strong>
                        ({{ $piloto->usuario->email }}).
                        Si cambias el usuario, el anterior volverá a rol espectador.
                    </div>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="bi bi-save me-1"></i>Guardar cambios
                    </button>
                    <a href="{{ route('dashboard.pilotos.index') }}"
                       class="btn btn-outline-secondary">Cancelar</a>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection