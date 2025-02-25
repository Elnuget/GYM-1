<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedidaCorporal extends Model
{
    use HasFactory;

    protected $table = 'medidas_corporales';
    protected $primaryKey = 'id_medida';

    protected $fillable = [
        'cliente_id',
        'peso',
        'altura',
        'cuello',
        'hombros',
        'pecho',
        'cintura',
        'cadera',
        'biceps',
        'antebrazos',
        'muslos',
        'pantorrillas',
        'fecha_medicion'
    ];

    protected $casts = [
        'fecha_medicion' => 'date',
        'peso' => 'decimal:2',
        'altura' => 'decimal:2'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }
} 