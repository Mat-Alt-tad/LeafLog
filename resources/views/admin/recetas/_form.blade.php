@php($r = $receta ?? null)
<form method="POST" action="{{ $action }}" class="form-card" style="max-width:900px">
    @csrf @if ($method ?? false) @method($method) @endif

    <div class="form-group">
        <label>Título *</label>
        <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $r?->titulo) }}" required>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
            <label>Tiempo de preparación (min)</label>
            <input type="number" name="tiempo_preparacion" class="form-control" value="{{ old('tiempo_preparacion', $r?->tiempo_preparacion) }}">
        </div>
        <div class="form-group">
            <label>Porciones</label>
            <input type="number" name="porciones" class="form-control" value="{{ old('porciones', $r?->porciones) }}">
        </div>
    </div>

    <div class="form-group">
        <label>Instrucciones</label>
        <textarea name="instrucciones" class="form-control" rows="5">{{ old('instrucciones', $r?->instrucciones) }}</textarea>
    </div>

    <div class="form-group">
        <label>Plantas usadas</label>
        <div id="receta-plantas" style="display:flex;flex-direction:column;gap:8px">
            @php($oldCant = old('cantidades') ?? [])
            @php($oldPartes = old('partes_usadas') ?? [])
            @forelse (old('plantas', ($rPlantas = $r?->plantas ?? collect())->pluck('id')->all()) as $index => $plantaId)
                @include('admin.recetas._planta-row', ['index' => $index, 'plantaId' => $plantaId, 'cantidad' => $oldCant[$index] ?? ($rPlantas[$index]->pivot?->cantidad ?? null), 'parte' => $oldPartes[$index] ?? ($rPlantas[$index]->pivot?->parte_usada ?? null)])
            @empty
                @include('admin.recetas._planta-row', ['index' => 0, 'plantaId' => null, 'cantidad' => null, 'parte' => null])
            @endforelse
        </div>
        <button type="button" class="btn btn-outline btn-sm" style="margin-top:8px" onclick="agregarPlantaReceta()">
            <i class="fas fa-plus"></i> Agregar planta
        </button>
    </div>

    <div style="display:flex;gap:10px">
        <button class="btn btn-primary"><i class="fas fa-save"></i> {{ $submit ?? 'Guardar' }}</button>
        <a href="{{ route('admin.recetas.index') }}" class="btn btn-outline">Cancelar</a>
    </div>
</form>

@push('scripts')
<script>
    let filaReceta = 1;
    const plantOptionsReceta = @json($plantas->map(fn($p) => ['id' => $p->id, 'nombre' => $p->nombre])->all());
    function agregarPlantaReceta() {
        const fila = document.createElement('div');
        fila.style.cssText = 'display:flex;gap:8px;align-items:center';
        let opts = '<select name="plantas[]" class="form-control" style="flex:2" required><option value="">Selecciona…</option>';
        plantOptionsReceta.forEach(p => opts += `<option value="${p.id}">${p.nombre}</option>`);
        opts += '</select>';
        fila.innerHTML = opts +
            '<select name="partes_usadas[]" class="form-control" style="flex:1"><option value="">Parte</option><option>Hoja</option><option>Flor</option><option>Raíz</option><option>Corteza</option><option>Fruto</option><option>Semilla</option><option>Planta completa</option></select>';
        document.getElementById('receta-plantas').appendChild(fila);
    }
</script>
@endpush
