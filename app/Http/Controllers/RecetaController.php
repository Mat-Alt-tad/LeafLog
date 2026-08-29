<?php

namespace App\Http\Controllers;

use App\Models\Planta;
use App\Models\Receta;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    public function index()
    {
        $recetas = Receta::with('plantas')->orderBy('titulo')->paginate(12);
        return view('recetas.index', compact('recetas'));
    }

    public function show(Receta $receta)
    {
        $receta->load(['plantas' => fn ($p) => $p->withPivot('cantidad', 'parte_usada')]);
        return view('recetas.show', compact('receta'));
    }

    public function create()
    {
        $plantas = Planta::orderBy('nombre')->get();
        return view('admin.recetas.create', compact('plantas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'instrucciones' => 'nullable|string',
            'tiempo_preparacion' => 'nullable|integer',
            'porciones' => 'nullable|integer',
            'plantas' => 'nullable|array',
            'cantidades' => 'nullable|array',
            'partes_usadas' => 'nullable|array',
        ]);

        $receta = Receta::create([
            'titulo' => $validated['titulo'],
            'instrucciones' => $validated['instrucciones'] ?? null,
            'tiempo_preparacion' => $validated['tiempo_preparacion'] ?? null,
            'porciones' => $validated['porciones'] ?? null,
        ]);

        $this->syncPlantas($receta, $request);

        return redirect()->route('admin.recetas.index')->with('status', 'Receta creada.');
    }

    public function edit(Receta $receta)
    {
        $receta->load(['plantas' => fn ($p) => $p->withPivot('cantidad', 'parte_usada')]);
        $plantas = Planta::orderBy('nombre')->get();
        return view('admin.recetas.edit', compact('receta', 'plantas'));
    }

    public function update(Request $request, Receta $receta)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'instrucciones' => 'nullable|string',
            'tiempo_preparacion' => 'nullable|integer',
            'porciones' => 'nullable|integer',
            'plantas' => 'nullable|array',
            'cantidades' => 'nullable|array',
            'partes_usadas' => 'nullable|array',
        ]);

        $receta->update([
            'titulo' => $validated['titulo'],
            'instrucciones' => $validated['instrucciones'] ?? null,
            'tiempo_preparacion' => $validated['tiempo_preparacion'] ?? null,
            'porciones' => $validated['porciones'] ?? null,
        ]);

        $this->syncPlantas($receta, $request);

        return redirect()->route('admin.recetas.index')->with('status', 'Receta actualizada.');
    }

    public function destroy(Receta $receta)
    {
        $receta->plantas()->detach();
        $receta->delete();
        return redirect()->route('admin.recetas.index')->with('status', 'Receta eliminada.');
    }

    protected function syncPlantas(Receta $receta, Request $request)
    {
        $sync = [];
        foreach ($request->input('plantas', []) as $index => $plantaId) {
            $sync[$plantaId] = [
                'cantidad' => $request->input('cantidades')[$index] ?? null,
                'parte_usada' => $request->input('partes_usadas')[$index] ?? null,
            ];
        }
        $receta->plantas()->sync($sync);
    }
}
