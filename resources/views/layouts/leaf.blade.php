<!DOCTYPE html>
<html lang="es" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name', 'LeafLog'))</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body x-data="searchData()">
        <header id="main-hdr">
            <div class="marca">
                <a href="{{ route('plantas.index') }}" class="marca-link">
                    <div class="marca-ico"><i class="fas fa-leaf"></i></div>
                    <div class="marca-txt">
                        <strong>LeafLog</strong>
                        <span>Etnobotánica · Fusagasugá</span>
                    </div>
                </a>
            </div>

            <form action="{{ route('buscar') }}" method="GET" class="search-wrap" style="position:relative" x-on:submit="open=false">
                <i class="fas fa-search"></i>
                <input
                    type="text"
                    name="q"
                    x-model="q"
                    x-on:input.debounce.300ms="buscar()"
                    x-on:keydown.escape="open=false"
                    placeholder="Buscar planta, receta, animal, síntoma…"
                    autocomplete="off"
                >
                <div x-show="open" x-cloak
                     class="search-results"
                     x-on:click.outside="open=false">
                    <template x-for="(grupo, clave) in results" :key="clave">
                        <div class="search-group">
                            <div class="search-group-title" x-text="clave"></div>
                            <template x-for="item in grupo" :key="item.tipo + item.url">
                                <a :href="item.url" class="search-item">
                                    <i class="fas fa-circle" style="font-size:.45rem"></i>
                                    <div>
                                        <div class="search-item-title" x-text="item.titulo"></div>
                                        <small x-show="item.sub" x-text="item.sub"></small>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>
                    <div x-show="Object.keys(results).length === 0 && q.length" class="search-empty">
                        Sin resultados para «<span x-text="q"></span>»
                    </div>
                    <div class="search-footer" x-show="q.length">
                        <a :href="'{{ route('buscar') }}?q=' + encodeURIComponent(q)" class="search-footer-link">
                            <i class="fas fa-search"></i> Ver todos los resultados
                        </a>
                        <a href="{{ route('tratamientos.index') }}" class="search-footer-link">
                            <i class="fas fa-heartbeat"></i> Tratamientos
                        </a>
                    </div>
                </div>
            </form>

            <div class="hdr-right">
                <a href="{{ route('tratamientos.index') }}" class="btn-hdr">
                    <i class="fas fa-heartbeat"></i> Tratamientos
                </a>
                <button class="theme-btn" id="theme-btn" title="Claro / Oscuro">
                    <i class="fas fa-moon"></i>
                </button>
                @auth
                    @if(auth()->user()->hasAnyRole(['admin', 'moderador']))
                        <a href="{{ route('admin.dashboard') }}" class="btn-hdr">
                            <i class="fas fa-th-large"></i> Admin
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="btn-hdr">
                        <i class="fas fa-user"></i> {{ auth()->user()->name }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-hdr btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Ingresar
                    </a>
                @endauth
            </div>
        </header>

        <main style="min-height:70vh">
            @if (session('status'))
                <div class="flash" x-data="{ show: true }" x-show="show" x-init="setTimeout(()=>show=false,3000)">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                </div>
            @endif
            @yield('content')
        </main>

        <footer class="footer">
            <strong>LeafLog</strong> · Plataforma digital de catalogación etnobotánica · Fusagasugá, Cundinamarca
        </footer>

        <script>
            function searchData() {
                return {
                    q: '',
                    open: false,
                    results: {},
                    async buscar() {
                        if (this.q.trim().length < 1) { this.results = {}; this.open = false; return; }
                        const resp = await fetch('{{ route("buscar.autocompletar") }}?q=' + encodeURIComponent(this.q), {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await resp.json();
                        this.results = {
                            ...(data.plantas.length ? { 'Plantas': data.plantas } : {}),
                            ...(data.recetas.length ? { 'Recetas': data.recetas } : {}),
                            ...(data.animales.length ? { 'Animales': data.animales } : {}),
                            ...(data.tratamientos.length ? { 'Tratamientos': data.tratamientos } : {}),
                        };
                        this.open = true;
                    }
                }
            }

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
