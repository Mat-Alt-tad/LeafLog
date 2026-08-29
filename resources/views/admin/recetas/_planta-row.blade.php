<div style="display:flex;gap:8px;align-items:center">
    <select name="plantas[]" class="form-control" style="flex:2" required>
        <option value="">Selecciona…</option>
        @foreach ($plantas as $planta)
            <option value="{{ $planta->id }}" {{ $plantaId == $planta->id ? 'selected' : '' }}>{{ $planta->nombre }}</option>
        @endforeach
    </select>
    <input type="text" name="cantidades[]" class="form-control" style="flex:1" placeholder="Cantidad" value="{{ $cantidad }}">
    <select name="partes_usadas[]" class="form-control" style="flex:1">
        <option value="">Parte</option>
        @foreach (['Hoja', 'Flor', 'Raíz', 'Corteza', 'Fruto', 'Semilla', 'Planta completa'] as $op)
            <option value="{{ $op }}" {{ $parte === $op ? 'selected' : '' }}>{{ $op }}</option>
        @endforeach
    </select>
</div>
