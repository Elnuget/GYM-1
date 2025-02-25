<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComidaNutricion extends Model
{
    use HasFactory;

    protected $table = 'comidas_nutricion';

    protected $fillable = [
        'nutricion_id',
        'nombre_comida',
        'hora_sugerida',
        'calorias',
        'descripcion',
        'alimentos',
        'instrucciones'
    ];

    protected $casts = [
        'hora_sugerida' => 'datetime',
        'calorias' => 'integer'
    ];

    public function nutricion(): BelongsTo
    {
        return $this->belongsTo(Nutricion::class, 'nutricion_id', 'id_nutricion');
    }
} 