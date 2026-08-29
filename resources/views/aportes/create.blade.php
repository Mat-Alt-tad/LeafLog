@extends('layouts.leaf')

@section('title', 'Nuevo aporte · ' . config('app.name'))

@section('content')
<section class="metas">
    <div class="container" style="max-width:720px">
        <h2>Nuevo aporte</h2>
        <p style="color:var(--texto-mid);margin-bottom:20px">Comparte un conocimiento del territorio sobre una planta. Tu aporte pasará a moderación antes de publicarse.</p>

        @if ($errors->any())
            <div class="alert-error">
                <ul style="margin:0;padding-left:16px">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('aportes.store') }}" class="form-card">
            @csrf
            <div class="form-group">
                <label>Planta</label>
                <select name="planta_id" class="form-control" required>
                    <option value="">Selecciona una planta…</option>
                    @foreach ($plantas as $planta)
                        <option value="{{ $planta->id }}" {{ request('planta_id') == $planta->id ? 'selected' : '' }}>{{ $planta->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Contenido del aporte</label>
                <textarea name="contenido" class="form-control" required rows="6" placeholder="Describe el conocimiento, receta, uso medicinal, relato…"></textarea>
            </div>
            <button class="btn btn-dorado"><i class="fas fa-paper-plane"></i> Enviar aporte</button>
        </form>
    </div>
</section>
@endsection
