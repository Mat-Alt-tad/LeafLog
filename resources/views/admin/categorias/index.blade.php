@extends('layouts.admin')

@section('title', 'Categorías · Admin · ' . config('app.name'))
@section('heading', 'Categorías y subtemas')

@section('content')
@if ($categorias->isEmpty())
    <div class="empty" style="text-align:center;color:var(--texto-suave);padding:40px">
        <i class="fas fa-tags" style="font-size:3rem;opacity:.3;display:block;margin-bottom:12px"></i>
        Aún no hay categorías.
    </div>
@endif

@foreach ($categorias as $categoria)
    <div class="rel-card">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
            <h3 style="font-family:'Lora',serif;color:var(--verde);margin:0">{{ $categoria->nombre }}</h3>
            <span class="badge-chip">Orden: {{ $categoria->orden }}</span>
        </div>
        @if ($categoria->descripcion)
            <p style="color:var(--texto-mid);margin:8px 0">{{ $categoria->descripcion }}</p>
        @endif
        <div style="margin-top:10px">
            <strong style="font-size:.8rem;color:var(--texto-mid)">Subtemas:</strong>
            @forelse ($categoria->subtemas as $subtema)
                <span class="badge-chip">{{ $subtema->nombre }}</span>
            @empty
                <span class="badge-chip">sin subtemas</span>
            @endforelse
        </div>
    </div>
@endforeach
@endsection
