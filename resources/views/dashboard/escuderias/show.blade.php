@extends('layouts.dashboard')

@section('title', 'Gestionar Pilotos — Panel')
@section('page-title', 'Gestionar pilotos')
@section('page-subtitle', $escuderia->nombre)

@section('content')

    <a href="{{ route('dashboard.escuderias.index') }}"
       class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-1"></i>Volver a escuderías
    </a>

    <div class="row g-4">

        {{-- ── Pilotos actuales ── --}}
        <div class="col-lg-7">
            <div class="table-panel">
                <div class="table-panel-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-people-fill me-2 text-danger"></i>
                        Pilotos inscritos
                    </h6>
                </div>

                @if($inscripciones->isEmpty())
                    <div class="p-4 text-center text-secondary">
                        <i class="bi bi-person-x fs-2 mb-2"></i>
                        <p class="mb-0">No hay pilotos asignados aún.</p>
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead style="background:#0d0d14;">
                            <tr>
                                <th class="ps-3">Piloto</th>
                                <th>Temporada</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center pe-3">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inscripciones as $insc)
                            @php
                                $badgeTipo = match($insc->tipo) {
                                    'oficial'  => 'bg-danger',
                                    'reserva'  => 'bg-warning text-dark',
                                    'academia' => 'bg-secondary',
                                    default    => 'bg-secondary'
                                };
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold">{{ $insc->piloto->gamertag }}</div>
                                    <div class="text-secondary small">
                                        {{ $insc->piloto->usuario->nombre }}
                                    </div>
                                </td>
                                <td class="text-secondary small">
                                    {{ $insc->temporada->nombre }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeTipo }}">
                                        {{ ucfirst($insc->tipo) }}
                                    </span>
                                </td>
                                <td class="text-center pe-3">
                                    <form method="POST"
                                          action="{{ route('dashboard.escuderias.pilotos.destroy', [$escuderia->id, $insc->id]) }}"
                                          onsubmit="return confirm('¿Quitar a {{ $insc->piloto->gamertag }} de la escudería?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-person-dash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Formulario asignar piloto ── --}}
        <div class="col-lg-5">
            <div class="table-panel p-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-person-plus me-2 text-danger"></i>
                    Asignar piloto
                </h6>

                <form method="POST"
                      action="{{ route('dashboard.escuderias.pilotos.store', $escuderia->id) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-secondary small text-uppercase"
                               style="letter-spacing:1px;">Piloto *</label>
                        <select name="id_piloto"
                                class="form-select bg-dark text-white border-secondary @error('id_piloto') is-invalid @enderror"
                                required>
                            <option value="">— Selecciona piloto —</option>
                            @foreach($pilotos as $piloto)
                                <option value="{{ $piloto->id }}"
                                    {{ old('id_piloto') == $piloto->id ? 'selected' : '' }}>
                                    {{ $piloto->gamertag }}
                                    ({{ $piloto->usuario->nombre }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_piloto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
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

                    <div class="mb-4">
                        <label class="form-label text-secondary small text-uppercase"
                               style="letter-spacing:1px;">Tipo *</label>
                        <select name="tipo"
                                class="form-select bg-dark text-white border-secondary @error('tipo') is-invalid @enderror"
                                required>
                            <option value="oficial"  {{ old('tipo') === 'oficial'  ? 'selected' : '' }}>Oficial</option>
                            <option value="reserva"  {{ old('tipo') === 'reserva'  ? 'selected' : '' }}>Reserva</option>
                            <option value="academia" {{ old('tipo') === 'academia' ? 'selected' : '' }}>Academia</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-person-plus me-1"></i>Asignar piloto
                    </button>
                </form>
            </div>
        </div>

    </div>

@endsection