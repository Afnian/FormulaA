@extends('layouts.app')

@section('title', 'Solicitud de Inscripción — Fórmula A')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-7">

            {{-- Botón volver --}}
            <a href="{{ route('perfil.show') }}" class="btn btn-sm btn-outline-secondary mb-4">
                <i class="bi bi-arrow-left me-1"></i>Volver a mi perfil
            </a>

            {{-- Cabecera --}}
            <div class="text-center mb-5">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                     style="width:80px; height:80px; background:#1e1e2e; border:3px solid var(--fa-rojo);">
                    <i class="bi bi-send-fill text-danger" style="font-size:2rem;"></i>
                </div>
                <h2 class="fw-bold">Solicitud de inscripción</h2>
                <p class="text-secondary">
                    Envía tu solicitud para participar en la Fórmula A.
                    El administrador la revisará y te notificará el resultado.
                </p>
            </div>

            {{-- Alerta si fue rechazada --}}
            @if($solicitud && $solicitud->estado === 'rechazada')
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-x-circle-fill fs-5"></i>
                <div>
                    Tu solicitud anterior fue <strong>rechazada</strong>
                    el {{ $solicitud->updated_at->format('d M Y') }}.
                    Puedes enviar una nueva solicitud.
                </div>
            </div>
            @endif

            {{-- Card con info y formulario --}}
            <div class="card-fa p-4">

                {{-- Qué implica la solicitud --}}
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-info-circle me-2 text-danger"></i>¿Qué incluye la solicitud?
                </h5>
                <ul class="list-unstyled text-secondary mb-4">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Participar en las carreras de la temporada activa
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Ser asignado a una escudería por el administrador
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Aparecer en las clasificaciones y resultados oficiales
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Acceso a tu historial completo de carreras
                    </li>
                </ul>

                <hr style="border-color:#38383f;">

                {{-- Datos del solicitante (solo lectura) --}}
                <h5 class="fw-bold mb-3 mt-3">
                    <i class="bi bi-person me-2 text-danger"></i>Tus datos
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                            Nombre
                        </label>
                        <div class="fw-bold">{{ Auth::user()->nombre }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                            Email
                        </label>
                        <div class="fw-bold">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                            Gamertag
                        </label>
                        <div class="fw-bold">{{ Auth::user()->piloto->gamertag }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small text-uppercase" style="letter-spacing:1px;">
                            Nacionalidad
                        </label>
                        <div class="fw-bold">
                            {{ Auth::user()->piloto->nacionalidad ?? 'Sin especificar' }}
                        </div>
                    </div>
                </div>

                <hr style="border-color:#38383f;">

                {{-- Aviso --}}
                <div class="alert alert-secondary d-flex gap-2 align-items-start mt-3">
                    <i class="bi bi-exclamation-triangle-fill text-warning mt-1"></i>
                    <div class="small">
                        Al enviar esta solicitud confirmas que dispones del videojuego
                        <strong>F1 25</strong> y que puedes participar activamente
                        en la liga. Solo puedes tener una solicitud activa a la vez.
                    </div>
                </div>

                {{-- Formulario --}}
                <form method="POST" action="{{ route('inscripcion.store') }}" class="mt-4">
                    @csrf
                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger btn-lg">
                            <i class="bi bi-send me-2"></i>Enviar solicitud de inscripción
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>

</div>
@endsection