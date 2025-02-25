<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RutinaCliente extends Model
{
    use HasFactory;

    protected $table = 'rutinas_cliente';
    protected $primaryKey = 'id_rutina_cliente';

    protected $fillable = [
        'cliente_id',
        'rutina_id',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'progreso',
        'notas_entrenador'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'progreso' => 'integer'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    public function rutina(): BelongsTo
    {
        return $this->belongsTo(RutinaPredefinida::class, 'rutina_id', 'id_rutina');
    }
} 