<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel — Fórmula A')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --fa-rojo:   #009de4;
            --fa-negro:  #15151e;
            --fa-gris:   #38383f;
            --fa-blanco: #ffffff;
            --sidebar-w: 260px;
        }

        body {
            background-color: #0d0d14;
            color: var(--fa-blanco);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background-color: var(--fa-negro);
            border-right: 2px solid var(--fa-gris);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 2px solid var(--fa-gris);
        }

        .sidebar-brand .brand-title {
            color: var(--fa-rojo);
            font-weight: 900;
            font-size: 1.2rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
        }

        .sidebar-brand .brand-sub {
            color: #888;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex-grow: 1;
        }

        .sidebar-section {
            padding: 0.5rem 1.25rem 0.25rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #555;
            font-weight: 600;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.25rem;
            color: #aaa;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            color: var(--fa-blanco);
            background-color: rgba(255,255,255,0.05);
            border-left-color: var(--fa-gris);
        }

        .sidebar-link.active {
            color: var(--fa-blanco);
            background-color: rgba(0, 157, 228, 0.1);
            border-left-color: var(--fa-rojo);
        }

        .sidebar-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--fa-gris);
        }

        /* ── Contenido principal ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background-color: var(--fa-negro);
            border-bottom: 1px solid var(--fa-gris);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .content-area {
            padding: 2rem 1.5rem;
            flex-grow: 1;
        }

        /* ── Cards de stats ── */
        .stat-card {
            background-color: var(--fa-negro);
            border: 1px solid var(--fa-gris);
            border-radius: 8px;
            padding: 1.25rem;
            transition: border-color 0.2s;
        }

        .stat-card:hover {
            border-color: var(--fa-rojo);
        }

        /* ── Tablas del panel ── */
        .table-panel {
            background-color: var(--fa-negro);
            border: 1px solid var(--fa-gris);
            border-radius: 8px;
            overflow: hidden;
        }

        .table-panel-header {
            padding: 1rem 1.25rem;
            border-bottom: 2px solid var(--fa-rojo);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* ── Badges de estado ── */
        .badge-pendiente { background-color: #ffc107; color: #000; }
        .badge-aceptada  { background-color: #198754; }
        .badge-rechazada { background-color: var(--fa-rojo); }
        .badge-borrador  { background-color: #6c757d; }
        .badge-publicada { background-color: #198754; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar      { display: none; }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <a href="{{ route('dashboard.index') }}" class="brand-title d-block">
            <i class="bi bi-flag-fill me-2"></i>Fórmula A
        </a>
        <span class="brand-sub">Panel de administración</span>
    </div>

    {{-- Navegación --}}
    <nav class="sidebar-nav">

        {{-- General --}}
        <div class="sidebar-section">General</div>
        <a href="{{ route('dashboard.index') }}"
           class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> Ver web pública
        </a>

        {{-- Competición --}}
        <div class="sidebar-section mt-3">Competición</div>
        <a href="{{ route('dashboard.temporadas.index') }}"
           class="sidebar-link {{ request()->routeIs('dashboard.temporadas.*') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i> Temporadas
        </a>
        <a href="{{ route('dashboard.circuitos.index') }}"
           class="sidebar-link {{ request()->routeIs('dashboard.circuitos.*') ? 'active' : '' }}">
            <i class="bi bi-map"></i> Circuitos
        </a>
        <a href="{{ route('dashboard.eventos.index') }}"
           class="sidebar-link {{ request()->routeIs('dashboard.eventos.*') ? 'active' : '' }}">
            <i class="bi bi-flag"></i> Eventos
        </a>

        {{-- Equipos --}}
        <div class="sidebar-section mt-3">Equipos</div>
        <a href="{{ route('dashboard.pilotos.index') }}"
           class="sidebar-link {{ request()->routeIs('dashboard.pilotos.*') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i> Pilotos
        </a>
        <a href="{{ route('dashboard.escuderias.index') }}"
           class="sidebar-link {{ request()->routeIs('dashboard.escuderias.*') ? 'active' : '' }}">
            <i class="bi bi-shield-fill"></i> Escuderías
        </a>

        {{-- Contenido --}}
        <div class="sidebar-section mt-3">Contenido</div>
        <a href="{{ route('dashboard.noticias.index') }}"
           class="sidebar-link {{ request()->routeIs('dashboard.noticias.*') ? 'active' : '' }}">
            <i class="bi bi-newspaper"></i> Noticias
        </a>

        {{-- Usuarios --}}
        @if(Auth::user()->hasRole('admin'))
        <div class="sidebar-section mt-3">Usuarios</div>
        <a href="{{ route('dashboard.solicitudes.index') }}"
           class="sidebar-link {{ request()->routeIs('dashboard.solicitudes.*') ? 'active' : '' }}">
            <i class="bi bi-person-check"></i> Solicitudes
            @php
                $pendientes = \App\Models\SolicitudAcceso::where('estado','pendiente')->count();
            @endphp
            @if($pendientes > 0)
                <span class="badge bg-warning text-dark ms-auto">{{ $pendientes }}</span>
            @endif
        </a>
        @endif

    </nav>

    {{-- Footer del sidebar --}}
    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="d-flex align-items-center justify-content-center rounded-circle"
                 style="width:32px; height:32px; background:#38383f; flex-shrink:0;">
                <i class="bi bi-person-fill" style="color:var(--fa-rojo);"></i>
            </div>
            <div class="overflow-hidden">
                <div class="small fw-bold text-truncate">{{ Auth::user()->nombre }}</div>
                <div style="font-size:0.65rem; color:#888;">
                    {{ ucfirst(Auth::user()->rol) }}
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                <i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión
            </button>
        </form>
    </div>
</aside>

{{-- ══ CONTENIDO PRINCIPAL ══ --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div>
            <span class="fw-bold">@yield('page-title', 'Dashboard')</span>
            <span class="text-secondary small ms-2">@yield('page-subtitle', '')</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge" style="background:var(--fa-rojo);">
                {{ ucfirst(Auth::user()->rol) }}
            </span>
        </div>
    </div>

    {{-- Mensajes flash --}}
    <div class="px-4 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill"></i>
                {{ session('info') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Área de contenido --}}
    <div class="content-area">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>