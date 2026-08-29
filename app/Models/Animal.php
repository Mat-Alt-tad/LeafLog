<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Animal extends Model
{
    protected $table = 'animales';

    protected $fillable = ['nombre', 'tipo', 'descripcion', 'imagen'];

    public function plantas(): BelongsToMany
    {
        return $this->belongsToMany(Planta::class, 'planta_animal')
            ->withPivot('tipo_consumo');
    }
}
