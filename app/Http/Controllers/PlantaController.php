<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Planta;
use App\Models\Subtema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PlantaController extends Controller
{
    public function index(Request $request)
    {
        $query = Planta::with(['categorias', 'subtema'])
            ->where('verificada', true);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($w) use ($q) {
                $w->where('nombre', 'like', "%$q%")
                    ->orWhere('cientifico', 'like', "%$q%")
                    ->orWhere('tags', 'like', "%$q%");
            });
        }

        if ($request->filled('categoria')) {
            $query->whereHas('categorias', fn ($c) => $c->where('categorias.id', $request->input('categoria')));
        }

        $plantas = $query->orderBy('nombre')->paginate(12)->withQueryString();
        $categorias = Categoria::orderBy('orden')->get();

        return view('plantas.index', compact('plantas', 'categorias'));
    }

    public function show(Planta $planta)
    {
        $planta->load([
            'categorias',
            'subtema',
            'recetas' => fn ($r) => $r->withPivot('cantidad', 'parte_usada'),
            'animales' => fn ($a) => $a->withPivot('tipo_consumo'),
            'tratamientos' => fn ($t) => $t->withPivot('parte_usada', 'preparacion'),
            'modelos3d',
            'comentarios.user',
        ]);

        return view('plantas.show', compact('planta'));
    }

    public function create()
    {
        $categorias = Categoria::with('subtemas')->orderBy('orden')->get();
        return view('admin.plantas.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cientifico' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'instrucciones' => 'nullable|string',
            'contexto' => 'nullable|string',
            'relato' => 'nullable|string',
            'subtema_id' => 'nullable|exists:subtemas,id',
            'tags' => 'nullable|string',
            'verificada' => 'nullable|boolean',
            'video_url' => 'nullable|string|max:255',
            'video_persona_nombre' => 'nullable|string|max:255',
            'video_persona_rol' => 'nullable|string|max:255',
            'video_validado' => 'nullable|boolean',
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:categorias,id',
            'imagen' => 'nullable|image|max:5120',
        ]);

        $planta = Planta::create([
            'nombre' => $validated['nombre'],
            'cientifico' => $validated['cientifico'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'instrucciones' => $validated['instrucciones'] ?? null,
            'contexto' => $validated['contexto'] ?? null,
            'relato' => $validated['relato'] ?? null,
            'subtema_id' => $validated['subtema_id'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'verificada' => $validated['verificada'] ?? false,
            'video_url' => $validated['video_url'] ?? null,
            'video_persona_nombre' => $validated['video_persona_nombre'] ?? null,
            'video_persona_rol' => $validated['video_persona_rol'] ?? null,
            'video_validado' => $validated['video_validado'] ?? false,
        ]);

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('plantas', 'public');
            $planta->update(['img_path' => $path]);
        }

        if (!empty($validated['categorias'])) {
            $planta->categorias()->sync($validated['categorias']);
        }

        return redirect()->route('admin.plantas.index')->with('status', 'Planta creada correctamente.');
    }

    public function edit(Planta $planta)
    {
        $planta->load('categorias');
        $categorias = Categoria::with('subtemas')->orderBy('orden')->get();
        return view('admin.plantas.edit', compact('planta', 'categorias'));
    }

    public function update(Request $request, Planta $planta)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cientifico' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'instrucciones' => 'nullable|string',
            'contexto' => 'nullable|string',
            'relato' => 'nullable|string',
            'subtema_id' => 'nullable|exists:subtemas,id',
            'tags' => 'nullable|string',
            'verificada' => 'nullable|boolean',
            'video_url' => 'nullable|string|max:255',
            'video_persona_nombre' => 'nullable|string|max:255',
            'video_persona_rol' => 'nullable|string|max:255',
            'video_validado' => 'nullable|boolean',
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:categorias,id',
            'imagen' => 'nullable|image|max:5120',
        ]);

        $planta->update([
            'nombre' => $validated['nombre'],
            'cientifico' => $validated['cientifico'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'instrucciones' => $validated['instrucciones'] ?? null,
            'contexto' => $validated['contexto'] ?? null,
            'relato' => $validated['relato'] ?? null,
            'subtema_id' => $validated['subtema_id'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'verificada' => $validated['verificada'] ?? false,
            'video_url' => $validated['video_url'] ?? null,
            'video_persona_nombre' => $validated['video_persona_nombre'] ?? null,
            'video_persona_rol' => $validated['video_persona_rol'] ?? null,
            'video_validado' => $validated['video_validado'] ?? false,
        ]);

        if ($request->hasFile('imagen')) {
            if ($planta->img_path) {
                Storage::disk('public')->delete($planta->img_path);
            }
            $path = $request->file('imagen')->store('plantas', 'public');
            $planta->update(['img_path' => $path]);
        }

        if (isset($validated['categorias'])) {
            $planta->categorias()->sync($validated['categorias']);
        }

        return redirect()->route('admin.plantas.index')->with('status', 'Planta actualizada correctamente.');
    }

    public function destroy(Planta $planta)
    {
        if ($planta->img_path) {
            Storage::disk('public')->delete($planta->img_path);
        }
        foreach ($planta->modelos3d as $modelo) {
            Storage::disk('public')->delete($modelo->archivo_glb);
        }
        $planta->delete();

        return redirect()->route('admin.plantas.index')->with('status', 'Planta eliminada.');
    }
}
