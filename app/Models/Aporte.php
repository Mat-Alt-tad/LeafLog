<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aporte extends Model
{
    protected $fillable = ['user_id', 'planta_id', 'estado', 'contenido'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function planta(): BelongsTo
    {
        return $this->belongsTo(Planta::class);
    }
}
