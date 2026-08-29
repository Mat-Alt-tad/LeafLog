<?php

namespace App\Http\Controllers;

use App\Models\Modelo3d;
use App\Models\Planta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisorController extends Controller
{
    public function show(Planta $planta)
    {
        $planta->load('modelos3d');
        $primerModelo = $planta->modelos3d->first();

        return view('visor.show', [
            'planta' => $planta,
            'modelos' => $planta->modelos3d,
            'primerModelo' => $primerModelo,
        ]);
    }

    public function store(Request $request, Planta $planta)
    {
        $request->validate([
            'archivo_glb' => 'required|file|max:51200',
            'tipo' => 'nullable|string|max:255',
        ]);

        $extension = strtolower($request->file('archivo_glb')->getClientOriginalExtension());
        if (!in_array($extension, ['glb', 'gltf', 'bin'])) {
            return back()->withErrors(['archivo_glb' => 'El archivo debe tener extensión .glb, .gltf o .bin.']);
        }

        $path = $request->file('archivo_glb')->store('modelos', 'public');

        $tipoInput = strtolower($request->input('tipo', 'glb'));
        if ($tipoInput === 'fotogrametria') {
            $tipo = 'fotogrametria';
        } elseif ($tipoInput === 'diseño' || $tipoInput === 'diseno' || $tipoInput === '3d') {
            $tipo = 'diseño';
        } else {
            $tipo = 'glb';
        }

        Modelo3d::create([
            'planta_id' => $planta->id,
            'archivo_glb' => $path,
            'tipo' => $tipo,
            'fecha_subida' => now(),
        ]);

        return back()->with('status', 'Modelo 3D subido correctamente.');
    }

    public function destroy(Modelo3d $modelo)
    {
        Storage::disk('public')->delete($modelo->archivo_glb);
        $modelo->delete();
        return back()->with('status', 'Modelo 3D eliminado.');
    }
}
