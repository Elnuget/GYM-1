<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencia';
    
    protected $fillable = [
        'user_id',
        'fecha_asistencia',
        'hora_ingreso',
        'hora_salida',
        'estado'
    ];

    protected $casts = [
        'fecha_asistencia' => 'date',
        'hora_ingreso' => 'datetime',
        'hora_salida' => 'datetime'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} 