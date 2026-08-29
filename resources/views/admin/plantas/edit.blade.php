@extends('layouts.admin')

@section('title', 'Editar planta · Admin · ' . config('app.name'))
@section('heading', 'Editar: ' . $planta->nombre)

@section('content')
@if ($errors->any())
    <div class="alert-error" style="max-width:900px;margin:0 auto 16px">
        <ul style="margin:0;padding-left:16px">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
@include('admin.plantas._form', [
    'action' => route('admin.plantas.update', $planta),
    'method' => 'PUT',
    'planta' => $planta,
    'submit' => 'Actualizar planta',
])
@endsection
