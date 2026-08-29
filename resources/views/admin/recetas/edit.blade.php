@extends('layouts.admin')

@section('title', 'Editar receta · Admin · ' . config('app.name'))
@section('heading', 'Editar: ' . $receta->titulo)

@section('content')
@if ($errors->any())
    <div class="alert-error" style="max-width:900px;margin:0 auto 16px">
        <ul style="margin:0;padding-left:16px">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
@include('admin.recetas._form', [
    'action' => route('admin.recetas.update', $receta),
    'method' => 'PUT',
    'receta' => $receta,
    'submit' => 'Actualizar receta',
])
@endsection
