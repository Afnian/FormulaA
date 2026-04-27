@extends('layouts.app')

@section('title', 'Fórmula B — Fórmula A')

@section('content')
<div class="container">

    {{-- Cabecera --}}
    <div class="mb-4 d-flex align-items-center gap-3">
        <div>
            <h1 class="fw-bold text-uppercase mb-0" style="letter-spacing:2px;">
                <i class="bi bi-flag-fill me-2 text-warning"></i>Fórmula B
            </h1>
            @if($temporadaFB)
                <p class="text-secondary mb-0">{{ $temporadaFB->nombre }} · {{ $temporadaFB->anio }}</p>
            @endif
        </div>
        <span class="badge bg-warning text-dark fs-6 ms-auto">Categoría Junior</span>
    </div>

    @if(!$temporadaFB)
        <div class="alert alert-secondary">No hay temporada activa de Fórmula B.</div>
    @else

    {{-- ══ PRÓXIMO EVENTO ══ --}}
    @if($proximoEvento)
    <div class="card-fa p-3 mb-4" style="border-left:4px solid #ffc107;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="badge bg-warning text-dark mb-1">
                    <i class="bi bi-clock me-1"></i>Próxima carrera
                </span>
                <h5 class="fw-bold mb-1">{{ $proximoEvento->nombre }}</h5>
                <p class="text-secondary small mb-0">
                    <i class="bi bi-geo-alt me-1"></i>
                    {{ $proximoEvento->circuito->nombre }} · {{ $proximoEvento->circuito->pais }}
                </p>
                <p class="text-secondary small mb-0">
                    <i class="bi bi-calendar me-1"></i>
                    {{ $proximoEvento->fecha->isoFormat('D [de] MMMM [de] YYYY') }}
                </p>
            </div>
            <div class="col-md-6 text-center mt-3 mt-md-0">
                <p class="text-secondary small mb-2 text-uppercase" style="letter-spacing:1px;">
                    Cuenta atrás
                </p>
                <div class="d-flex justify-content-center gap-2"
                     id="countdown-fb"
                     data-fecha="{{ $proximoEvento->fecha->toISOString() }}">
                    <div class="countdown-box" style="background:#ffc107;">
                        <div class="num text-dark" id="fb-dias">--</div>
                        <div class="lbl text-dark">Días</div>
                    </div>
                    <div class="countdown-box" style="background:#ffc107;">
                        <div class="num text-dark" id="fb-horas">--</div>
                        <div class="lbl text-dark">Horas</div>
                    </div>
                    <div class="countdown-box" style="background:#ffc107;">
                        <div class="num text-dark" id="fb-min">--</div>
                        <div class="lbl text-dark">Min</div>
                    </div>
                    <div class="countdown-box" style="background:#ffc107;">
                        <div class="num text-dark" id="fb-seg">--</div>
                        <div class="lbl text-dark">Seg</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ══ CLASIFICACIONES ══ --}}
    <div class="row g-4 mb-4">

        {{-- Mundial de pilotos --}}
        <div class="col-lg-6">
            <div class="card-fa p-0 overflow-hidden h-100">
                <div class="p-3" style="background:#1e1e2e; border-bottom:2px solid #ffc107;">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-person-fill me-2 text-warning"></i>Mundial de Pilotos
                    </h5>
                </div>
                @if($clasificacionPilotos->isEmpty())
                    <p class="text-secondary p-3 mb-0">Sin datos aún.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead style="background:#15151e;">
                            <tr>
                                <th class="ps-3" style="width:50px;">POS</th>
                                <th>Piloto</th>
                                <th>Escudería</th>
                                <th class="text-center pe-3">PTS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clasificacionPilotos as $index => $item)
                            @php
                                $pos = $index + 1;
                                $clasePos = match($pos) {
                                    1 => 'pos-p1', 2 => 'pos-p2', 3 => 'pos-p3',
                                    default => 'text-white'
                                };
                                $claseFila = match($pos) {
                                    1 => 'fila-p1', 2 => 'fila-p2', 3 => 'fila-p3',
                                    default => ''
                                };
                            @endphp
                            <tr class="{{ $claseFila }}">
                                <td class="ps-3">
                                    <span class="fw-black {{ $clasePos }}">{{ $pos }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold small">{{ $item['piloto']->gamertag }}</div>
                                    <div class="text-secondary" style="font-size:0.7rem;">
                                        {{ $item['piloto']->nacionalidad ?? '—' }}
                                    </div>
                                </td>
                                <td class="text-secondary small">
                                    {{ $item['escuderia']->nombre }}
                                </td>
                                <td class="text-center pe-3">
                                    <span class="fw-black text-warning">{{ $item['puntos'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- Mundial de constructores --}}
        <div class="col-lg-6">
            <div class="card-fa p-0 overflow-hidden h-100">
                <div class="p-3" style="background:#1e1e2e; border-bottom:2px solid #ffc107;">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-shield-fill me-2 text-warning"></i>Mundial de Constructores
                    </h5>
                </div>
                @if($clasificacionConstructores->isEmpty())
                    <p class="text-secondary p-3 mb-0">Sin datos aún.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead style="background:#15151e;">
                            <tr>
                                <th class="ps-3" style="width:50px;">POS</th>
                                <th>Escudería</th>
                                <th class="text-center pe-3">PTS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clasificacionConstructores as $index => $item)
                            @php
                                $pos = $index + 1;
                                $clasePos = match($pos) {
                                    1 => 'pos-p1', 2 => 'pos-p2', 3 => 'pos-p3',
                                    default => 'text-white'
                                };
                                $claseFila = match($pos) {
                                    1 => 'fila-p1', 2 => 'fila-p2', 3 => 'fila-p3',
                                    default => ''
                                };
                            @endphp
                            <tr class="{{ $claseFila }}">
                                <td class="ps-3">
                                    <span class="fw-black {{ $clasePos }}">{{ $pos }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold small">{{ $item['escuderia']->nombre }}</div>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @foreach($item['pilotos'] as $gamertag)
                                            <span class="badge bg-secondary" style="font-size:0.65rem;">
                                                {{ $gamertag }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-center pe-3">
                                    <span class="fw-black text-warning">{{ $item['puntos'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ ÚLTIMO RESULTADO ══ --}}
    @if($ultimoEvento)
    <div class="mb-4">
        <h4 class="fw-bold text-uppercase mb-3" style="letter-spacing:1px;">
            <i class="bi bi-list-ol me-2 text-warning"></i>
            Último resultado · {{ $ultimoEvento->nombre }}
        </h4>
        @if($resultadosUltimo->isEmpty())
            <p class="text-secondary">Sin resultados cargados.</p>
        @else
        <div class="card-fa p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead style="background:#1e1e2e; border-bottom:2px solid #ffc107;">
                        <tr>
                            <th class="ps-3" style="width:60px;">POS</th>
                            <th>Piloto</th>
                            <th>Escudería</th>
                            <th class="text-center">Diferencia</th>
                            <th class="text-center pe-3">Puntos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultadosUltimo as $resultado)
                        @php
                            $claseFila = match($resultado->posicion) {
                                1 => 'fila-p1', 2 => 'fila-p2', 3 => 'fila-p3',
                                default => ''
                            };
                            $clasePos = match($resultado->posicion) {
                                1 => 'pos-p1', 2 => 'pos-p2', 3 => 'pos-p3',
                                default => 'text-white'
                            };
                        @endphp
                        <tr class="{{ $claseFila }}">
                            <td class="ps-3">
                                <span class="fw-black {{ $clasePos }}" style="font-size:1.1rem;">
                                    {{ $resultado->posicion }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold">
                                    {{ $resultado->inscripcion->piloto->gamertag }}
                                </div>
                            </td>
                            <td class="text-secondary">
                                {{ $resultado->inscripcion->escuderia->nombre }}
                            </td>
                            <td class="text-center text-secondary">
                                {{ $resultado->diferencia ?? '—' }}
                            </td>
                            <td class="text-center pe-3">
                                <span class="fw-bold text-warning">
                                    {{ $resultado->puntos_totales }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- ══ ÚLTIMAS NOTICIAS ══ --}}
    @if($noticias->count())
    <div class="mb-4">
        <h4 class="fw-bold text-uppercase mb-3" style="letter-spacing:1px;">
            <i class="bi bi-newspaper me-2 text-warning"></i>Últimas noticias
        </h4>
        <div class="row g-3">
            @foreach($noticias as $noticia)
            <div class="col-md-4">
                <div class="card-fa p-3 h-100 d-flex flex-column"
                     style="border-left:3px solid #ffc107;">
                    <span class="badge bg-warning text-dark mb-2 align-self-start">
                        {{ $noticia->publicado_en->format('d M Y') }}
                    </span>
                    <h6 class="fw-bold flex-grow-1">{{ $noticia->titulo }}</h6>
                    <p class="text-secondary small mb-2">
                        <i class="bi bi-person me-1"></i>{{ $noticia->autor->nombre }}
                    </p>
                    <a href="#" class="btn btn-sm btn-outline-warning align-self-start">
                        Leer más <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @endif {{-- fin @if($temporadaFB) --}}

</div>
@endsection

@push('styles')
<style>
    .fila-p1 { background-color: rgba(255, 215, 0, 0.08) !important; }
    .fila-p2 { background-color: rgba(192, 192, 192, 0.08) !important; }
    .fila-p3 { background-color: rgba(205, 127, 50, 0.08) !important; }
    .pos-p1  { color: #ffd700; }
    .pos-p2  { color: #c0c0c0; }
    .pos-p3  { color: #cd7f32; }
</style>
@endpush

@push('scripts')
<script>
    const fbEl = document.getElementById('countdown-fb');
    if (fbEl) {
        const target = new Date(fbEl.dataset.fecha).getTime();

        function actualizarFB() {
            const diff = target - Date.now();
            if (diff <= 0) {
                ['fb-dias','fb-horas','fb-min','fb-seg'].forEach(id => {
                    document.getElementById(id).textContent = '00';
                });
                return;
            }
            document.getElementById('fb-dias').textContent  = String(Math.floor(diff / 86400000)).padStart(2,'0');
            document.getElementById('fb-horas').textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2,'0');
            document.getElementById('fb-min').textContent   = String(Math.floor((diff % 3600000) / 60000)).padStart(2,'0');
            document.getElementById('fb-seg').textContent   = String(Math.floor((diff % 60000) / 1000)).padStart(2,'0');
        }

        actualizarFB();
        setInterval(actualizarFB, 1000);
    }
</script>
@endpush