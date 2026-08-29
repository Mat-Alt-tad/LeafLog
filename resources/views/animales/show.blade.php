@extends('layouts.leaf')

@section('title', $animal->nombre . ' · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container" style="max-width:900px">
        <a href="{{ route('animales.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Volver a animales</a>

        <h1 style="font-family:'Lora',serif;color:var(--verde);margin:14px 0 6px;font-size:2rem"><i class="fas fa-paw"></i> {{ $animal->nombre }}</h1>
        <p style="color:var(--texto-mid)">Tipo: {{ $animal->tipo }}</p>

        @if ($animal->descripcion)
            <p style="color:var(--texto-mid);line-height:1.6;margin:14px 0">{{ $animal->descripcion }}</p>
        @endif

        <div class="rel-card">
            <h3 style="color:var(--verde);font-family:'Lora',serif;margin:0 0 12px">Plantas que consume</h3>
            @foreach ($animal->plantas as $planta)
                <div style="padding:8px 0;border-bottom:1px solid var(--border-lt)">
                    <a href="{{ route('plantas.show', $planta) }}" style="font-weight:700;color:var(--verde)">{{ $planta->nombre }}</a>
                    <span class="badge-chip badge-dorado">Consumo: {{ $planta->pivot->tipo_consumo }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
