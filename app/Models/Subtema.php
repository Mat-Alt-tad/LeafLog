<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subtema extends Model
{
    protected $fillable = ['categoria_id', 'nombre'];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function plantas(): HasMany
    {
        return $this->hasMany(Planta::class);
    }
}
