@extends('layouts.admin')

@section('title', 'Animales · Admin · ' . config('app.name'))
@section('heading', 'Animales')

@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:16px">
    <a href="{{ route('admin.animales.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo animal</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Plantas</th>
            <th style="width:150px">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($animales as $animal)
            <tr>
                <td><strong>{{ $animal->nombre }}</strong></td>
                <td>{{ $animal->tipo }}</td>
                <td>
                    @foreach ($animal->plantas->take(3) as $planta)
                        <span class="badge-chip">{{ $planta->nombre }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('animales.show', $animal) }}" class="btn btn-outline btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('admin.animales.edit', $animal) }}" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-pen"></i></a>
                    <form method="POST" action="{{ route('admin.animales.destroy', $animal) }}" style="display:inline" onsubmit="return confirm('¿Eliminar este animal?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top:20px">{{ $animales->links() }}</div>
@endsection
