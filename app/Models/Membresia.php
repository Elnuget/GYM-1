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
        return $this->belongsTo(TipoMembresia::class, 'id_tipo_membresia');
    }

    public function registrarVisita()
    {
        if ($this->visitas_restantes > 0) {
            $this->visitas_restantes--;
            $this->save();
        }
    }
} 