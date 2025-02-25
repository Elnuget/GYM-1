<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanNutricional extends Model
{
    use HasFactory;

    protected $table = 'planes_nutricionales';
    protected $primaryKey = 'id_plan';

    protected $fillable = [
        'cliente_id',
        'nombre_plan',
        'descripcion',
        'estado',
        'calorias_diarias',
        'proteinas',
        'carbohidratos',
        'grasas',
        'notas_nutricionales',
        'recomendaciones',
        'fecha_inicio',
        'fecha_fin'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'calorias_diarias' => 'integer',
        'proteinas' => 'integer',
        'carbohidratos' => 'integer',
        'grasas' => 'integer'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    public function comidas(): HasMany
    {
        return $this->hasMany(ComidaPlan::class, 'plan_id', 'id_plan');
    }

    public function getMacrosAttribute()
    {
        return [
            'proteinas' => $this->proteinas,
            'carbohidratos' => $this->carbohidratos,
            'grasas' => $this->grasas
        ];
    }
} 