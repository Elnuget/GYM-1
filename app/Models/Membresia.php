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
        'tipo_membresia',
        'fecha_compra',
        'fecha_vencimiento',
        'visitas_permitidas',
        'visitas_restantes',
        'renovacion'
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'fecha_vencimiento' => 'date',
        'renovacion' => 'boolean'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function registrarVisita()
    {
        if ($this->tipo_membresia === 'por_visitas' && $this->visitas_restantes > 0) {
            $this->visitas_restantes--;
            $this->save();
        }
    }
} 