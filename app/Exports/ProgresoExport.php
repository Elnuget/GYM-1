<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Cliente;
use App\Models\MedidaCorporal;
use App\Models\Asistencia;
use App\Models\RutinaCliente;
use Carbon\Carbon;

class ProgresoExport implements WithMultipleSheets
{
    protected $cliente;

    public function __construct($cliente)
    {
        $this->cliente = $cliente;
    }

    public function sheets(): array
    {
        return [
            'Medidas' => new MedidasSheet($this->cliente),
            'Asistencias' => new AsistenciasSheet($this->cliente),
            'Rutinas' => new RutinasSheet($this->cliente)
        ];
    }
}

class MedidasSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $cliente;

    public function __construct($cliente)
    {
        $this->cliente = $cliente;
    }

    public function collection()
    {
        return MedidaCorporal::where('cliente_id', $this->cliente->id_cliente)
            ->orderBy('fecha_medicion', 'desc')
            ->get()
            ->map(function($medida) {
                return [
                    'Fecha' => $medida->fecha_medicion->format('d/m/Y'),
                    'Peso' => $medida->peso,
                    'Altura' => $medida->altura,
                    'Cintura' => $medida->cintura,
                    'Pecho' => $medida->pecho,
                    'Brazos (Prom.)' => ($medida->biceps_derecho + $medida->biceps_izquierdo) / 2,
                    'Muslos (Prom.)' => ($medida->muslo_derecho + $medida->muslo_izquierdo) / 2
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Peso (kg)',
            'Altura (cm)',
            'Cintura (cm)',
            'Pecho (cm)',
            'Brazos (cm)',
            'Muslos (cm)'
        ];
    }

    public function title(): string
    {
        return 'Medidas Corporales';
    }
}

class AsistenciasSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $cliente;

    public function __construct($cliente)
    {
        $this->cliente = $cliente;
    }

    public function collection()
    {
        return Asistencia::where('cliente_id', $this->cliente->id_cliente)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($asistencia) {
                return [
                    'Fecha' => $asistencia->fecha_asistencia ?? 'N/A',
                    'Hora Ingreso' => $asistencia->hora_ingreso ?? 'N/A',
                    'Hora Salida' => $asistencia->hora_salida ?? 'No registrada',
                    'Estado' => ucfirst($asistencia->estado ?? 'N/A')
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Hora Ingreso',
            'Hora Salida',
            'Estado'
        ];
    }

    public function title(): string
    {
        return 'Registro de Asistencias';
    }
}

class RutinasSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $cliente;

    public function __construct($cliente)
    {
        $this->cliente = $cliente;
    }

    public function collection()
    {
        return RutinaCliente::where('cliente_id', $this->cliente->id_cliente)
            ->with(['rutinaPredefinida', 'ejercicios'])
            ->orderBy('fecha_inicio', 'desc')
            ->get()
            ->map(function($rutina) {
                return [
                    'Rutina' => $rutina->rutinaPredefinida->nombre,
                    'Fecha Inicio' => $rutina->fecha_inicio->format('d/m/Y'),
                    'Estado' => ucfirst($rutina->estado),
                    'Ejercicios Completados' => $rutina->ejercicios()->where('completado', true)->count(),
                    'Total Ejercicios' => $rutina->ejercicios()->count()
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Rutina',
            'Fecha Inicio',
            'Estado',
            'Ejercicios Completados',
            'Total Ejercicios'
        ];
    }

    public function title(): string
    {
        return 'Rutinas';
    }
} 