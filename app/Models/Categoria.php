<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'icono', 'orden'];

    public function plantas(): BelongsToMany
    {
        return $this->belongsToMany(Planta::class, 'categoria_planta');
    }

    public function subtemas(): HasMany
    {
        return $this->hasMany(Subtema::class);
    }
}
