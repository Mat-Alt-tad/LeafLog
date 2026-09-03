@php
    $modelosGlb = $modelos->filter(fn($m) => $m->contenido_tipo === 'glb');
    $modeloImgs = $modelos->firstWhere('contenido_tipo', 'imagenes');
    $primerGlb = $modelosGlb->first();
    $primerUrl = $primerGlb ? asset('storage/' . $primerGlb->archivo_glb) : null;
    $imagenesRutas = $modeloImgs ? json_decode($modeloImgs->archivo_glb, true) : null;
    $imagenesUrls = $imagenesRutas ? array_map(fn($r) => asset('storage/' . $r), $imagenesRutas) : null;
@endphp

@vite(['resources/js/visor.js'])

<script>
    window.__LEAFLOG_VISOR__ = {
        container: 'visor-canvas',
        url: @json($primerUrl),
        mode: 'caja',
        pyramideTipo: 'glb',
    };
</script>

<div id="visor-wrap">
    <div class="visor-stage" style="position:relative;width:100%;height:420px;background:#000;border-radius:var(--radio);overflow:hidden">
        <div id="visor-canvas" style="width:100%;height:100%"></div>
        <button type="button" id="btn-fullscreen" onclick="toggleFullscreen()"
                style="position:absolute;top:10px;right:10px;z-index:10;background:rgba(0,0,0,.6);border:1px solid rgba(255,255,255,.3);color:#fff;border-radius:8px;padding:6px 12px;cursor:pointer;font-size:.8rem;backdrop-filter:blur(4px);transition:background .2s"
                onmouseover="this.style.background='rgba(0,0,0,.85)'" onmouseout="this.style.background='rgba(0,0,0,.6)'">
            <i class="fas fa-expand-arrows-alt"></i> Pantalla completa
        </button>
    </div>

    <div class="visor-controls" style="margin-top:14px">
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <label style="font-weight:700;font-size:.85rem;margin-right:4px">Modo:</label>
            <button type="button" class="btn btn-outline btn-sm modo-active" data-modo="caja"
                    onclick="visorModo('caja')"><i class="fas fa-expand-arrows-alt"></i> Caja (GLB)</button>
            <button type="button" class="btn btn-outline btn-sm" data-modo="piramide-glb"
                    onclick="visorModo('piramide-glb')"><i class="fas fa-caret-up"></i> Pirámide (GLB)</button>
            <button type="button" class="btn btn-outline btn-sm" data-modo="piramide-imgs"
                    onclick="visorModo('piramide-imgs')"><i class="fas fa-images"></i> Pirámide (4 Imágenes)</button>
        </div>
        <p id="visor-instruccion" style="font-size:.8rem;color:var(--texto-suave);margin-top:8px">
            Modo caja: modelo 3D en pantalla completa.
        </p>

        @if($modeloImgs)
            <div style="margin-top:8px">
                <span class="badge-chip"><i class="fas fa-images"></i> 4 imágenes holograma disponibles</span>
            </div>
        @endif
    </div>
</div>

<style>
    #visor-wrap .modo-active { border-color: var(--verde-mid) !important; color: var(--verde-mid) !important; background: var(--pale) !important; }

    #visor-wrap:fullscreen {
        display: flex;
        flex-direction: column;
        background: #000;
    }
    #visor-wrap:fullscreen .visor-stage {
        flex: 1 1 auto;
        min-height: 0;
        height: auto !important;
        border-radius: 0;
    }
    #visor-wrap:fullscreen .visor-controls {
        display: none;
    }
</style>

@can('manage-3d')
<div style="margin-top:20px">
    {{-- Subir GLB --}}
    <form method="POST" action="{{ route('admin.plantas.modelos.store', $planta) }}" enctype="multipart/form-data" class="form-card" style="max-width:none">
        @csrf
        <input type="hidden" name="contenido_tipo" value="glb">
        <h3 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 14px">Subir modelo GLB</h3>
        <div class="form-group">
            <label>Archivo (.glb / .gltf / .bin) — máx 50 MB</label>
            <input type="file" name="archivo_glb" class="form-control" accept=".glb,.gltf,.bin" required>
        </div>
        <div class="form-group">
            <label>Origen</label>
            <select name="tipo" class="form-control">
                <option value="glb">Modelo 3D (diseño directo)</option>
                <option value="fotogrametria">Fotogrametría</option>
            </select>
        </div>
        <button class="btn btn-primary"><i class="fas fa-upload"></i> Subir modelo</button>
    </form>

    {{-- Subir 4 imágenes --}}
    <form method="POST" action="{{ route('admin.plantas.modelos.store', $planta) }}" enctype="multipart/form-data" class="form-card" style="max-width:none;margin-top:12px">
        @csrf
        <input type="hidden" name="contenido_tipo" value="imagenes">
        <h3 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 14px">Subir 4 imágenes (holograma pirámide)</h3>
        <p style="font-size:.85rem;color:var(--texto-suave);margin:0 0 12px">Sube 4 fotos del modelo desde diferentes ángulos para el efecto Pepper's Ghost.</p>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px">
            <div class="form-group">
                <label>Frente</label>
                <input type="file" name="imagen_1" class="form-control" accept="image/*" required>
            </div>
            <div class="form-group">
                <label>Atrás</label>
                <input type="file" name="imagen_2" class="form-control" accept="image/*" required>
            </div>
            <div class="form-group">
                <label>Izquierda</label>
                <input type="file" name="imagen_3" class="form-control" accept="image/*" required>
            </div>
            <div class="form-group">
                <label>Derecha</label>
                <input type="file" name="imagen_4" class="form-control" accept="image/*" required>
            </div>
        </div>
        <button class="btn btn-primary"><i class="fas fa-upload"></i> Subir 4 imágenes</button>
    </form>
</div>
@endcan

@push('scripts')
<script>
function toggleFullscreen() {
    const wrap = document.getElementById('visor-wrap');
    const btn = document.getElementById('btn-fullscreen');
    const icon = btn.querySelector('i');
    if (!document.fullscreenElement) {
        wrap.requestFullscreen().catch(() => {});
    } else {
        document.exitFullscreen();
    }
}

document.addEventListener('fullscreenchange', () => {
    const btn = document.getElementById('btn-fullscreen');
    if (document.fullscreenElement) {
        btn.innerHTML = '<i class="fas fa-compress-arrows-alt"></i> Salir';
    } else {
        btn.innerHTML = '<i class="fas fa-expand-arrows-alt"></i> Pantalla completa';
    }
    if (window.LeafLogVisor) window.LeafLogVisor.resize();
});

function visorModo(m) {
    document.querySelectorAll('#visor-wrap [data-modo]').forEach(b => b.classList.toggle('modo-active', b.dataset.modo === m));

    const instruccion = document.getElementById('visor-instruccion');

    if (m === 'caja') {
        instruccion.textContent = 'Modelo 3D centrado. Pulsa "Pantalla completa" para expandir.';
        if (window.LeafLogVisor) window.LeafLogVisor.setMode('caja');
    }

    if (m === 'piramide-glb') {
        instruccion.textContent = 'Coloca la pirámide de acetato centrada sobre la pantalla.';
        if (window.LeafLogVisor) window.LeafLogVisor.setMode('piramide', { pyramideTipo: 'glb' });
    }

    if (m === 'piramide-imgs') {
        const imagenesUrls = @json($imagenesUrls);

        if (imagenesUrls && imagenesUrls.length === 4) {
            instruccion.textContent = '4 vistas en formato holograma pirámide.';
            if (window.LeafLogVisor) window.LeafLogVisor.cargar(JSON.stringify(imagenesUrls), 'imagenes');
            if (window.LeafLogVisor) window.LeafLogVisor.setMode('piramide', { pyramideTipo: 'imagenes' });
        } else {
            instruccion.textContent = 'No hay 4 imágenes disponibles. Sube 4 imágenes en el formulario de abajo.';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const panel = document.getElementById('panel-3d');
    const cfg = window.__LEAFLOG_VISOR__;
    if (!cfg || !cfg.url) return;

    const initVisor = () => {
        if (!window.LeafLogVisor) return false;
        const canvas = document.getElementById(cfg.container);
        if (canvas && canvas.clientWidth > 0) {
            window.LeafLogVisor.init(cfg.container, cfg.url, 'caja', 'glb');
            return true;
        }
        return false;
    };

    window.inicializarVisor3D = (retries = 20) => {
        if (initVisor()) return;
        if (retries > 0) setTimeout(() => window.inicializarVisor3D(retries - 1), 100);
    };

    if (!panel) { window.inicializarVisor3D(); return; }

    const observer = new MutationObserver(() => {
        if (panel.classList.contains('active')) {
            window.inicializarVisor3D();
            observer.disconnect();
        }
    });
    observer.observe(panel, { attributes: true, attributeFilter: ['class'] });
    if (panel.classList.contains('active')) window.inicializarVisor3D();
});
</script>
@endpush
