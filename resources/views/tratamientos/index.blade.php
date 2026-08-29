@extends('layouts.leaf')

@section('title', 'Medicina tradicional · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container">
        <h2>Medicina tradicional</h2>
        <p style="color:var(--texto-mid);margin-bottom:24px">Tratamientos tradicionales asociados a plantas.</p>

        @if ($tratamientos->isEmpty())
            <div class="empty" style="text-align:center;color:var(--texto-suave);padding:50px 20px">
                <i class="fas fa-heartbeat" style="font-size:3rem;opacity:.3;display:block;margin-bottom:12px"></i>
                Aún no hay tratamientos registrados.
            </div>
        @endif

        <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px">
            @foreach ($tratamientos as $tratamiento)
                <a href="{{ route('tratamientos.show', $tratamiento) }}" class="rel-card" style="text-decoration:none;display:block">
                    <h3 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 6px"><i class="fas fa-heartbeat"></i> {{ $tratamiento->sintoma }}</h3>
                    <div class="rel-detail">
                        Gravedad: {{ $tratamiento->gravedad }} · {{ $tratamiento->plantas->count() }} planta(s)
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:28px">{{ $tratamientos->links() }}</div>
    </div>
</section>
@endsection
