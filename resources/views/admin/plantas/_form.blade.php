@php($p = $planta ?? null)
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="form-card" style="max-width:900px">
    @csrf @if ($method ?? false) @method($method) @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
            <label>Nombre común *</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $p?->nombre) }}" required>
        </div>
        <div class="form-group">
            <label>Nombre científico</label>
            <input type="text" name="cientifico" class="form-control" value="{{ old('cientifico', $p?->cientifico) }}">
        </div>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $p?->descripcion) }}</textarea>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
            <label>Contexto cultural</label>
            <textarea name="contexto" class="form-control" rows="3">{{ old('contexto', $p?->contexto) }}</textarea>
        </div>
        <div class="form-group">
            <label>Relato</label>
            <textarea name="relato" class="form-control" rows="3">{{ old('relato', $p?->relato) }}</textarea>
        </div>
    </div>

    <div class="form-group">
        <label>Instrucciones de uso</label>
        <textarea name="instrucciones" class="form-control" rows="3">{{ old('instrucciones', $p?->instrucciones) }}</textarea>
    </div>

    <div class="form-group">
        <label>Etiquetas (separadas por coma)</label>
        <input type="text" name="tags" class="form-control" value="{{ old('tags', $p?->tags) }}">
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
            <label>Subtema</label>
            <select name="subtema_id" class="form-control">
                <option value="">— Sin subtema —</option>
                @foreach ($categorias as $categoria)
                    @foreach ($categoria->subtemas as $subtema)
                        <option value="{{ $subtema->id }}" {{ old('subtema_id', $p?->subtema_id) == $subtema->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }} / {{ $subtema->nombre }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Categorías</label>
            <select name="categorias[]" class="form-control" multiple>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}"
                        {{ in_array($categoria->id, old('categorias', $p?->categorias->pluck('id')->all() ?? [])) ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            <small style="color:var(--texto-suave)">Ctrl para seleccionar varias</small>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
            <label>Video URL</label>
            <input type="text" name="video_url" class="form-control" value="{{ old('video_url', $p?->video_url) }}">
        </div>
        <div class="form-group">
            <label>URL testigo</label>
            <input type="text" name="video_persona_nombre" class="form-control" placeholder="Nombre de la persona" value="{{ old('video_persona_nombre', $p?->video_persona_nombre) }}">
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
            <label>Rol de la persona</label>
            <input type="text" name="video_persona_rol" class="form-control" value="{{ old('video_persona_rol', $p?->video_persona_rol) }}">
        </div>
        <div class="form-group">
            <label>Imagen (máx 5 MB)</label>
            <input type="file" name="imagen" class="form-control" accept="image/*">
            @if ($p?->img_path)
                <small style="color:var(--texto-suave)">Imagen actual: {{ basename($p->img_path) }}</small>
            @endif
        </div>
    </div>

    <div style="display:flex;gap:24px;align-items:center;margin:18px 0">
        <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer">
            <input type="checkbox" name="verificada" value="1" {{ old('verificada', $p?->verificada) ? 'checked' : '' }}>
            Verificada
        </label>
        <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer">
            <input type="checkbox" name="video_validado" value="1" {{ old('video_validado', $p?->video_validado) ? 'checked' : '' }}>
            Video validado
        </label>
    </div>

    <div style="display:flex;gap:10px">
        <button class="btn btn-primary"><i class="fas fa-save"></i> {{ $submit ?? 'Guardar' }}</button>
        <a href="{{ route('admin.plantas.index') }}" class="btn btn-outline">Cancelar</a>
    </div>
</form>
