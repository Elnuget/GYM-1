<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RutinaPredefinida extends Model
{
    use HasFactory;

    protected $table = 'rutinas_predefinidas';
    protected $primaryKey = 'id_rutina';
    
    protected $fillable = [
        'nombre_rutina',
        'objetivo',
        'nivel',
        'duracion',
        'activo',
        'gimnasio_id',
        'descripcion',
        'fecha_creacion'
    ];

    protected $casts = [
        'duracion' => 'integer',
        'activo' => 'boolean'
    ];

    public function entrenador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_entrenador', 'id');
    }

    public function ejercicios(): BelongsToMany
    {
        return $this->belongsToMany(Ejercicio::class, 'rutina_ejercicios', 'rutina_id', 'ejercicio_id')
                    ->withPivot(['dia', 'orden', 'series', 'repeticiones', 'peso_sugerido', 'notas'])
                    ->orderBy('dia')
                    ->orderBy('orden');
    }

    public function rutinasCliente(): HasMany
    {
        return $this->hasMany(RutinaCliente::class, 'rutina_id', 'id_rutina');
    }

    public function ejerciciosPorDia()
    {
        return $this->ejercicios->groupBy('pivot.dia');
    }

    public function getRouteKeyName()
    {
        return 'id_rutina';
    }

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id', 'id_gimnasio');
    }
} 