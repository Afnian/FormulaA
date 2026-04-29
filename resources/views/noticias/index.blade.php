@extends('layouts.app')

@section('title', 'Noticias — Fórmula A')

@section('content')
<div class="container">

    {{-- Cabecera --}}
    <div class="mb-5">
        <h1 class="fw-bold text-uppercase" style="letter-spacing:2px;">
            <i class="bi bi-newspaper me-2 text-primary"></i>Noticias
        </h1>
        <p class="text-secondary">Toda la actualidad de la Fórmula A y Fórmula B.</p>
    </div>

    @if($noticias->isEmpty())
        <div class="alert alert-secondary">No hay noticias publicadas aún.</div>
    @else

    {{-- Noticia destacada (la primera de la página) --}}
    @if($noticias->currentPage() === 1)
    @php $destacada = $noticias->first(); @endphp
    <div class="noticia-destacada p-4 mb-5">
        <div class="row align-items-center g-4">
            {{-- Imagen --}}
            <div class="col-md-5">
                @if($destacada->evento?->circuito?->imagen_url)
                    <img src="{{ $destacada->evento->circuito->imagen_url }}"
                         alt="{{ $destacada->evento->circuito->nombre }}"
                         class="img-fluid rounded w-100"
                         style="max-height:260px; object-fit:cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center rounded"
                         style="height:260px; background:#1e1e2e;">
                        <i class="bi bi-newspaper text-primary" style="font-size:5rem;"></i>
                    </div>
                @endif
            </div>

            {{-- Contenido --}}
            <div class="col-md-7">
                <span class="badge bg-primary mb-2">DESTACADO</span>
                @if($destacada->evento)
                    <span class="badge bg-secondary mb-2 ms-1">
                        <i class="bi bi-flag me-1"></i>{{ $destacada->evento->nombre }}
                    </span>
                @endif
                <h2 class="fw-bold mb-2">{{ $destacada->titulo }}</h2>
                <p class="text-secondary small mb-3">
                    <i class="bi bi-person me-1"></i>{{ $destacada->autor->nombre }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-calendar me-1"></i>
                    {{ $destacada->publicado_en->isoFormat('D [de] MMMM [de] YYYY') }}
                </p>
                <p class="text-light">
                    {{ Str::limit(strip_tags($destacada->contenido), 250) }}
                </p>
                <a href="{{ route('noticias.show', $destacada->id) }}"
                   class="btn btn-primary mt-2">
                    Leer artículo completo <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Resto de noticias --}}
    @php
        $restantes = $noticias->currentPage() === 1
            ? $noticias->skip(1)
            : $noticias;
    @endphp

    @if($restantes->count())
    <div class="row g-4 mb-4">
        @foreach($restantes as $noticia)
        <div class="col-md-6 col-lg-4">
            <div class="card-fa p-0 overflow-hidden h-100 d-flex flex-column">

                {{-- Imagen --}}
                @if($noticia->evento?->circuito?->imagen_url)
                    <img src="{{ $noticia->evento->circuito->imagen_url }}"
                         alt="{{ $noticia->evento->circuito->nombre }}"
                         style="height:160px; object-fit:cover; width:100%;">
                @else
                    <div class="d-flex align-items-center justify-content-center"
                         style="height:160px; background:#1e1e2e;">
                        <i class="bi bi-newspaper text-primary" style="font-size:3rem;"></i>
                    </div>
                @endif

                {{-- Contenido --}}
                <div class="p-3 d-flex flex-column flex-grow-1">
                    @if($noticia->evento)
                        <span class="badge bg-secondary mb-2 align-self-start">
                            <i class="bi bi-flag me-1"></i>{{ $noticia->evento->nombre }}
                        </span>
                    @endif

                    <h5 class="fw-bold flex-grow-1">{{ $noticia->titulo }}</h5>

                    <p class="text-secondary small mb-2">
                        <i class="bi bi-person me-1"></i>{{ $noticia->autor->nombre }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-calendar me-1"></i>
                        {{ $noticia->publicado_en->format('d M Y') }}
                    </p>

                    <p class="text-light small mb-3">
                        {{ Str::limit(strip_tags($noticia->contenido), 100) }}
                    </p>

                    <a href="{{ route('noticias.show', $noticia->id) }}"
                       class="btn btn-outline-primary btn-sm align-self-start mt-auto">
                        Leer más <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Paginación --}}
    @if($noticias->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination pagination-sm">
                {{-- Anterior --}}
                @if($noticias->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link bg-dark border-secondary text-secondary">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link bg-dark border-secondary text-white"
                           href="{{ $noticias->previousPageUrl() }}">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Páginas --}}
                @foreach($noticias->getUrlRange(1, $noticias->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $noticias->currentPage() ? 'active' : '' }}">
                        <a class="page-link {{ $page == $noticias->currentPage() ? 'bg-primary border-primary' : 'bg-dark border-secondary text-white' }}"
                           href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Siguiente --}}
                @if($noticias->hasMorePages())
                    <li class="page-item">
                        <a class="page-link bg-dark border-secondary text-white"
                           href="{{ $noticias->nextPageUrl() }}">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link bg-dark border-secondary text-secondary">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif

    @endif {{-- fin @if($noticias->isEmpty()) --}}

</div>
@endsection