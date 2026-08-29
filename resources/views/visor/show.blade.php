@php($primerModeloUrl = $primerModelo ? asset('storage/' . $primerModelo->archivo_glb) : null)
@vite(['resources/js/visor.js'])

<script>
    window.__LEAFLOG_VISOR__ = {
        container: 'visor-canvas',
        url: @json($primerModeloUrl),
        mode: 'caja',
    };
</script>

<div class="visor" id="visor-wrap" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
    <div id="visor-canvas" style="width:100%;height:420px;background:#000;border-radius:var(--radio);overflow:hidden"></div>

    <div>
        @forelse ($modelos as $modelo)
            <div class="rel-card">
                <i class="fas fa-cube" style="color:var(--dorado)"></i>
                <strong>{{ ucfirst($modelo->tipo) }}</strong>
                @if ($modelo->fecha_subida)
                    <span class="badge-chip">{{ $modelo->fecha_subida->format('d/m/Y') }}</span>
                @endif
                <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap">
                    <button type="button" class="btn btn-primary btn-sm" onclick="window.LeafLogVisor && window.LeafLogVisor.init('visor-canvas', this.dataset.glb, 'caja')" data-glb="{{ asset('storage/' . $modelo->archivo_glb) }}">
                        <i class="fas fa-play"></i> Mostrar
                    </button>
                    <a href="{{ asset('storage/' . $modelo->archivo_glb) }}" download class="btn btn-outline btn-sm">
                        <i class="fas fa-download"></i> Descargar
                    </a>
                    @can('manage-3d')
                        <form method="POST" action="{{ route('admin.modelos.destroy', $modelo) }}" onsubmit="return confirm('¿Eliminar este modelo?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    @endcan
                </div>
            </div>
        @empty
            <div class="empty" style="text-align:center;color:var(--texto-suave);padding:20px">
                <i class="fas fa-cube" style="font-size:2.4rem;opacity:.3;display:block;margin-bottom:10px"></i>
                Esta planta aún no tiene modelos 3D.
                @can('manage-3d')
                    <div style="margin-top:14px">
                        <a href="{{ route('admin.plantas.edit', $planta) }}" class="btn btn-primary btn-sm">Subir modelo GLB</a>
                    </div>
                @endcan
            </div>
        @endforelse

        <div style="margin-top:16px">
            <label style="font-weight:700;font-size:.85rem">Modo de visualización</label>
            <div style="display:flex;gap:8px;margin-top:8px">
                <button type="button" class="btn btn-outline btn-sm" data-modo="caja" onclick="visorModo('caja')"><i class="fas fa-box"></i> Caja</button>
                <button type="button" class="btn btn-outline btn-sm" data-modo="piramide" onclick="visorModo('piramide')"><i class="fas fa-caret-up"></i> Pirámide</button>
            </div>
            <p id="visor-instruccion" style="font-size:.8rem;color:var(--texto-suave);margin-top:10px">
                Coloca tu teléfono en la parte superior de la caja con la pantalla hacia abajo.
            </p>
        </div>
    </div>
</div>

@can('manage-3d')
    <form method="POST" action="{{ route('admin.plantas.modelos.store', $planta) }}" enctype="multipart/form-data" class="form-card" style="margin-top:20px;max-width:none">
        @csrf
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
@endcan

<style>
    .visor .modo-active { border-color: var(--verde-mid) !important; color: var(--verde-mid) !important; background: var(--pale) !important; }
</style>

@push('scripts')
<script>
    function visorModo(m) {
        document.querySelectorAll('#visor-wrap [data-modo]').forEach(b => b.classList.toggle('modo-active', b.dataset.modo === m));
        document.getElementById('visor-instruccion').textContent = m === 'caja'
            ? 'Coloca tu teléfono en la parte superior de la caja con la pantalla hacia abajo.'
            : 'Coloca la pirámide de acetato centrada sobre la pantalla.';
        if (window.LeafLogVisor) window.LeafLogVisor.setMode(m);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const panel = document.getElementById('panel-3d');
        const cfg = window.__LEAFLOG_VISOR__;
        if (!cfg || !cfg.url) return;

        const tryInit = () => {
            const canvas = document.getElementById(cfg.container);
            if (canvas && canvas.clientWidth > 0 && !panel) {
                window.LeafLogVisor && window.LeafLogVisor.init(cfg.container, cfg.url, 'caja');
                return true;
            }
            return false;
        };

        if (!panel) { tryInit(); return; }

        const observer = new MutationObserver(() => {
            if (panel.classList.contains('active')) {
                const canvas = document.getElementById(cfg.container);
                if (canvas && canvas.clientWidth > 0) {
                    window.LeafLogVisor && window.LeafLogVisor.init(cfg.container, cfg.url, 'caja');
                    observer.disconnect();
                }
            }
        });
        observer.observe(panel, { attributes: true, attributeFilter: ['class'] });
        if (panel.classList.contains('active')) tryInit();
    });
</script>
@endpush
