<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Receta extends Model
{
    protected $fillable = ['titulo', 'instrucciones', 'tiempo_preparacion', 'porciones', 'imagen'];

    public function plantas(): BelongsToMany
    {
        return $this->belongsToMany(Planta::class, 'planta_receta')
            ->withPivot('cantidad', 'parte_usada');
    }
}
