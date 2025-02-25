<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ejercicio extends Model
{
    use HasFactory;

    protected $table = 'ejercicios';
    protected $primaryKey = 'id_ejercicio';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen_url',
        'video_url',
        'instrucciones',
        'grupo_muscular',
        'equipamiento_necesario',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function rutinas(): BelongsToMany
    {
        return $this->belongsToMany(RutinaPredefinida::class, 'rutina_ejercicios', 'ejercicio_id', 'rutina_id')
                    ->withPivot(['dia', 'orden', 'series', 'repeticiones', 'peso_sugerido', 'notas']);
    }
} 