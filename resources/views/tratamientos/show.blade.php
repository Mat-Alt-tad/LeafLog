@extends('layouts.leaf')

@section('title', $tratamiento->sintoma . ' · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container" style="max-width:900px">
        <a href="{{ route('tratamientos.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Volver a tratamientos</a>

        <h1 style="font-family:'Lora',serif;color:var(--verde);margin:14px 0 6px;font-size:2rem"><i class="fas fa-heartbeat"></i> {{ $tratamiento->sintoma }}</h1>
        <span class="badge-chip badge-dorado">Gravedad: {{ $tratamiento->gravedad }}</span>

        @if ($tratamiento->descripcion)
            <p style="color:var(--texto-mid);line-height:1.6;margin:14px 0">{{ $tratamiento->descripcion }}</p>
        @endif

        <div class="rel-card">
            <h3 style="color:var(--verde);font-family:'Lora',serif;margin:0 0 12px">Plantas recomendadas</h3>
            @foreach ($tratamiento->plantas as $planta)
                <div style="padding:8px 0;border-bottom:1px solid var(--border-lt)">
                    <a href="{{ route('plantas.show', $planta) }}" style="font-weight:700;color:var(--verde)">{{ $planta->nombre }}</a>
                    @if ($planta->pivot->parte_usada)
                        <span class="badge-chip">Parte: {{ $planta->pivot->parte_usada }}</span>
                    @endif
                    @if ($planta->pivot->preparacion)
                        <span class="badge-chip badge-dorado">Prep: {{ $planta->pivot->preparacion }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
