@extends('layouts.admin')

@section('title', 'Recetas · Admin · ' . config('app.name'))
@section('heading', 'Recetas')

@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:16px">
    <a href="{{ route('admin.recetas.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva receta</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Plantas</th>
            <th>Tiempo</th>
            <th>Porciones</th>
            <th style="width:150px">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($recetas as $receta)
            <tr>
                <td><strong>{{ $receta->titulo }}</strong></td>
                <td>
                    @foreach ($receta->plantas->take(3) as $planta)
                        <span class="badge-chip">{{ $planta->nombre }}</span>
                    @endforeach
                </td>
                <td>{{ $receta->tiempo_preparacion ? $receta->tiempo_preparacion . ' min' : '—' }}</td>
                <td>{{ $receta->porciones ?? '—' }}</td>
                <td>
                    <a href="{{ route('recetas.show', $receta) }}" class="btn btn-outline btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('admin.recetas.edit', $receta) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-pen"></i></a>
                    <form method="POST" action="{{ route('admin.recetas.destroy', $receta) }}" style="display:inline" onsubmit="return confirm('¿Eliminar esta receta?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top:20px">{{ $recetas->links() }}</div>
@endsection
