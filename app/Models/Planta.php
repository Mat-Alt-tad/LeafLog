<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planta extends Model
{
    protected $fillable = [
        'nombre',
        'cientifico',
        'descripcion',
        'instrucciones',
        'contexto',
        'relato',
        'img_url',
        'img_path',
        'video_url',
        'video_persona_nombre',
        'video_persona_rol',
        'video_validado',
        'subtema_id',
        'tags',
        'verificada',
    ];

    protected $casts = [
        'verificada' => 'boolean',
        'video_validado' => 'boolean',
    ];

    public function subtema(): BelongsTo
    {
        return $this->belongsTo(Subtema::class);
    }

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'categoria_planta');
    }

    public function recetas(): BelongsToMany
    {
        return $this->belongsToMany(Receta::class, 'planta_receta')
            ->withPivot('cantidad', 'parte_usada');
    }

    public function animales(): BelongsToMany
    {
        return $this->belongsToMany(Animal::class, 'planta_animal')
            ->withPivot('tipo_consumo');
    }

    public function tratamientos(): BelongsToMany
    {
        return $this->belongsToMany(Tratamiento::class, 'planta_tratamiento')
            ->withPivot('parte_usada', 'preparacion');
    }

    public function modelos3d(): HasMany
    {
        return $this->hasMany(Modelo3d::class);
    }

    public function aportes(): HasMany
    {
        return $this->hasMany(Aporte::class);
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class);
    }
}
