<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComidaPlan extends Model
{
    use HasFactory;

    protected $table = 'comidas_plan';

    protected $fillable = [
        'plan_id',
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

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanNutricional::class, 'plan_id', 'id_plan');
    }
} 