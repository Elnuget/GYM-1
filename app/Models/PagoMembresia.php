<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoMembresia extends Model
{
    use HasFactory;

    protected $table = 'pagos_membresia';
    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'cliente_id',
        'membresia_id',
        'monto',
        'metodo_pago',
        'comprobante_url',
        'notas',
        'estado',
        'fecha_pago',
        'fecha_aprobacion'
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'fecha_aprobacion' => 'datetime'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'membresia_id', 'id_membresia');
    }
} 