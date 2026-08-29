<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Aporte;
use App\Models\Categoria;
use App\Models\Modelo3d;
use App\Models\Planta;
use App\Models\Receta;
use App\Models\Tratamiento;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'plantas' => Planta::count(),
            'recetas' => Receta::count(),
            'animales' => Animal::count(),
            'tratamientos' => Tratamiento::count(),
            'modelos' => Modelo3d::count(),
            'aportes_pendientes' => Aporte::where('estado', 'pendiente')->count(),
            'usuarios' => User::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function plantas()
    {
        $plantas = Planta::with(['categorias', 'subtema'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.plantas.index', compact('plantas'));
    }

    public function recetas()
    {
        $recetas = Receta::with('plantas')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.recetas.index', compact('recetas'));
    }

    public function animales()
    {
        $animales = Animal::with('plantas')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.animales.index', compact('animales'));
    }

    public function tratamientos()
    {
        $tratamientos = Tratamiento::with('plantas')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.tratamientos.index', compact('tratamientos'));
    }

    public function usuarios()
    {
        $usuarios = User::with('roles')->orderBy('name')->paginate(20);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function categorias()
    {
        $categorias = Categoria::with('subtemas')->orderBy('orden')->get();
        return view('admin.categorias.index', compact('categorias'));
    }
}
