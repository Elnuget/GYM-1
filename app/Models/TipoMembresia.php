<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoMembresia extends Model
{
    use HasFactory;

    protected $table = 'tipos_membresia';
    protected $primaryKey = 'id_tipo_membresia';
    
    protected $fillable = [
        'gimnasio_id',
        'nombre',
        'descripcion',
        'precio',
        'duracion_dias',
        'tipo',
        'numero_visitas',
        'estado'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'duracion_dias' => 'integer',
        'numero_visitas' => 'integer',
        'estado' => 'boolean'
    ];

    /**
     * Obtiene el gimnasio al que pertenece este tipo de membresía
     */
    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id', 'id_gimnasio');
    }
} 