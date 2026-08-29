<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Planta;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function index()
    {
        $animales = Animal::with('plantas')->orderBy('nombre')->paginate(12);
        return view('animales.index', compact('animales'));
    }

    public function show(Animal $animal)
    {
        $animal->load(['plantas' => fn ($p) => $p->withPivot('tipo_consumo')]);
        return view('animales.show', compact('animal'));
    }

    public function create()
    {
        return view('admin.animales.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Animal::create([
            'nombre' => $validated['nombre'],
            'tipo' => $validated['tipo'] ?? 'domestico',
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        return redirect()->route('admin.animales.index')->with('status', 'Animal creado.');
    }

    public function edit(Animal $animal)
    {
        return view('admin.animales.edit', compact('animal'));
    }

    public function update(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $animal->update([
            'nombre' => $validated['nombre'],
            'tipo' => $validated['tipo'] ?? 'domestico',
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        return redirect()->route('admin.animales.index')->with('status', 'Animal actualizado.');
    }

    public function destroy(Animal $animal)
    {
        $animal->plantas()->detach();
        $animal->delete();
        return redirect()->route('admin.animales.index')->with('status', 'Animal eliminado.');
    }

    public function attachPlantas(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'plantas' => 'required|array',
            'plantas.*' => 'exists:plantas,id',
            'tipos_consumo' => 'nullable|array',
        ]);

        $sync = [];
        foreach ($validated['plantas'] as $index => $plantaId) {
            $sync[$plantaId] = [
                'tipo_consumo' => $request->input('tipos_consumo')[$index] ?? 'complemento',
            ];
        }
        $animal->plantas()->sync($sync);

        return back()->with('status', 'Plantas asociadas.');
    }
}
