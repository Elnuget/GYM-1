<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">Mi Dashboard</h2>
                    
                    <!-- Resumen de Información -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Asistencias -->
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Asistencias este mes</h3>
                            <p class="text-blue-600 text-2xl font-bold">{{ $datos['asistencias_mes'] }}</p>
                            <p class="text-blue-600 text-sm">sesiones completadas</p>
                        </div>
                        
                        <!-- Membresía -->
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800 mb-2">Membresía</h3>
                            @if($datos['membresia'])
                                <p class="text-purple-600">
                                    <span class="font-bold">{{ $datos['membresia']['tipo'] }}</span><br>
                                    <span class="text-sm">Activa hasta: {{ $datos['membresia']['fecha_fin'] }}</span>
                                </p>
                            @else
                                <p class="text-purple-600">Sin membresía activa</p>
                            @endif
                        </div>

                        <!-- Medidas -->
                        <div class="bg-emerald-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-emerald-800 mb-2">Últimas Medidas</h3>
                            @if($datos['medidas'])
                                <div class="text-emerald-600">
                                    <p>Peso: {{ $datos['medidas']['peso'] }} kg</p>
                                    <p>IMC: {{ $datos['medidas']['imc'] }}</p>
                                    <p class="text-sm mt-1">Actualizado: {{ $datos['medidas']['fecha_medicion'] }}</p>
                                </div>
                            @else
                                <p class="text-emerald-600">Sin medidas registradas</p>
                            @endif
                        </div>
                    </div>

                    <!-- Objetivos y Rutina -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Objetivos -->
                        <div class="border rounded-lg p-6">
                            <h3 class="text-xl font-semibold mb-4">Mis Objetivos</h3>
                            @if($datos['objetivos'])
                                <div class="space-y-2">
                                    <p><span class="font-medium">Objetivo Principal:</span> {{ $datos['objetivos']['principal'] }}</p>
                                    <p><span class="font-medium">Nivel:</span> {{ $datos['objetivos']['nivel'] }}</p>
                                    <p><span class="font-medium">Días de entrenamiento:</span> {{ $datos['objetivos']['dias_entrenamiento'] }}</p>
                                </div>
                            @else
                                <p class="text-gray-500">No hay objetivos definidos</p>
                            @endif
                        </div>

                        <!-- Rutina Actual -->
                        <div class="border rounded-lg p-6">
                            <h3 class="text-xl font-semibold mb-4">Mi Rutina Actual</h3>
                            @if($datos['rutina_actual'])
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="font-medium">{{ $datos['rutina_actual']['nombre'] }}</h4>
                                        <p class="text-gray-600">{{ $datos['rutina_actual']['objetivo'] }}</p>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-emerald-600 h-2.5 rounded-full" 
                                                 style="width: {{ $datos['rutina_actual']['progreso'] }}%"></div>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-600">{{ $datos['rutina_actual']['progreso'] }}%</span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Inicio: {{ $datos['rutina_actual']['fecha_inicio'] }}</span>
                                        <a href="{{ route('cliente.rutinas.show', $datos['rutina_actual']['id_rutina']) }}" 
                                           class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                                            Ver detalles →
                                        </a>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500">No tienes una rutina asignada</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-cliente-layout> 