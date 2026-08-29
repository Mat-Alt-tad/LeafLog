<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tratamiento extends Model
{
    protected $fillable = ['sintoma', 'descripcion', 'gravedad'];

    public function plantas(): BelongsToMany
    {
        return $this->belongsToMany(Planta::class, 'planta_tratamiento')
            ->withPivot('parte_usada', 'preparacion');
    }
}
