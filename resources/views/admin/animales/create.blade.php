@extends('layouts.admin')

@section('title', 'Nuevo animal · Admin · ' . config('app.name'))
@section('heading', 'Nuevo animal')

@section('content')
@if ($errors->any())
    <div class="alert-error" style="max-width:720px;margin:0 auto 16px">
        <ul style="margin:0;padding-left:16px">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
@include('admin.animales._form', [
    'action' => route('admin.animales.store'),
    'submit' => 'Guardar animal',
])
@endsection
