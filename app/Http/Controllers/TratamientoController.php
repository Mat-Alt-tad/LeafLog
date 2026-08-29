<?php

namespace App\Http\Controllers;

use App\Models\Planta;
use App\Models\Tratamiento;
use Illuminate\Http\Request;

class TratamientoController extends Controller
{
    public function index()
    {
        $tratamientos = Tratamiento::with('plantas')->orderBy('sintoma')->paginate(12);
        return view('tratamientos.index', compact('tratamientos'));
    }

    public function show(Tratamiento $tratamiento)
    {
        $tratamiento->load(['plantas' => fn ($p) => $p->withPivot('parte_usada', 'preparacion')]);
        return view('tratamientos.show', compact('tratamiento'));
    }

    public function create()
    {
        return view('admin.tratamientos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sintoma' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'gravedad' => 'required|in:baja,media,alta',
        ]);

        Tratamiento::create($validated);

        return redirect()->route('admin.tratamientos.index')->with('status', 'Tratamiento creado.');
    }

    public function edit(Tratamiento $tratamiento)
    {
        return view('admin.tratamientos.edit', compact('tratamiento'));
    }

    public function update(Request $request, Tratamiento $tratamiento)
    {
        $validated = $request->validate([
            'sintoma' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'gravedad' => 'required|in:baja,media,alta',
        ]);

        $tratamiento->update($validated);

        return redirect()->route('admin.tratamientos.index')->with('status', 'Tratamiento actualizado.');
    }

    public function destroy(Tratamiento $tratamiento)
    {
        $tratamiento->plantas()->detach();
        $tratamiento->delete();
        return redirect()->route('admin.tratamientos.index')->with('status', 'Tratamiento eliminado.');
    }
}
