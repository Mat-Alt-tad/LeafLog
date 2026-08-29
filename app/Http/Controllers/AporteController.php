<?php

namespace App\Http\Controllers;

use App\Models\Aporte;
use App\Models\Planta;
use Illuminate\Http\Request;

class AporteController extends Controller
{
    public function index()
    {
        $aportes = Aporte::with(['user', 'planta'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('aportes.index', compact('aportes'));
    }

    public function create()
    {
        $plantas = Planta::orderBy('nombre')->get();
        return view('aportes.create', compact('plantas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'planta_id' => 'required|exists:plantas,id',
            'contenido' => 'required|string|max:2000',
        ]);

        Aporte::create([
            'user_id' => $request->user()->id,
            'planta_id' => $validated['planta_id'],
            'estado' => 'pendiente',
            'contenido' => $validated['contenido'],
        ]);

        return redirect()->route('aportes.index')->with('status', 'Aporte enviado. Pasó a moderación.');
    }

    public function aprobar(Aporte $aporte)
    {
        $aporte->update(['estado' => 'aprobado']);
        return back()->with('status', 'Aporte aprobado y publicado.');
    }

    public function rechazar(Request $request, Aporte $aporte)
    {
        $request->validate(['motivo' => 'nullable|string|max:500']);
        $aporte->update(['estado' => 'rechazado']);
        return back()->with('status', 'Aporte rechazado.');
    }
}
