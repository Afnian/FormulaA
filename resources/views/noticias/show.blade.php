@extends('layouts.app')

@section('title', $noticia->titulo . ' — Fórmula A')

@section('content')
<div class="container">

    {{-- Botón volver --}}
    <a href="{{ route('noticias.index') }}" class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-1"></i>Volver a noticias
    </a>

    <div class="row g-5">

        {{-- ── Artículo principal ── --}}
        <div class="col-lg-8">

            {{-- Imagen de cabecera --}}
            @if($noticia->evento?->circuito?->imagen_url)
                <img src="{{ $noticia->evento->circuito->imagen_url }}"
                     alt="{{ $noticia->evento->circuito->nombre }}"
                     class="img-fluid rounded w-100 mb-4"
                     style="max-height:360px; object-fit:cover;">
            @else
                <div class="d-flex align-items-center justify-content-center rounded mb-4"
                     style="height:280px; background:#1e1e2e;">
                    <i class="bi bi-newspaper text-primary" style="font-size:6rem;"></i>
                </div>
            @endif

            {{-- Badges --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge bg-primary">NOTICIA</span>
                @if($noticia->evento)
                    <span class="badge bg-secondary">
                        <i class="bi bi-flag me-1"></i>{{ $noticia->evento->nombre }}
                    </span>
                @endif
            </div>

            {{-- Título --}}
            <h1 class="fw-bold mb-3" style="line-height:1.2;">
                {{ $noticia->titulo }}
            </h1>

            {{-- Metadatos --}}
            <div class="d-flex align-items-center gap-3 mb-4 pb-3"
                 style="border-bottom:1px solid #38383f;">
                <div class="d-flex align-items-center justify-content-center rounded-circle"
                     style="width:44px; height:44px; background:#1e1e2e; flex-shrink:0;">
                    <i class="bi bi-person-fill text-primary"></i>
                </div>
                <div>
                    <div class="fw-bold small">{{ $noticia->autor->nombre }}</div>
                    <div class="text-secondary small">
                        <i class="bi bi-calendar me-1"></i>
                        {{ $noticia->publicado_en->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </div>
                </div>
            </div>

            {{-- Contenido del artículo --}}
            <div class="text-light lh-lg" style="font-size:1.05rem;">
                {!! nl2br(e($noticia->contenido)) !!}
            </div>

            {{-- Evento relacionado --}}
            @if($noticia->evento)
            <div class="card-fa p-3 mt-5">
                <p class="text-secondary small text-uppercase mb-1" style="letter-spacing:1px;">
                    <i class="bi bi-link-45deg me-1"></i>Evento relacionado
                </p>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-bold mb-0">{{ $noticia->evento->nombre }}</h6>
                        <small class="text-secondary">
                            {{ $noticia->evento->circuito->nombre ?? '' }}
                            · {{ $noticia->evento->fecha->format('d M Y') }}
                        </small>
                    </div>
                    <a href="{{ route('calendario.show', $noticia->evento->id) }}"
                       class="btn btn-sm btn-outline-primary">
                        Ver resultado <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @endif

        </div>

        {{-- ── Sidebar: noticias relacionadas ── --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top:80px;">
                <h5 class="fw-bold text-uppercase mb-3" style="letter-spacing:1px;">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Más noticias
                </h5>

                @forelse($relacionadas as $rel)
                <div class="card-fa p-3 mb-3">
                    @if($rel->evento)
                        <span class="badge bg-secondary mb-1" style="font-size:0.65rem;">
                            {{ $rel->evento->nombre }}
                        </span>
                    @endif
                    <h6 class="fw-bold mb-1">
                        <a href="{{ route('noticias.show', $rel->id) }}"
                           class="text-white text-decoration-none">
                            {{ $rel->titulo }}
                        </a>
                    </h6>
                    <p class="text-secondary mb-0" style="font-size:0.75rem;">
                        <i class="bi bi-person me-1"></i>{{ $rel->autor->nombre }}
                        &nbsp;·&nbsp;
                        {{ $rel->publicado_en->format('d M Y') }}
                    </p>
                </div>
                @empty
                    <p class="text-secondary small">No hay más noticias.</p>
                @endforelse

                <a href="{{ route('noticias.index') }}"
                   class="btn btn-outline-primary btn-sm w-100 mt-2">
                    Ver todas las noticias
                </a>
            </div>
        </div>

    </div>

</div>
@endsection