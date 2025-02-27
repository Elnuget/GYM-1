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
        'tipo',
        'grupo_muscular',
        'imagen_url',
        'video_url'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function rutinas()
    {
        return $this->hasMany(EjercicioRutina::class, 'ejercicio_id', 'id_ejercicio');
    }
} 