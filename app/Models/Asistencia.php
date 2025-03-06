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
        
        // Si el valor ya incluye la fecha, extraer solo la hora
        if (strlen($value) > 8) {
            return Carbon::parse($value)->format('H:i:s');
        }
        
        return $value;
    }

    // Convertir hora de salida a la zona horaria América/Guayaquil cuando se acceda a ella
    public function getHoraSalidaAttribute($value)
    {
        if (!$value) return null;
        
        // Si el valor ya incluye la fecha, extraer solo la hora
        if (strlen($value) > 8) {
            return Carbon::parse($value)->format('H:i:s');
        }
        
        return $value;
    }

    // Convertir fecha a la zona horaria América/Guayaquil cuando se acceda a ella
    public function getFechaAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getDuracionFormateadaAttribute()
    {
        if (!$this->hora_salida) {
            return 'En curso';
        }

        // Calcular la duración si no está almacenada
        $duracionMinutos = $this->duracion_minutos;
        if ($duracionMinutos === null || $duracionMinutos < 0) {
            $duracionMinutos = $this->calcularDuracion();
            
            // Actualizar el valor en la base de datos
            if ($duracionMinutos !== null) {
                $this->duracion_minutos = $duracionMinutos;
                $this->save();
            }
        }
        
        if ($duracionMinutos === null) {
            return '-';
        }
        
        // Formato unificado para todas las vistas
        if ($duracionMinutos >= 60) {
            $horas = floor($duracionMinutos / 60);
            $minutos = $duracionMinutos % 60;
            return $horas . 'h ' . $minutos . 'min';
        } else {
            // Asegurar que siempre se muestre como número entero
            return (int)$duracionMinutos . ' min';
        }
    }

    // Relación con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    // Método para calcular la duración en minutos
    public function calcularDuracion()
    {
        if (!$this->hora_salida || !$this->hora_entrada) {
            return null;
        }

        // Obtener las horas como objetos Carbon completos con fecha
        $fechaBase = Carbon::parse($this->fecha)->format('Y-m-d');
        
        // Convertir hora_entrada a un objeto Carbon completo
        $horaEntrada = $this->hora_entrada;
        if (strlen($horaEntrada) <= 8) { // Si solo es hora (HH:MM:SS)
            $entrada = Carbon::parse($fechaBase . ' ' . $horaEntrada);
        } else {
            $entrada = Carbon::parse($horaEntrada);
        }
        
        // Convertir hora_salida a un objeto Carbon completo
        $horaSalida = $this->hora_salida;
        if (strlen($horaSalida) <= 8) { // Si solo es hora (HH:MM:SS)
            $salida = Carbon::parse($fechaBase . ' ' . $horaSalida);
        } else {
            $salida = Carbon::parse($horaSalida);
        }
        
        // Si la salida es anterior a la entrada (por ejemplo, si la entrada fue a las 23:00 y la salida a las 01:00)
        if ($salida < $entrada) {
            $salida->addDay(); // Añadir un día a la salida
        }
        
        // Calcular la diferencia en minutos (invertir el orden para asegurar resultado positivo)
        $minutos = $entrada->diffInMinutes($salida);
        
        // Asegurar que nunca sea 0 si hay diferencia real
        if ($minutos == 0 && $entrada->format('H:i:s') != $salida->format('H:i:s')) {
            // Si las horas son diferentes pero diffInMinutes da 0, forzar al menos 1 minuto
            return 1;
        }
        
        return $minutos;
    }

    // Método para registrar la salida
    public function registrarSalida($hora_salida = null)
    {
        $this->hora_salida = $hora_salida ?? Carbon::now()->format('H:i:s');
        $this->duracion_minutos = $this->calcularDuracion();
        $this->estado = 'completada';
        $this->save();

        return $this;
    }

    // Mutadores para asegurar formato consistente
    public function setHoraEntradaAttribute($value)
    {
        if (!$value) {
            $this->attributes['hora_entrada'] = null;
            return;
        }
        
        // Asegurar que se guarde solo la hora
        if (strlen($value) > 8) {
            $this->attributes['hora_entrada'] = Carbon::parse($value)->format('H:i:s');
        } else {
            $this->attributes['hora_entrada'] = $value;
        }
    }
    
    public function setHoraSalidaAttribute($value)
    {
        if (!$value) {
            $this->attributes['hora_salida'] = null;
            return;
        }
        
        // Asegurar que se guarde solo la hora
        if (strlen($value) > 8) {
            $this->attributes['hora_salida'] = Carbon::parse($value)->format('H:i:s');
        } else {
            $this->attributes['hora_salida'] = $value;
        }
    }
}
