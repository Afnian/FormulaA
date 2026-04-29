@extends('layouts.dashboard')

@section('title', 'Nuevo Piloto — Panel')
@section('page-title', 'Nuevo piloto')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">

        <a href="{{ route('dashboard.pilotos.index') }}"
           class="btn btn-sm btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>

        <div class="table-panel p-4">
            <form method="POST" action="{{ route('dashboard.pilotos.store') }}">
                @csrf

                {{-- Gamertag --}}
                <div class="mb-3">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">Gamertag *</label>
                    <input type="text"
                           name="gamertag"
                           class="form-control bg-dark text-white border-secondary @error('gamertag') is-invalid @enderror"
                           value="{{ old('gamertag') }}"
                           placeholder="Ej: CarlosR44"
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
                           value="{{ old('nacionalidad') }}"
                           placeholder="Ej: Española">
                    @error('nacionalidad')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Usuario vinculado --}}
                <div class="mb-4">
                    <label class="form-label text-secondary small text-uppercase"
                           style="letter-spacing:1px;">
                        Vincular a usuario
                        <span class="text-secondary fw-normal">(opcional)</span>
                    </label>
                    <select name="id_usuario"
                            class="form-select bg-dark text-white border-secondary @error('id_usuario') is-invalid @enderror">
                        <option value="">— Sin usuario vinculado —</option>
                        @foreach($usuariosSinPiloto as $usuario)
                            <option value="{{ $usuario->id }}"
                                {{ old('id_usuario') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->nombre }} — {{ $usuario->email }}
                                ({{ ucfirst($usuario->rol) }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_usuario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-secondary small mt-1">
                        <i class="bi bi-info-circle me-1"></i>
                        Si vinculas a un usuario, su rol cambiará automáticamente a <strong>piloto</strong>.
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-plus-lg me-1"></i>Crear piloto
                    </button>
                    <a href="{{ route('dashboard.pilotos.index') }}"
                       class="btn btn-outline-secondary">Cancelar</a>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection