<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObjetivoCliente extends Model
{
    use HasFactory;

    protected $table = 'objetivos_cliente';
    protected $primaryKey = 'id_objetivo';

    protected $fillable = [
        'cliente_id',
        'objetivo_principal',
        'nivel_experiencia',
        'dias_entrenamiento',
        'condiciones_medicas',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }
} 