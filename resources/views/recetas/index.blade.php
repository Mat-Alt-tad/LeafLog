@extends('layouts.leaf')

@section('title', 'Recetas · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container">
        <h2>Recetas caseras</h2>
        <p style="color:var(--texto-mid);margin-bottom:24px">Recetas tradicionales que usan plantas del territorio.</p>

        @if ($recetas->isEmpty())
            <div class="empty" style="text-align:center;color:var(--texto-suave);padding:50px 20px">
                <i class="fas fa-utensils" style="font-size:3rem;opacity:.3;display:block;margin-bottom:12px"></i>
                Aún no hay recetas publicadas.
            </div>
        @endif

        <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px">
            @foreach ($recetas as $receta)
                <a href="{{ route('recetas.show', $receta) }}" class="rel-card" style="text-decoration:none;display:block">
                    <h3 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 6px"><i class="fas fa-utensils"></i> {{ $receta->titulo }}</h3>
                    <div class="rel-detail">
                        @if ($receta->tiempo_preparacion)<i class="fas fa-clock"></i> {{ $receta->tiempo_preparacion }} min · @endif
                        @if ($receta->porciones)<i class="fas fa-users"></i> {{ $receta->porciones }} porc. · @endif
                        {{ $receta->plantas->count() }} planta(s)
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:28px">{{ $recetas->links() }}</div>
    </div>
</section>
@endsection
