<!DOCTYPE html>
<html lang="es" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Admin · LeafLog')</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body>
        <header id="main-hdr">
            <div class="marca">
                <a href="{{ route('plantas.index') }}" class="marca-link">
                    <div class="marca-ico"><i class="fas fa-leaf"></i></div>
                    <div class="marca-txt">
                        <strong>LeafLog</strong>
                        <span>Panel de administración</span>
                    </div>
                </a>
            </div>
            <div class="hdr-right">
                <button class="theme-btn" id="theme-btn" title="Claro / Oscuro">
                    <i class="fas fa-moon"></i>
                </button>
                <a href="{{ route('plantas.index') }}" class="btn-hdr">
                    <i class="fas fa-globe"></i> Ver sitio
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-hdr"><i class="fas fa-sign-out-alt"></i> Salir</button>
                </form>
            </div>
        </header>

        <div class="admin-wrap">
            <aside class="admin-sidebar">
                <div class="brand"><i class="fas fa-leaf"></i> LeafLog</div>
                <nav class="admin-nav">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i> Resumen
                    </a>
                    <a href="{{ route('admin.plantas.index') }}" class="{{ request()->routeIs('admin.plantas*') ? 'active' : '' }}">
                        <i class="fas fa-leaf"></i> Plantas
                    </a>
                    <a href="{{ route('admin.recetas.index') }}" class="{{ request()->routeIs('admin.recetas*') ? 'active' : '' }}">
                        <i class="fas fa-utensils"></i> Recetas
                    </a>
                    <a href="{{ route('admin.animales.index') }}" class="{{ request()->routeIs('admin.animales*') ? 'active' : '' }}">
                        <i class="fas fa-paw"></i> Animales
                    </a>
                    <a href="{{ route('admin.tratamientos.index') }}" class="{{ request()->routeIs('admin.tratamientos*') ? 'active' : '' }}">
                        <i class="fas fa-heartbeat"></i> Tratamientos
                    </a>
                    <a href="{{ route('admin.categorias.index') }}" class="{{ request()->routeIs('admin.categorias*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i> Categorías
                    </a>
                    <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Usuarios
                    </a>
                    <a href="{{ route('aportes.index') }}">
                        <i class="fas fa-inbox"></i> Aportes
                    </a>
                </nav>
            </aside>
            <main class="admin-content">
                @if (session('status'))
                    <div class="flash" x-data="{ show: true }" x-show="show" x-init="setTimeout(()=>show=false,3000)">
                        <i class="fas fa-check-circle"></i> {{ session('status') }}
                    </div>
                @endif
                <h1 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 20px">@yield('heading', 'Panel')</h1>
                @yield('content')
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const themeBtn = document.getElementById('theme-btn');
                if (!themeBtn) return;
                const root = document.documentElement;
                const icon = themeBtn.querySelector('i');
                function applyTheme(theme) {
                    root.setAttribute('data-theme', theme);
                    icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                    try { localStorage.setItem('leaflog-theme', theme); } catch (e) {}
                }
                const saved = (() => { try { return localStorage.getItem('leaflog-theme'); } catch (e) { return null; } })();
                if (saved) applyTheme(saved);
                themeBtn.addEventListener('click', () => {
                    applyTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>
