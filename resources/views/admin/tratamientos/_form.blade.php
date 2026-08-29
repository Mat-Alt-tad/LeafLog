@php($t = $tratamiento ?? null)
<form method="POST" action="{{ $action }}" class="form-card" style="max-width:720px">
    @csrf @if ($method ?? false) @method($method) @endif

    <div class="form-group">
        <label>Síntoma / afección *</label>
        <input type="text" name="sintoma" class="form-control" value="{{ old('sintoma', $t?->sintoma) }}" required>
    </div>

    <div class="form-group">
        <label>Gravedad</label>
        <select name="gravedad" class="form-control">
            @foreach (['baja', 'media', 'alta'] as $op)
                <option value="{{ $op }}" {{ old('gravedad', $t?->gravedad ?? 'baja') === $op ? 'selected' : '' }}>{{ ucfirst($op) }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $t?->descripcion) }}</textarea>
    </div>

    <div style="display:flex;gap:10px">
        <button class="btn btn-primary"><i class="fas fa-save"></i> {{ $submit ?? 'Guardar' }}</button>
        <a href="{{ route('admin.tratamientos.index') }}" class="btn btn-outline">Cancelar</a>
    </div>
</form>
