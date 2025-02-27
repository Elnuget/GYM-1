<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Encabezado -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Reportes y Estadísticas</h1>
                        <p class="text-gray-600">Seguimiento detallado de tu progreso</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('cliente.reportes.pdf') }}" 
                           class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span>Exportar PDF</span>
                        </a>
                        <a href="{{ route('cliente.reportes.excel') }}" 
                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Exportar Excel</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="bg-emerald-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $estadisticas['total_asistencias'] }}</h3>
                            <p class="text-sm text-gray-500">Total Asistencias</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $estadisticas['promedio_mensual'] }}</h3>
                            <p class="text-sm text-gray-500">Promedio Mensual</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $estadisticas['dias_consecutivos'] }}</h3>
                            <p class="text-sm text-gray-500">Días Consecutivos</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $estadisticas['rutinas_completadas'] }}</h3>
                            <p class="text-sm text-gray-500">Rutinas Completadas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Gráfica de Medidas -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Evolución de Medidas</h3>
                    <canvas id="medidasChart" height="300"></canvas>
                </div>

                <!-- Gráfica de Asistencias -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Asistencias Mensuales</h3>
                    <canvas id="asistenciasChart" height="300"></canvas>
                </div>

                <!-- Gráfica de Progreso de Rutinas -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Progreso de Rutinas</h3>
                    <canvas id="rutinasChart" height="300"></canvas>
                </div>

                <!-- Resumen de Cambios -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumen de Cambios</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500">Cambio en Peso</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ abs($estadisticas['cambio_peso']) }} kg
                                    @if($estadisticas['cambio_peso'] > 0)
                                        <span class="text-green-500">↑</span>
                                    @elseif($estadisticas['cambio_peso'] < 0)
                                        <span class="text-red-500">↓</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500">Tiempo Total Entrenado</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ floor($estadisticas['tiempo_total'] / 60) }}h {{ $estadisticas['tiempo_total'] % 60 }}m
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfica de Medidas
        new Chart(document.getElementById('medidasChart'), {
            type: 'line',
            data: {
                labels: @json($datosMedidas['labels']),
                datasets: [{
                    label: 'Peso (kg)',
                    data: @json($datosMedidas['peso']),
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                }, {
                    label: 'Cintura (cm)',
                    data: @json($datosMedidas['cintura']),
                    borderColor: 'rgb(16, 185, 129)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Gráfica de Asistencias
        new Chart(document.getElementById('asistenciasChart'), {
            type: 'bar',
            data: {
                labels: @json(collect($asistencias)->pluck('mes')),
                datasets: [{
                    label: 'Asistencias',
                    data: @json(collect($asistencias)->pluck('total')),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Gráfica de Rutinas
        new Chart(document.getElementById('rutinasChart'), {
            type: 'bar',
            data: {
                labels: @json($datosRutinas['labels']),
                datasets: [{
                    label: 'Ejercicios Completados',
                    data: @json($datosRutinas['completadas']),
                    backgroundColor: 'rgba(16, 185, 129, 0.5)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1
                }, {
                    label: 'Total Ejercicios',
                    data: @json($datosRutinas['total']),
                    backgroundColor: 'rgba(107, 114, 128, 0.5)',
                    borderColor: 'rgb(107, 114, 128)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-cliente-layout> 