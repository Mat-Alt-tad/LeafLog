@extends('layouts.admin')

@section('title', 'Plantas · Admin · ' . config('app.name'))
@section('heading', 'Plantas')

@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:16px">
    <a href="{{ route('admin.plantas.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva planta</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Científico</th>
            <th>Subtema</th>
            <th>Categorías</th>
            <th>Estado</th>
            <th style="width:150px">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($plantas as $planta)
            <tr>
                <td><strong>{{ $planta->nombre }}</strong></td>
                <td style="font-style:italic;color:var(--texto-mid)">{{ $planta->cientifico ?? '—' }}</td>
                <td>{{ $planta->subtema?->nombre ?? '—' }}</td>
                <td>
                    @foreach ($planta->categorias->take(2) as $cat)
                        <span class="badge-chip">{{ $cat->nombre }}</span>
                    @endforeach
                </td>
                <td>
                    @if ($planta->verificada)
                        <span class="badge-chip badge-dorado">Verificada</span>
                    @else
                        <span class="badge-chip">Borrador</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('plantas.show', $planta) }}" class="btn btn-outline btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('admin.plantas.edit', $planta) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-pen"></i></a>
                    <form method="POST" action="{{ route('admin.plantas.destroy', $planta) }}" style="display:inline" onsubmit="return confirm('¿Eliminar esta planta?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top:20px">{{ $plantas->links() }}</div>
@endsection
