@extends('layouts.leaf')

@section('title', 'Búsqueda · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container" style="max-width:900px">
        <h2>Búsqueda en LeafLog</h2>

        <form method="GET" action="{{ route('buscar') }}" style="display:flex;gap:10px;margin:20px 0">
            <input type="text" name="q" value="{{ $q }}" placeholder="Buscar planta, receta, animal, síntoma…" class="form-control" autofocus>
            <button class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
        </form>

        @if ($q === '')
            <p style="color:var(--texto-suave)">Escribe un término para buscar en todo el catálogo.</p>
        @elseif ($resultados->isEmpty())
            <div class="empty" style="text-align:center;color:var(--texto-suave);padding:40px 20px">
                <i class="fas fa-search" style="font-size:3rem;opacity:.3;display:block;margin-bottom:12px"></i>
                No se encontraron resultados para «{{ $q }}».
            </div>
        @else
            <p style="color:var(--texto-mid)">Resultados para «<strong>{{ $q }}</strong>»:</p>
            @foreach ($resultados as $grupo => $items)
                <div class="rel-card">
                    <h3 style="color:var(--verde);font-family:'Lora',serif;margin:0 0 10px">{{ $grupo }}</h3>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        @foreach ($items as $item)
                            <a href="{{ $item['url'] }}" class="search-item" style="text-decoration:none">
                                <i class="fas fa-arrow-right" style="font-size:.75rem"></i>
                                <div>
                                    <span style="font-weight:700;color:var(--texto)">{{ $item['titulo'] }}</span>
                                    @if (!empty($item['sub']))
                                        <div style="font-size:.82rem;color:var(--texto-suave);font-style:italic">{{ $item['sub'] }}</div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>
@endsection
