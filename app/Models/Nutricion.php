<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nutricion extends Model
{
    use HasFactory;

    protected $table = 'nutricion';
    protected $primaryKey = 'id_nutricion';
    
    protected $fillable = [
        'cliente_id',
        'nombre_plan',
        'informacion',
        'plan_dieta',
        'estado',
        'calorias_diarias',
        'proteinas',
        'carbohidratos',
        'grasas',
        'recomendaciones',
        'fecha_asignacion',
        'fecha_fin'
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
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
        return $this->hasMany(ComidaNutricion::class, 'nutricion_id', 'id_nutricion');
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