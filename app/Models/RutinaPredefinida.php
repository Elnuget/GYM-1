<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RutinaPredefinida extends Model
{
    protected $table = 'rutinas_predefinidas';
    protected $primaryKey = 'id_rutina';
    
    protected $fillable = [
        'nombre_rutina',
        'descripcion',
        'objetivo',
        'estado',
        'id_entrenador',
        'fecha_creacion'
    ];

    protected $casts = [
        'fecha_creacion' => 'date'
    ];

    public function entrenador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_entrenador', 'id');
    }

    public function getRouteKeyName()
    {
        return 'id_rutina';
    }
} 