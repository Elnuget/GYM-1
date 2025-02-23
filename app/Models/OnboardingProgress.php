<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingProgress extends Model
{
    use HasFactory;

    protected $table = 'onboarding_progress';

    protected $fillable = [
        'cliente_id',
        'perfil_completado',
        'medidas_iniciales',
        'objetivos_definidos',
        'tutorial_visto'
    ];

    protected $casts = [
        'perfil_completado' => 'boolean',
        'medidas_iniciales' => 'boolean',
        'objetivos_definidos' => 'boolean',
        'tutorial_visto' => 'boolean'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
} 