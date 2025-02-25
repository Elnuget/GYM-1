<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencia';
    
    protected $fillable = [
        'cliente_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'duracion_minutos',
        'estado',
        'notas'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime',
        'hora_salida' => 'datetime',
        'duracion_minutos' => 'integer'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    public function calcularDuracion()
    {
        if ($this->hora_entrada && $this->hora_salida) {
            $entrada = Carbon::parse($this->hora_entrada);
            $salida = Carbon::parse($this->hora_salida);
            return $salida->diffInMinutes($entrada);
        }
        return 0;
    }

    public function getDuracionFormateadaAttribute()
    {
        if ($this->duracion_minutos) {
            $horas = floor($this->duracion_minutos / 60);
            $minutos = $this->duracion_minutos % 60;
            
            if ($horas > 0) {
                return sprintf('%dh %dm', $horas, $minutos);
            }
            return sprintf('%dm', $minutos);
        }
        return '-';
    }
} 