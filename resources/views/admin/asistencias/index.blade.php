<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
    @if($asistencia->hora_salida)
        @if($asistencia->duracion_minutos >= 60)
            {{ floor($asistencia->duracion_minutos / 60) }}h {{ $asistencia->duracion_minutos % 60 }}min
        @else
            {{ $asistencia->duracion_minutos }} min
        @endif
    @else
        -
    @endif
</td> 