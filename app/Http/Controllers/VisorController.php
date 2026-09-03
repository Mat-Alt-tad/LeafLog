<?php

namespace App\Http\Controllers;

use App\Models\Modelo3d;
use App\Models\Planta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        // Determinar tipo de contenido: GLB o 4 imágenes
        $contenidoTipo = strtolower($request->input('contenido_tipo', 'glb'));

        if ($contenidoTipo === 'imagenes') {
            // Manejar 4 imágenes
            $this->storeImagenes($request, $planta);
        } else {
            // Manejar archivo GLB (comportamiento existente)
            $this->storeGlb($request, $planta);
        }
    }

    protected function storeGlb(Request $request, Planta $planta)
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
            'contenido_tipo' => 'glb',
            'fecha_subida' => now(),
        ]);

        return back()->with('status', 'Modelo 3D subido correctamente.');
    }

    protected function storeImagenes(Request $request, Planta $planta)
    {
        $this->validate($request, [
            'imagen_1' => 'required|image|max:2048',
            'imagen_2' => 'required|image|max:2048',
            'imagen_3' => 'required|image|max:2048',
            'imagen_4' => 'required|image|max:2048',
        ]);

        $slug = Str::slug($planta->nombre);
        $nombres = [];
        $carpetas = ['frente', 'atras', 'izquierda', 'derecha'];

        foreach ($carpetas as $index => $posicion) {
            $imagen = $request->file("imagen_$index");
            $path = $imagen->store("modelos/imagenes/{$slug}", 'public');
            $nombres[] = $path;
        }

        Modelo3d::create([
            'planta_id' => $planta->id,
            'archivo_glb' => json_encode($nombres),
            'tipo' => 'imagenes',
            'contenido_tipo' => 'imagenes',
            'fecha_subida' => now(),
        ]);

        return back()->with('status', '4 imágenes subidas correctamente para modo holograma.');
    }

    public function destroy(Modelo3d $modelo)
    {
        // Si es tipo imagenes, las rutas están JSON en archivo_glb
        if ($modelo->contenido_tipo === 'imagenes') {
            $rutas = json_decode($modelo->archivo_glb, true);
            foreach ($rutas as $ruta) {
                Storage::disk('public')->delete($ruta);
            }
        } else {
            Storage::disk('public')->delete($modelo->archivo_glb);
        }

        $modelo->delete();
        return back()->with('status', 'Modelo 3D eliminado.');
    }
}
