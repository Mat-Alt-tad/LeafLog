@extends('layouts.leaf')

@section('title', $receta->titulo . ' · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container" style="max-width:900px">
        <a href="{{ route('recetas.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Volver a recetas</a>

        <h1 style="font-family:'Lora',serif;color:var(--verde);margin:14px 0 6px;font-size:2rem"><i class="fas fa-utensils"></i> {{ $receta->titulo }}</h1>
        <div style="display:flex;gap:16px;flex-wrap:wrap;color:var(--texto-mid);margin-bottom:20px">
            @if ($receta->tiempo_preparacion)<span><i class="fas fa-clock"></i> {{ $receta->tiempo_preparacion }} minutos</span>@endif
            @if ($receta->porciones)<span><i class="fas fa-users"></i> {{ $receta->porciones }} porciones</span>@endif
        </div>

        <div class="rel-card">
            <h3 style="color:var(--verde);font-family:'Lora',serif;margin:0 0 12px">Ingredientes / plantas usadas</h3>
            @foreach ($receta->plantas as $planta)
                <div style="padding:8px 0;border-bottom:1px solid var(--border-lt)">
                    <a href="{{ route('plantas.show', $planta) }}" style="font-weight:700;color:var(--verde)">{{ $planta->nombre }}</a>
                    @if ($planta->pivot->cantidad)
                        <span class="badge-chip">Cantidad: {{ $planta->pivot->cantidad }}</span>
                    @endif
                    @if ($planta->pivot->parte_usada)
                        <span class="badge-chip badge-dorado">Parte: {{ $planta->pivot->parte_usada }}</span>
                    @endif
                </div>
            @endforeach
        </div>

        @if ($receta->instrucciones)
            <div class="rel-card">
                <h3 style="color:var(--verde);font-family:'Lora',serif;margin:0 0 12px"><i class="fas fa-list-ol"></i> Preparación</h3>
                <p style="line-height:1.7;color:var(--texto-mid);white-space:pre-line">{{ $receta->instrucciones }}</p>
            </div>
        @endif
    </div>
</section>
@endsection
