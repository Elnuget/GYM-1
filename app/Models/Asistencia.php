<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected $attributes = [
        'estado' => 'activa',
    ];

    protected $dates = [
        'fecha',
        'created_at',
        'updated_at'
    ];

    // Convertir hora de entrada a la zona horaria América/Guayaquil cuando se acceda a ella
    public function getHoraEntradaAttribute($value)
    {
        if (!$value) return null;
        return Carbon::parse($value)->setTimezone('America/Guayaquil')->format('H:i:s');
    }

    // Convertir hora de salida a la zona horaria América/Guayaquil cuando se acceda a ella
    public function getHoraSalidaAttribute($value)
    {
        if (!$value) return null;
        return Carbon::parse($value)->setTimezone('America/Guayaquil')->format('H:i:s');
    }

    // Convertir fecha a la zona horaria América/Guayaquil cuando se acceda a ella
    public function getFechaAttribute($value)
    {
        if (!$value) return null;
        return Carbon::parse($value)->setTimezone('America/Guayaquil')->toDateString();
    }

    // Relación con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    // Método para calcular la duración en minutos entre hora_entrada y hora_salida
    public function calcularDuracion()
    {
        if (!$this->getRawOriginal('hora_salida') || !$this->getRawOriginal('hora_entrada')) {
            return null;
        }

        $entrada = Carbon::parse($this->getRawOriginal('hora_entrada'));
        $salida = Carbon::parse($this->getRawOriginal('hora_salida'));
        
        return $salida->diffInMinutes($entrada);
    }

    // Método para registrar la salida
    public function registrarSalida($hora_salida = null)
    {
        $this->hora_salida = $hora_salida ?? Carbon::now('America/Guayaquil')->format('H:i:s');
        $this->duracion_minutos = $this->calcularDuracion();
        $this->save();

        return $this;
    }
}
