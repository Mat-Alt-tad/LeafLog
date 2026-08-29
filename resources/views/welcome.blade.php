<!DOCTYPE html>
<html lang="es" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'LeafLog') }} · Catálogo Etnobotánico</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <header id="main-hdr">
            <div class="marca">
                <a href="/" class="marca-link">
                    <div class="marca-ico"><i class="fas fa-leaf"></i></div>
                    <div class="marca-txt">
                        <strong>LeafLog</strong>
                        <span>Etnobotánica · Fusagasugá</span>
                    </div>
                </a>
            </div>

            <form action="{{ route('buscar') }}" method="GET" class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="q" placeholder="Buscar planta, receta, animal, síntoma…" autocomplete="off">
            </form>

            <div class="hdr-right">
                <button class="theme-btn" id="theme-btn" title="Claro / Oscuro">
                    <i class="fas fa-moon"></i>
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-hdr">
                        <i class="fas fa-th-large"></i> Panel
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-hdr"><i class="fas fa-sign-out-alt"></i> Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-hdr btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Ingresar
                    </a>
                @endauth
            </div>
        </header>

        <section class="hero">
            <h1>Saberes <span>ancestrales</span>,<br>catálogo vivo de plantas.</h1>
            <p>
                LeafLog es una plataforma digital de catalogación etnobotánica que preserva
                el conocimiento tradicional de las plantas de Fusagasugá y lo conecta con
                visualización 3D mediante el efecto Pepper's Ghost.
            </p>
            <a href="{{ route('plantas.index') }}" class="btn-hero"><i class="fas fa-seedling"></i> Explorar el catálogo</a>
        </section>

        <section class="metas">
            <div class="container">
                <h2>El corazón de la plataforma</h2>
                <div class="metas-grid">
                    <div class="meta-card">
                        <i class="fas fa-leaf"></i>
                        <h3>La planta es el núcleo</h3>
                        <p>Cada planta se relaciona con recetas caseras, medicina tradicional, alimentación animal, relatos culturales y modelos 3D.</p>
                    </div>
                    <div class="meta-card">
                        <i class="fas fa-cube"></i>
                        <h3>Visualización 3D</h3>
                        <p>Uso del efecto Pepper's Ghost en dos modalidades (caja y pirámide), funcionando con teléfonos móviles.</p>
                    </div>
                    <div class="meta-card">
                        <i class="fas fa-hands-holding-circle"></i>
                        <h3>Saberes de la comunidad</h3>
                        <p>Contenido validado con la comunidad rural de Fusagasugá para conservar la memoria etnobotánica.</p>
                    </div>
                    <div class="meta-card">
                        <i class="fas fa-magnifying-glass"></i>
                        <h3>Búsqueda bidireccional</h3>
                        <p>Explora plantas por síntoma, uso, relato o fotografía: del conocimiento a la planta y de la planta al saber.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer">
            <strong>LeafLog</strong> · Plataforma digital de catalogación etnobotánica · Fusagasugá, Cundinamarca
        </footer>

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
    </body>
</html>
