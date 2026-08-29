@extends('layouts.leaf')

@section('title', 'Catálogo · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container">
        <h2>Catálogo de plantas</h2>

        <form method="GET" action="{{ route('plantas.index') }}" class="filters" style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:24px;align-items:center">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre, científico o tags…" class="form-control" style="max-width:320px">
            <select name="categoria" class="form-control" style="max-width:240px">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filtrar</button>
            <a href="{{ route('plantas.index') }}" class="btn btn-outline btn-sm">Limpiar</a>
        </form>

        @if ($plantas->isEmpty())
            <div class="empty" style="text-align:center;color:var(--texto-suave);padding:50px 20px">
                <i class="fas fa-seedling" style="font-size:3rem;opacity:.3;display:block;margin-bottom:12px"></i>
                No se encontraron plantas con esos criterios.
            </div>
        @endif

        <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px">
            @foreach ($plantas as $planta)
                <a href="{{ route('plantas.show', $planta) }}" class="planta-card">
                    <div class="img-wrap">
                        @if ($planta->img_path)
                            <img src="{{ asset('storage/' . $planta->img_path) }}" alt="{{ $planta->nombre }}">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--verde-mid)"><i class="fas fa-leaf" style="font-size:2.2rem"></i></div>
                        @endif
                    </div>
                    <div class="body">
                        <h3>{{ $planta->nombre }}</h3>
                        @if ($planta->cientifico)
                            <p class="cien">{{ $planta->cientifico }}</p>
                        @endif
                        @foreach ($planta->categorias->take(2) as $cat)
                            <span class="badge-chip">{{ $cat->nombre }}</span>
                        @endforeach
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:28px">{{ $plantas->links() }}</div>
    </div>
</section>
@endsection
