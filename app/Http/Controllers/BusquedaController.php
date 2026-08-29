<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Planta;
use App\Models\Receta;
use App\Models\Tratamiento;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));
        $resultados = collect();

        if ($q !== '') {
            $resultados = $this->buscarTodos($q);
        }

        return view('busqueda.index', compact('q', 'resultados'));
    }

    public function autocompletar(Request $request)
    {
        $q = trim($request->input('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        $plantas = Planta::where('nombre', 'like', "%$q%")
            ->orWhere('cientifico', 'like', "%$q%")
            ->limit(5)->get()->map(fn ($p) => [
                'tipo' => 'planta',
                'titulo' => $p->nombre,
                'sub' => $p->cientifico,
                'url' => route('plantas.show', $p),
            ]);

        $recetas = Receta::where('titulo', 'like', "%$q%")
            ->limit(3)->get()->map(fn ($r) => [
                'tipo' => 'receta',
                'titulo' => $r->titulo,
                'url' => route('recetas.show', $r),
            ]);

        $animales = Animal::where('nombre', 'like', "%$q%")
            ->limit(3)->get()->map(fn ($a) => [
                'tipo' => 'animal',
                'titulo' => $a->nombre,
                'url' => route('animales.show', $a),
            ]);

        $tratamientos = Tratamiento::where('sintoma', 'like', "%$q%")
            ->limit(3)->get()->map(fn ($t) => [
                'tipo' => 'tratamiento',
                'titulo' => $t->sintoma,
                'url' => route('tratamientos.show', $t),
            ]);

        return response()->json([
            'plantas' => $plantas,
            'recetas' => $recetas,
            'animales' => $animales,
            'tratamientos' => $tratamientos,
        ]);
    }

    protected function buscarTodos(string $q)
    {
        $resultados = collect();

        $plantas = Planta::where('verificada', true)
            ->where(function ($w) use ($q) {
                $w->where('nombre', 'like', "%$q%")
                    ->orWhere('cientifico', 'like', "%$q%")
                    ->orWhere('tags', 'like', "%$q%");
            })->get();
        $resultados->put('Plantas', $plantas->map(fn ($p) => [
            'tipo' => 'planta',
            'titulo' => $p->nombre,
            'sub' => $p->cientifico,
            'url' => route('plantas.show', $p),
        ]));

        $recetas = Receta::where('titulo', 'like', "%$q%")->get();
        $resultados->put('Recetas', $recetas->map(fn ($r) => [
            'tipo' => 'receta',
            'titulo' => $r->titulo,
            'url' => route('recetas.show', $r),
        ]));

        $animales = Animal::where('nombre', 'like', "%$q%")->get();
        $resultados->put('Animales', $animales->map(fn ($a) => [
            'tipo' => 'animal',
            'titulo' => $a->nombre,
            'url' => route('animales.show', $a),
        ]));

        $tratamientos = Tratamiento::where('sintoma', 'like', "%$q%")->get();
        $resultados->put('Tratamientos', $tratamientos->map(fn ($t) => [
            'tipo' => 'tratamiento',
            'titulo' => $t->sintoma,
            'url' => route('tratamientos.show', $t),
        ]));

        return $resultados->filter(fn ($v) => $v->isNotEmpty());
    }
}
