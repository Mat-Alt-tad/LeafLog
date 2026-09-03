@extends('layouts.leaf')

@section('title', $planta->nombre . ' · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container" style="max-width:1100px">
        <a href="{{ route('plantas.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Volver al catálogo</a>

        <div class="ficha-head" style="display:flex;gap:24px;flex-wrap:wrap;align-items:flex-start;margin-bottom:24px">
            @if ($planta->img_path)
                <img src="{{ asset('storage/' . $planta->img_path) }}" alt="{{ $planta->nombre }}"
                     style="width:280px;height:280px;object-fit:cover;border-radius:var(--radio);box-shadow:var(--sombra)">
            @else
                <div style="width:280px;height:280px;background:var(--pale);border-radius:var(--radio);display:flex;align-items:center;justify-content:center;color:var(--verde-mid)">
                    <i class="fas fa-leaf" style="font-size:4rem"></i>
                </div>
            @endif
            <div style="flex:1;min-width:260px">
                <h1 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 4px;font-size:2.1rem">{{ $planta->nombre }}</h1>
                @if ($planta->cientifico)
                    <p style="font-style:italic;color:var(--texto-mid);margin:0 0 12px">{{ $planta->cientifico }}</p>
                @endif
                <div style="margin-bottom:14px">
                    @foreach ($planta->categorias as $cat)
                        <span class="badge-chip">{{ $cat->nombre }}</span>
                    @endforeach
                    @if ($planta->verificada)
                        <span class="badge-chip badge-dorado"><i class="fas fa-check-circle"></i> Verificada</span>
                    @endif
                </div>
                <p style="color:var(--texto-mid);line-height:1.6">{{ $planta->descripcion }}</p>

                @if ($planta->video_url)
                    <div class="rel-card" style="margin-top:14px">
                        <i class="fas fa-video" style="color:var(--dorado);margin-right:6px"></i>
                        <strong>{{ $planta->video_persona_nombre ?? 'Video testimonial' }}</strong>
                        @if ($planta->video_persona_rol)
                            <span class="badge-chip">Rol: {{ $planta->video_persona_rol }}</span>
                        @endif
                    </div>
                @endif

                <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap">
                    @if ($planta->modelos3d->isNotEmpty())
                        <a href="#panel-3d" class="btn btn-primary" onclick="event.preventDefault(); document.querySelector('[data-tab=3d]')?.click(); document.getElementById('panel-3d')?.scrollIntoView({behavior:'smooth'})"><i class="fas fa-cube"></i> Ver en 3D</a>
                    @endif
                    <a href="{{ route('aportes.create', ['planta_id' => $planta->id]) }}" class="btn btn-dorado btn-sm"
                       @guest onclick="event.preventDefault();window.location='{{ route('login') }}'" @endguest>
                        <i class="fas fa-plus"></i> Aportar
                    </a>
                </div>
            </div>
        </div>

        <div class="tabs" id="tabs">
            <button class="tab-btn active" data-tab="info" onclick="cambiarTab(this)"><i class="fas fa-info-circle"></i> Información</button>
            <button class="tab-btn" data-tab="recetas" onclick="cambiarTab(this)"><i class="fas fa-utensils"></i> Recetas ({{ $planta->recetas->count() }})</button>
            <button class="tab-btn" data-tab="medicina" onclick="cambiarTab(this)"><i class="fas fa-heartbeat"></i> Medicina ({{ $planta->tratamientos->count() }})</button>
            <button class="tab-btn" data-tab="animales" onclick="cambiarTab(this)"><i class="fas fa-paw"></i> Animales ({{ $planta->animales->count() }})</button>
            <button class="tab-btn" data-tab="3d" onclick="cambiarTab(this)"><i class="fas fa-cube"></i> 3D</button>
        </div>

        <div id="panel-info" class="tab-panel active">
            <div class="rel-card">
                <h3 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 8px"><i class="fas fa-book-open"></i> Contexto cultural</h3>
                <p>{{ $planta->contexto ?? 'Sin información de contexto aún.' }}</p>
            </div>
            @if ($planta->relato)
                <div class="rel-card">
                    <h3 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 8px"><i class="fas fa-comment-dots"></i> Relato</h3>
                    <p style="font-style:italic">{{ $planta->relato }}</p>
                </div>
            @endif
            @if ($planta->instrucciones)
                <div class="rel-card">
                    <h3 style="font-family:'Lora',serif;color:var(--verde);margin:0 0 8px"><i class="fas fa-tools"></i> Instrucciones de uso</h3>
                    <p>{!! nl2br(e($planta->instrucciones)) !!}</p>
                </div>
            @endif
        </div>

        <div id="panel-recetas" class="tab-panel">
            @forelse ($planta->recetas as $receta)
                <div class="rel-card">
                    <a href="{{ route('recetas.show', $receta) }}">{{ $receta->titulo }}</a>
                    <div class="rel-detail">
                        <i class="fas fa-seedling"></i> Parte usada: {{ $receta->pivot->parte_usada ?? 'variada' }}
                        @if ($receta->pivot->cantidad)
                            · Cantidad: {{ $receta->pivot->cantidad }}
                        @endif
                        @if ($receta->tiempo_preparacion)
                            · <i class="fas fa-clock"></i> {{ $receta->tiempo_preparacion }} min
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty" style="text-align:center;color:var(--texto-suave);padding:30px"><i class="fas fa-utensils"></i> Aún no hay recetas asociadas.</div>
            @endforelse
        </div>

        <div id="panel-medicina" class="tab-panel">
            @forelse ($planta->tratamientos as $tratamiento)
                <div class="rel-card">
                    <a href="{{ route('tratamientos.show', $tratamiento) }}">{{ $tratamiento->sintoma }}</a>
                    <span class="badge-chip badge-dorado">Gravedad: {{ $tratamiento->gravedad }}</span>
                    <div class="rel-detail">
                        <i class="fas fa-leaf"></i> Parte usada: {{ $tratamiento->pivot->parte_usada ?? 'variada' }}
                        @if ($tratamiento->pivot->preparacion)
                            · Preparación: {{ $tratamiento->pivot->preparacion }}
                        @endif
                    </div>
                    @if ($tratamiento->descripcion)
                        <p style="color:var(--texto-mid);margin-top:8px">{{ $tratamiento->descripcion }}</p>
                    @endif
                </div>
            @empty
                <div class="empty" style="text-align:center;color:var(--texto-suave);padding:30px"><i class="fas fa-heartbeat"></i> Sin usos medicinales registrados.</div>
            @endforelse
        </div>

        <div id="panel-animales" class="tab-panel">
            @forelse ($planta->animales as $animal)
                <div class="rel-card">
                    <a href="{{ route('animales.show', $animal) }}">{{ $animal->nombre }}</a>
                    <div class="rel-detail">
                        Tipo de consumo: <strong>{{ $animal->pivot->tipo_consumo }}</strong>
                    </div>
                </div>
            @empty
                <div class="empty" style="text-align:center;color:var(--texto-suave);padding:30px"><i class="fas fa-paw"></i> Sin animales asociados.</div>
            @endforelse
        </div>

        <div id="panel-3d" class="tab-panel">
            @include('visor.show', ['planta' => $planta, 'modelos' => $planta->modelos3d, 'primerModelo' => $planta->modelos3d->first()])
        </div>

        <div style="margin-top:28px">
            <h3 style="font-family:'Lora',serif;color:var(--verde)">Comentarios ({{ $planta->comentarios->count() }})</h3>
            @auth
                <form method="POST" action="{{ route('comentarios.store', $planta) }}" style="display:flex;gap:10px;margin:14px 0">
                    @csrf
                    <input type="text" name="cuerpo" required placeholder="Escribe un comentario…" class="form-control">
                    <button class="btn btn-primary btn-sm"><i class="fas fa-paper-plane"></i> Publicar</button>
                </form>
            @endauth
            @foreach ($planta->comentarios as $comentario)
                <div class="rel-card">
                    <strong>{{ $comentario->user->name }}</strong> · <small style="color:var(--texto-suave)">{{ $comentario->created_at->diffForHumans() }}</small>
                    <p style="margin:8px 0 0;color:var(--texto-mid)">{{ $comentario->cuerpo }}</p>
                    <div style="margin-top:8px;display:flex;gap:12px;align-items:center">
                        <form method="POST" action="{{ route('comentarios.like', $comentario) }}">
                            @csrf
                            <button class="btn-link"><i class="fas fa-heart" style="color:#e53935"></i> {{ $comentario->likes }}</button>
                        </form>
                        @auth
                            @if (auth()->user()->id === $comentario->user_id || auth()->user()->hasAnyRole(['admin', 'moderador']))
                                <form method="POST" action="{{ route('comentarios.destroy', $comentario) }}" onsubmit="return confirm('¿Eliminar comentario?')">
                                    @csrf @method('DELETE')
                                    <button class="btn-link" style="color:var(--danger)">Eliminar</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    function cambiarTab(btn) {
        document.querySelectorAll('#tabs .tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const tab = btn.dataset.tab;
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        const panel = document.getElementById('panel-' + tab);
        if (panel) panel.classList.add('active');

        if (tab === '3d') {
            if (typeof window.inicializarVisor3D === 'function') {
                window.inicializarVisor3D();
            }
        }
    }
</script>
@endpush
@endsection
