<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modelo3d extends Model
{
    protected $table = 'modelos_3d';

    protected $fillable = ['planta_id', 'archivo_glb', 'tipo', 'contenido_tipo', 'fecha_subida'];

    protected $casts = [
        'fecha_subida' => 'datetime',
    ];

    public function planta(): BelongsTo
    {
        return $this->belongsTo(Planta::class);
    }
}
