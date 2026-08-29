@extends('layouts.leaf')

@section('title', 'Mis aportes · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container" style="max-width:900px">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px">
            <h2 style="margin:0">Mis aportes</h2>
            <a href="{{ route('aportes.create') }}" class="btn btn-dorado"><i class="fas fa-plus"></i> Nuevo aporte</a>
        </div>

        @if ($aportes->isEmpty())
            <div class="empty" style="text-align:center;color:var(--texto-suave);padding:50px 20px">
                <i class="fas fa-inbox" style="font-size:3rem;opacity:.3;display:block;margin-bottom:12px"></i>
                Aún no has hecho aportes. Aporta tu conocimiento del territorio.
            </div>
        @endif

        @foreach ($aportes as $aporte)
            <div class="rel-card">
                <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px">
                    <a href="{{ route('plantas.show', $aporte->planta) }}" style="font-weight:700;color:var(--verde);font-family:'Lora',serif">
                        {{ $aporte->planta->nombre }}
                    </a>
                    @if ($aporte->estado === 'pendiente')
                        <span class="badge-chip">En moderación</span>
                    @elseif ($aporte->estado === 'aprobado')
                        <span class="badge-chip badge-dorado"><i class="fas fa-check-circle"></i> Aprobado</span>
                    @else
                        <span class="badge-chip" style="background:rgba(229,57,53,.12);color:var(--danger)">Rechazado</span>
                    @endif
                </div>
                <p style="color:var(--texto-mid);margin:10px 0 6px">{{ $aporte->contenido }}</p>
                <small style="color:var(--texto-suave)">{{ $aporte->created_at->diffForHumans() }}</small>

                @if (auth()->user()->hasAnyRole(['admin', 'moderador']) && $aporte->estado === 'pendiente')
                    <div style="display:flex;gap:8px;margin-top:12px">
                        <form method="POST" action="{{ route('aportes.aprobar', $aporte) }}">
                            @csrf
                            <button class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Aprobar</button>
                        </form>
                        <form method="POST" action="{{ route('aportes.rechazar', $aporte) }}">
                            @csrf
                            <button class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Rechazar</button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach

        <div style="margin-top:24px">{{ $aportes->links() }}</div>
    </div>
</section>
@endsection
