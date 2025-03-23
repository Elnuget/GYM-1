<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    
    protected $fillable = [
        'id_membresia',
        'id_usuario',
        'monto',
        'fecha_pago',
        'estado',
        'id_metodo_pago',
        'comprobante_url',
        'notas',
        'fecha_aprobacion'
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'fecha_aprobacion' => 'datetime',
        'monto' => 'decimal:2'
    ];

    protected $appends = ['comprobante_full_url'];

    public function getComprobanteFullUrlAttribute()
    {
        if (!$this->comprobante_url) {
            return null;
        }
        
        // Si la URL ya es completa (comienza con http o https), devolverla tal cual
        if (str_starts_with($this->comprobante_url, 'http')) {
            return $this->comprobante_url;
        }
        
        // Si la URL comienza con 'storage/', construir la URL completa
        return url($this->comprobante_url);
    }

    public function membresia(): BelongsTo
    {
        return $this->belongsTo(Membresia::class, 'id_membresia', 'id_membresia');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago', 'id_metodo_pago');
    }
} 