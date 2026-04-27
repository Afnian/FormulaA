{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Fórmula A — Inicio')

@section('content')
<div class="container">

    {{-- ══════════════════════════════════════
         BLOQUE 1 — NOTICIAS
    ══════════════════════════════════════ --}}
    <section class="mb-5">
        <h2 class="fw-bold mb-4 text-uppercase" style="letter-spacing:2px;">
            <i class="bi bi-newspaper me-2 text-danger"></i>Últimas noticias
        </h2>

        @if($noticiaDestacada)
        <div class="row g-4">
            {{-- Noticia destacada --}}
            <div class="col-lg-7">
                <div class="noticia-destacada p-4 h-100 d-flex flex-column justify-content-end">
                    @if($noticiaDestacada->evento?->circuito?->imagen_url)
                        <img src="{{ $noticiaDestacada->evento->circuito->imagen_url }}"
                             alt="{{ $noticiaDestacada->evento->circuito->nombre }}"
                             class="w-100 mb-3 rounded"
                             style="height:220px; object-fit:cover;">
                    @else
                        <div class="w-100 mb-3 rounded d-flex align-items-center justify-content-center"
                             style="height:220px; background:#1e1e2e;">
                            <i class="bi bi-flag-fill text-danger" style="font-size:4rem;"></i>
                        </div>
                    @endif

                    <span class="badge bg-danger mb-2">DESTACADO</span>
                    <h3 class="fw-bold">{{ $noticiaDestacada->titulo }}</h3>
                    <p class="text-secondary small mb-3">
                        <i class="bi bi-person me-1"></i>{{ $noticiaDestacada->autor->nombre }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-calendar me-1"></i>
                        {{ $noticiaDestacada->publicado_en->format('d M Y') }}
                    </p>
                    <p class="text-light">
                        {{ Str::limit(strip_tags($noticiaDestacada->contenido), 180) }}
                    </p>
                    <a href="{{ route('noticias.show', $noticiaDestacada->id) }}"
                       class="btn btn-danger btn-sm mt-auto align-self-start">
                        Leer más <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            {{-- Noticias secundarias --}}
            <div class="col-lg-5 d-flex flex-column gap-4">
                @forelse($noticiasSecundarias as $noticia)
                <div class="card-fa p-3 flex-fill">
                    <span class="badge bg-secondary mb-2">
                        {{ $noticia->publicado_en->format('d M Y') }}
                    </span>
                    <h5 class="fw-bold">{{ $noticia->titulo }}</h5>
                    <p class="text-secondary small mb-2">
                        <i class="bi bi-person me-1"></i>{{ $noticia->autor->nombre }}
                    </p>
                    <p class="text-light small">
                        {{ Str::limit(strip_tags($noticia->contenido), 100) }}
                    </p>
                    <a href="{{ route('noticias.show', $noticia->id) }}"
                       class="btn btn-outline-danger btn-sm">Leer más</a>
                </div>
                @empty
                    <p class="text-secondary">No hay más noticias.</p>
                @endforelse
            </div>
        </div>
        @else
            <p class="text-secondary">No hay noticias publicadas aún.</p>
        @endif
    </section>

    {{-- ══════════════════════════════════════
         BLOQUE 2 — PRÓXIMO GRAN PREMIO
    ══════════════════════════════════════ --}}
    @if($proximoEvento)
    <section class="mb-5">
        <h2 class="fw-bold mb-4 text-uppercase" style="letter-spacing:2px;">
            <i class="bi bi-calendar-event me-2 text-danger"></i>Próximo Gran Premio
        </h2>
        <div class="card-fa p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-danger fw-bold mb-1 text-uppercase" style="letter-spacing:1px;">
                        Ronda {{ $proximoEvento->ronda }}
                    </p>
                    <h3 class="fw-bold mb-1">{{ $proximoEvento->nombre }}</h3>
                    <p class="text-secondary mb-1">
                        <i class="bi bi-geo-alt me-1"></i>
                        {{ $proximoEvento->circuito->nombre }} — {{ $proximoEvento->circuito->pais }}
                    </p>
                    <p class="text-secondary small">
                        <i class="bi bi-calendar me-1"></i>
                        {{ $proximoEvento->fecha->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-clock me-1"></i>
                        {{ $proximoEvento->fecha->format('H:i') }}h
                    </p>
                </div>
                <div class="col-md-6 text-center mt-3 mt-md-0">
                    <p class="text-secondary small mb-2 text-uppercase" style="letter-spacing:1px;">
                        Cuenta atrás
                    </p>
                    <div class="d-flex justify-content-center gap-2"
                         id="countdown"
                         data-fecha="{{ $proximoEvento->fecha->toISOString() }}">
                        <div class="countdown-box">
                            <div class="num" id="cd-dias">--</div>
                            <div class="lbl">Días</div>
                        </div>
                        <div class="countdown-box">
                            <div class="num" id="cd-horas">--</div>
                            <div class="lbl">Horas</div>
                        </div>
                        <div class="countdown-box">
                            <div class="num" id="cd-min">--</div>
                            <div class="lbl">Min</div>
                        </div>
                        <div class="countdown-box">
                            <div class="num" id="cd-seg">--</div>
                            <div class="lbl">Seg</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════
         BLOQUE 3 — PODIO ÚLTIMO EVENTO
    ══════════════════════════════════════ --}}
    @if($ultimoEvento && $podio->count())
    <section class="mb-5">
        <h2 class="fw-bold mb-4 text-uppercase" style="letter-spacing:2px;">
            <i class="bi bi-trophy me-2 text-danger"></i>Último resultado
        </h2>
        <div class="card-fa p-4">
            <p class="text-secondary mb-1 text-uppercase small" style="letter-spacing:1px;">
                Ronda {{ $ultimoEvento->ronda }} · {{ $ultimoEvento->circuito->pais }}
            </p>
            <h4 class="fw-bold mb-4">{{ $ultimoEvento->nombre }}</h4>

            <div class="row justify-content-center g-3">
                @foreach($podio as $resultado)
                @php
                    $clasePos = match($resultado->posicion) {
                        1 => 'podio-p1',
                        2 => 'podio-p2',
                        3 => 'podio-p3',
                        default => ''
                    };
                    $iconoPos = match($resultado->posicion) {
                        1 => 'bi-trophy-fill',
                        2 => 'bi-award-fill',
                        3 => 'bi-award',
                        default => ''
                    };
                @endphp
                <div class="col-md-4 text-center">
                    <div class="card-fa p-3">
                        <div class="podio-pos {{ $clasePos }}">
                            P{{ $resultado->posicion }}
                        </div>
                        <i class="bi {{ $iconoPos }} {{ $clasePos }} fs-3 my-2"></i>
                        <h5 class="fw-bold mb-0">
                            {{ $resultado->inscripcion->piloto->gamertag }}
                        </h5>
                        <p class="text-secondary small mb-1">
                            {{ $resultado->inscripcion->escuderia->nombre }}
                        </p>
                        <span class="badge bg-danger">
                            {{ $resultado->pts_carrera + $resultado->pts_pole + $resultado->pts_vuelta_rap }} pts
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</div>
@endsection

@push('scripts')
<script>
    const cdEl = document.getElementById('countdown');
    if (cdEl) {
        const target = new Date(cdEl.dataset.fecha).getTime();

        function actualizarCountdown() {
            const ahora = Date.now();
            const diff  = target - ahora;

            if (diff <= 0) {
                document.getElementById('cd-dias').textContent  = '00';
                document.getElementById('cd-horas').textContent = '00';
                document.getElementById('cd-min').textContent   = '00';
                document.getElementById('cd-seg').textContent   = '00';
                return;
            }

            const dias  = Math.floor(diff / (1000 * 60 * 60 * 24));
            const horas = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const min   = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seg   = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('cd-dias').textContent  = String(dias).padStart(2, '0');
            document.getElementById('cd-horas').textContent = String(horas).padStart(2, '0');
            document.getElementById('cd-min').textContent   = String(min).padStart(2, '0');
            document.getElementById('cd-seg').textContent   = String(seg).padStart(2, '0');
        }

        actualizarCountdown();
        setInterval(actualizarCountdown, 1000);
    }
</script>
@endpush