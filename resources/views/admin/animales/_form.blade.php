@php($a = $animal ?? null)
<form method="POST" action="{{ $action }}" class="form-card" style="max-width:720px">
    @csrf @if ($method ?? false) @method($method) @endif

    <div class="form-group">
        <label>Nombre *</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $a?->nombre) }}" required>
    </div>

    <div class="form-group">
        <label>Tipo</label>
        <select name="tipo" class="form-control">
            @foreach (['domestico', 'silvestre', 'urbano', 'otro'] as $op)
                <option value="{{ $op }}" {{ old('tipo', $a?->tipo ?? 'domestico') === $op ? 'selected' : '' }}>{{ ucfirst($op) }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $a?->descripcion) }}</textarea>
    </div>

    <div style="display:flex;gap:10px">
        <button class="btn btn-primary"><i class="fas fa-save"></i> {{ $submit ?? 'Guardar' }}</button>
        @if ($a)
            <a href="{{ route('admin.animales.edit', $a) }}" class="btn btn-outline">Cancelar</a>
        @else
            <a href="{{ route('admin.animales.index') }}" class="btn btn-outline">Cancelar</a>
        @endif
    </div>
</form>
