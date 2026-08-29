@extends('layouts.admin')

@section('title', 'Nuevo tratamiento · Admin · ' . config('app.name'))
@section('heading', 'Nuevo tratamiento')

@section('content')
@if ($errors->any())
    <div class="alert-error" style="max-width:720px;margin:0 auto 16px">
        <ul style="margin:0;padding-left:16px">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
@include('admin.tratamientos._form', [
    'action' => route('admin.tratamientos.store'),
    'submit' => 'Guardar tratamiento',
])
@endsection
