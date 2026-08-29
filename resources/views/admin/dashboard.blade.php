@extends('layouts.admin')

@section('title', 'Resumen · Admin · ' . config('app.name'))
@section('heading', 'Resumen')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="num">{{ $stats['plantas'] }}</div><div class="lab"><i class="fas fa-leaf"></i> Plantas</div></div>
    <div class="stat-card"><div class="num">{{ $stats['recetas'] }}</div><div class="lab"><i class="fas fa-utensils"></i> Recetas</div></div>
    <div class="stat-card"><div class="num">{{ $stats['animales'] }}</div><div class="lab"><i class="fas fa-paw"></i> Animales</div></div>
    <div class="stat-card"><div class="num">{{ $stats['tratamientos'] }}</div><div class="lab"><i class="fas fa-heartbeat"></i> Tratamientos</div></div>
    <div class="stat-card"><div class="num">{{ $stats['modelos'] }}</div><div class="lab"><i class="fas fa-cube"></i> Modelos 3D</div></div>
    <div class="stat-card" style="border-left-color:var(--dorado)"><div class="num">{{ $stats['aportes_pendientes'] }}</div><div class="lab"><i class="fas fa-inbox"></i> Aportes pendientes</div></div>
    <div class="stat-card"><div class="num">{{ $stats['usuarios'] }}</div><div class="lab"><i class="fas fa-users"></i> Usuarios</div></div>
</div>

@if ($stats['aportes_pendientes'] > 0)
    <div class="rel-card" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
        <span>Tienes <strong>{{ $stats['aportes_pendientes'] }}</strong> aporte(s) por moderar.</span>
        <a href="{{ route('aportes.index') }}" class="btn btn-dorado btn-sm">Revisar moderación <i class="fas fa-arrow-right"></i></a>
    </div>
@endif
@endsection
