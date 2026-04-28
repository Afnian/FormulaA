<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fórmula A — Liga de Simulación F1')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --fa-rojo:    #e10600;
            --fa-negro:   #15151e;
            --fa-gris:    #38383f;
            --fa-blanco:  #ffffff;
            --fa-dorado:  #ffd700;
        }

        body {
            background-color: var(--fa-negro);
            color: var(--fa-blanco);
            font-family: 'Segoe UI', sans-serif;
        }

        /* ── Navbar ── */
        .navbar-fa {
            background-color: var(--fa-negro);
            border-bottom: 3px solid var(--fa-rojo);
        }
        .navbar-fa .navbar-brand {
            color: var(--fa-rojo) !important;
            font-weight: 900;
            font-size: 1.5rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .navbar-fa .nav-link {
            color: var(--fa-blanco) !important;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            transition: color 0.2s;
        }
        .navbar-fa .nav-link:hover,
        .navbar-fa .nav-link.active {
            color: var(--fa-rojo) !important;
        }

        /* ── Cards ── */
        .card-fa {
            background-color: var(--fa-gris);
            border: none;
            border-radius: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-fa:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(225, 6, 0, 0.3);
        }

        /* ── Sección destacada ── */
        .noticia-destacada {
            background: linear-gradient(135deg, var(--fa-gris) 0%, #1e1e2e 100%);
            border-left: 4px solid var(--fa-rojo);
            border-radius: 8px;
        }

        /* ── Podio ── */
        .podio-pos {
            font-size: 3rem;
            font-weight: 900;
            line-height: 1;
        }
        .podio-p1 { color: var(--fa-dorado); }
        .podio-p2 { color: #c0c0c0; }
        .podio-p3 { color: #cd7f32; }

        /* ── Countdown ── */
        .countdown-box {
            background-color: var(--fa-rojo);
            border-radius: 8px;
            padding: 8px 16px;
            text-align: center;
            min-width: 70px;
        }
        .countdown-box .num {
            font-size: 2rem;
            font-weight: 900;
            line-height: 1;
        }
        .countdown-box .lbl {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ── Footer ── */
        footer {
            background-color: #0d0d14;
            border-top: 2px solid var(--fa-gris);
        }

        /* ── Badge rol ── */
        .badge-admin  { background-color: var(--fa-rojo); }
        .badge-editor { background-color: #6f42c1; }
        .badge-piloto { background-color: #0d6efd; }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="navbar navbar-expand-lg navbar-fa sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-flag-fill me-1"></i> FÓRMULA A
        </a>
        <button class="navbar-toggler border-secondary" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('noticias.*') ? 'active' : '' }}"
                        href="{{ route('noticias.index') }}">Noticias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('calendario.*') ? 'active' : '' }}"
                        href="{{ route('calendario.index') }}">Calendario</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('clasificaciones.*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">Clasificaciones</a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li>
                            <a class="dropdown-item" href="{{ route('clasificaciones.pilotos') }}">
                                <i class="bi bi-person-fill me-2"></i>Mundial de Pilotos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('clasificaciones.constructores') }}">
                                <i class="bi bi-shield-fill me-2"></i>Mundial de Constructores
                            </a>
                        </li>
                    </ul>
                </li>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('escuderias.*') ? 'active' : '' }}"
                        href="{{ route('escuderias.index') }}">Escuderías</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('formula-b.*') ? 'active' : '' }}"
                        href="{{ route('formula-b.index') }}">Fórmula B</a>
                </li>
            </ul>

            {{-- Acciones de usuario --}}
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Acceder
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-danger ms-2" href="{{ route('register') }}">
                            Registrarse
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#"
                           data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->nombre }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                            @if(Auth::user()->hasAnyRole(['admin','editor']))
                                <li>
                                    <a class="dropdown-item" href="/dashboard">
                                        <i class="bi bi-speedometer2 me-2"></i>Panel admin
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->hasRole('piloto'))
                                <li>
                                    <a class="dropdown-item" href="{{ route('perfil.show') }}">
                                        <i class="bi bi-person me-2"></i>Mi perfil
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

{{-- ── Contenido principal ── --}}
<main class="py-4">
    {{-- Mensajes flash --}}
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-info-circle-fill"></i>
            {{ session('info') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
    @yield('content')
</main>

{{-- ── Footer ── --}}
<footer class="py-4 mt-5">
    <div class="container text-center">
        <p class="mb-1 fw-bold text-danger">FÓRMULA A</p>
        <p class="text-secondary small mb-0">
            Liga de Simulación F1 · Basada en F1 25
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>