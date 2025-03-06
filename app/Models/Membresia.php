<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membresia extends Model
{
    use HasFactory;

    protected $table = 'membresias';
    protected $primaryKey = 'id_membresia';
    
    protected $fillable = [
        'id_usuario',
        'id_tipo_membresia',
        'precio_total',
        'saldo_pendiente',
        'fecha_compra',
        'fecha_vencimiento',
        'visitas_permitidas',
        'visitas_restantes',
        'renovacion'
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'fecha_vencimiento' => 'date',
        'renovacion' => 'boolean',
        'precio_total' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function tipoMembresia(): BelongsTo
    {
        return $this->belongsTo(TipoMembresia::class, 'id_tipo_membresia', 'id_tipo_membresia');
    }

    public function gimnasio()
    {
        if ($this->tipoMembresia && $this->tipoMembresia->gimnasio) {
            return $this->tipoMembresia->gimnasio;
        }

        return $this->belongsTo(Gimnasio::class, 'gimnasio_id', 'id_gimnasio')
            ->withDefault([
                'nombre' => 'Gimnasio no asignado',
                'direccion' => 'Dirección no disponible'
            ]);
    }

    public function getGimnasioNombreAttribute()
    {
        return $this->tipoMembresia->gimnasio->nombre ?? 'Gimnasio no asignado';
    }

    public function getGimnasioDireccionAttribute()
    {
        return $this->tipoMembresia->gimnasio->direccion ?? 'Dirección no disponible';
    }

    public function registrarVisita()
    {
        if ($this->visitas_restantes > 0) {
            $this->visitas_restantes--;
            $this->save();
        }
    }

    public function getDuracionAttribute()
    {
        if (!$this->tipoMembresia) {
            return 'No definida';
        }
        return $this->tipoMembresia->duracion_dias . ' días';
    }

    public function getEstadoFormateadoAttribute()
    {
        if ($this->fecha_vencimiento->isPast()) {
            return 'Vencida';
        }
        return 'Activa';
    }

    public function getDiasRestantesAttribute()
    {
        return (int)abs(now()->diffInDays($this->fecha_vencimiento));
    }
} 