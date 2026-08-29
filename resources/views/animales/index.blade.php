@extends('layouts.leaf')

@section('title', 'Animales · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container">
        <h2>Alimentación animal</h2>
        <p style="color:var(--texto-mid);margin-bottom:24px">Plantas usadas en la alimentación y cuidado de animales.</p>

        @if ($animales->isEmpty())
            <div class="empty" style="text-align:center;color:var(--texto-suave);padding:50px 20px">
                <i class="fas fa-paw" style="font-size:3rem;opacity:.3;display:block;margin-bottom:12px"></i>
                Aún no hay animales registrados.
            </div>
        @endif

        <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px">
            @foreach ($animales as $animal)
                <a href="{{ route('animales.show', $animal) }}" class="rel-card" style="text-decoration:none;display:block">
                    <h3 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 6px"><i class="fas fa-paw"></i> {{ $animal->nombre }}</h3>
                    <div class="rel-detail">
                        Tipo: {{ $animal->tipo }} · {{ $animal->plantas->count() }} planta(s) asociada(s)
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:28px">{{ $animales->links() }}</div>
    </div>
</section>
@endsection
