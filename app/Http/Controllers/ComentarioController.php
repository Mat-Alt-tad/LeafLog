<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Planta;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function store(Request $request, Planta $planta)
    {
        $validated = $request->validate([
            'cuerpo' => 'required|string|max:1000',
        ]);

        Comentario::create([
            'user_id' => $request->user()->id,
            'planta_id' => $planta->id,
            'cuerpo' => $validated['cuerpo'],
            'likes' => 0,
        ]);

        return back()->with('status', 'Comentario publicado.');
    }

    public function like(Comentario $comentario)
    {
        $comentario->increment('likes');
        return back();
    }

    public function destroy(Comentario $comentario)
    {
        $comentario->delete();
        return back()->with('status', 'Comentario eliminado.');
    }
}
