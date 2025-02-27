<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Progreso - {{ $cliente->user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Progreso</h1>
        <p>{{ $cliente->user->name }} - {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h2>Estadísticas Generales</h2>
        <table class="table">
            <tr>
                <th>Total Asistencias</th>
                <td>{{ $estadisticas['total_asistencias'] }}</td>
            </tr>
            <tr>
                <th>Tiempo Total Entrenado</th>
                <td>{{ floor($estadisticas['tiempo_total'] / 60) }} horas {{ $estadisticas['tiempo_total'] % 60 }} minutos</td>
            </tr>
            <tr>
                <th>Cambio en Peso</th>
                <td>{{ $estadisticas['cambio_peso'] }} kg</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Historial de Medidas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Peso</th>
                    <th>Cintura</th>
                    <th>Pecho</th>
                    <th>Brazos (Prom.)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medidas as $medida)
                <tr>
                    <td>{{ $medida->fecha_medicion->format('d/m/Y') }}</td>
                    <td>{{ $medida->peso }} kg</td>
                    <td>{{ $medida->cintura }} cm</td>
                    <td>{{ $medida->pecho }} cm</td>
                    <td>{{ ($medida->biceps_derecho + $medida->biceps_izquierdo) / 2 }} cm</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Rutinas Completadas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Rutina</th>
                    <th>Fecha Inicio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rutinas as $rutina)
                <tr>
                    <td>{{ $rutina->rutinaPredefinida->nombre }}</td>
                    <td>{{ $rutina->fecha_inicio->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($rutina->estado) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 