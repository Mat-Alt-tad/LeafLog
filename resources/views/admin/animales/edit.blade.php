@extends('layouts.admin')

@section('title', 'Editar animal · Admin · ' . config('app.name'))
@section('heading', 'Editar: ' . $animal->nombre)

@section('content')
@if ($errors->any())
    <div class="alert-error" style="max-width:720px;margin:0 auto 16px">
        <ul style="margin:0;padding-left:16px">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

@include('admin.animales._form', [
    'action' => route('admin.animales.update', $animal),
    'method' => 'PUT',
    'animal' => $animal,
    'submit' => 'Actualizar animal',
])

@php($animal->loadMissing('plantas'))
@php($plantas = \App\Models\Planta::orderBy('nombre')->get())
<div class="form-card" style="max-width:720px;margin-top:24px">
    <h3 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 14px">Plantas que consume este animal</h3>
    <form method="POST" action="{{ route('admin.animales.plantas.store', $animal) }}">
        @csrf
        <div class="form-group">
            <label>Selecciona las plantas y su tipo de consumo</label>
            <div id="animal-plantas" style="display:flex;flex-direction:column;gap:8px">
                @forelse ($animal->plantas as $planta)
                    <div style="display:flex;gap:8px;align-items:center">
                        <select name="plantas[]" class="form-control" style="flex:2">
                            <option value="">Selecciona…</option>
                            @foreach ($plantas as $p)
                                <option value="{{ $p->id }}" {{ $p->id === $planta->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                        <select name="tipos_consumo[]" class="form-control" style="flex:1">
                            @foreach (['alimento', 'medicina', 'complemento', 'refugio'] as $op)
                                <option value="{{ $op }}" {{ $planta->pivot->tipo_consumo === $op ? 'selected' : '' }}>{{ ucfirst($op) }}</option>
                            @endforeach
                        </select>
                    </div>
                @empty
                    <div style="display:flex;gap:8px;align-items:center">
                        <select name="plantas[]" class="form-control" style="flex:2">
                            <option value="">Selecciona…</option>
                            @foreach ($plantas as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                        <select name="tipos_consumo[]" class="form-control" style="flex:1">
                            @foreach (['alimento', 'medicina', 'complemento', 'refugio'] as $op)
                                <option value="{{ $op }}">{{ ucfirst($op) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforelse
            </div>
            <button type="button" class="btn btn-outline btn-sm" style="margin-top:8px" onclick="agregarPlantaAnimal()">
                <i class="fas fa-plus"></i> Agregar planta
            </button>
        </div>
        <button class="btn btn-dorado"><i class="fas fa-save"></i> Guardar asociaciones</button>
    </form>
</div>

@push('scripts')
<script>
    const plantOptionsAnimal = @json($plantas->map(fn($p) => ['id' => $p->id, 'nombre' => $p->nombre])->all());
    function agregarPlantaAnimal() {
        const div = document.createElement('div');
        div.style.cssText = 'display:flex;gap:8px;align-items:center';
        let opts = '<select name="plantas[]" class="form-control" style="flex:2"><option value="">Selecciona…</option>';
        plantOptionsAnimal.forEach(p => opts += `<option value="${p.id}">${p.nombre}</option>`);
        opts += '</select>';
        const tipos = ['alimento','medicina','complemento','refugio'].map(t => `<option value="${t}">${t.charAt(0).toUpperCase()+t.slice(1)}</option>`).join('');
        div.innerHTML = opts + `<select name="tipos_consumo[]" class="form-control" style="flex:1">${tipos}</select>`;
        document.getElementById('animal-plantas').appendChild(div);
    }
</script>
@endpush
@endsection
