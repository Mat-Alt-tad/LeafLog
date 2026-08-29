@extends('layouts.admin')

@section('title', 'Tratamientos · Admin · ' . config('app.name'))
@section('heading', 'Tratamientos')

@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:16px">
    <a href="{{ route('admin.tratamientos.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo tratamiento</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Síntoma</th>
            <th>Gravedad</th>
            <th>Plantas</th>
            <th style="width:150px">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tratamientos as $tratamiento)
            <tr>
                <td><strong>{{ $tratamiento->sintoma }}</strong></td>
                <td>
                    @if ($tratamiento->gravedad === 'baja') <span class="badge-chip">Baja</span>
                    @elseif ($tratamiento->gravedad === 'media') <span class="badge-chip badge-dorado">Media</span>
                    @else <span class="badge-chip" style="background:rgba(229,57,53,.15);color:var(--danger)">Alta</span>
                    @endif
                </td>
                <td>
                    @foreach ($tratamiento->plantas->take(3) as $planta)
                        <span class="badge-chip">{{ $planta->nombre }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('tratamientos.show', $tratamiento) }}" class="btn btn-outline btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('admin.tratamientos.edit', $tratamiento) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-pen"></i></a>
                    <form method="POST" action="{{ route('admin.tratamientos.destroy', $tratamiento) }}" style="display:inline" onsubmit="return confirm('¿Eliminar este tratamiento?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top:20px">{{ $tratamientos->links() }}</div>
@endsection
