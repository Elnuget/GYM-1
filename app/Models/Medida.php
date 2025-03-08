<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Medida extends Model
{
    use HasFactory;

    protected $table = 'medidas';
    
    protected $fillable = [
        'cliente_id',
        'fecha_medicion',
        'peso',
        'altura',
        'cintura',
        'pecho',
        'biceps',
        'muslos',
        'pantorrillas'
    ];

    protected $casts = [
        'fecha_medicion' => 'datetime',
        'peso' => 'float',
        'altura' => 'float',
        'cintura' => 'float',
        'pecho' => 'float',
        'biceps' => 'float',
        'muslos' => 'float',
        'pantorrillas' => 'float'
    ];

    /**
     * Relación con el cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Calcular el IMC (Índice de Masa Corporal)
     */
    public function getImcAttribute()
    {
        if ($this->altura > 0) {
            // Altura en metros (convertir de cm a m)
            $alturaEnMetros = $this->altura / 100;
            return $this->peso / ($alturaEnMetros * $alturaEnMetros);
        }
        return 0;
    }
} 