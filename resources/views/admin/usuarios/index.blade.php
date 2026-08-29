@extends('layouts.admin')

@section('title', 'Usuarios · Admin · ' . config('app.name'))
@section('heading', 'Usuarios')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Registro</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($usuarios as $usuario)
            <tr>
                <td><strong>{{ $usuario->name }}</strong></td>
                <td>{{ $usuario->email }}</td>
                <td>
                    @forelse ($usuario->roles as $rol)
                        <span class="badge-chip badge-dorado">{{ $rol->name }}</span>
                    @empty
                        <span class="badge-chip">sin rol</span>
                    @endforelse
                </td>
                <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top:20px">{{ $usuarios->links() }}</div>
@endsection
