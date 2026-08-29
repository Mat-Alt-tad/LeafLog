@extends('layouts.admin')

@section('title', 'Editar tratamiento · Admin · ' . config('app.name'))
@section('heading', 'Editar: ' . $tratamiento->sintoma)

@section('content')
@if ($errors->any())
    <div class="alert-error" style="max-width:720px;margin:0 auto 16px">
        <ul style="margin:0;padding-left:16px">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
@include('admin.tratamientos._form', [
    'action' => route('admin.tratamientos.update', $tratamiento),
    'method' => 'PUT',
    'tratamiento' => $tratamiento,
    'submit' => 'Actualizar tratamiento',
])
@endsection
