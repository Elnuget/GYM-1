<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">Mi Dashboard</h2>
                    
                    <!-- Resumen de Información -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-emerald-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-emerald-800 mb-2">Próxima Sesión</h3>
                            <p class="text-emerald-600">{{ $datos['proxima_sesion'] }}</p>
                        </div>
                        
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Asistencias este mes</h3>
                            <p class="text-blue-600">{{ $datos['asistencias_mes'] }} sesiones</p>
                        </div>
                        
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800 mb-2">Membresía</h3>
                            <p class="text-purple-600">
                                {{ $datos['membresia']['tipo'] }}<br>
                                <span class="text-sm">{{ $datos['membresia']['estado'] === 'activa' ? 'Activa hasta: ' : '' }}
                                {{ $datos['membresia']['fecha_fin'] }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Rutina Actual -->
                    @if($cliente->rutina_actual)
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Mi Rutina Actual</h3>
                        <div class="border rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-medium">{{ $cliente->rutina_actual->nombre }}</span>
                                <a href="{{ route('cliente.rutinas.show', $cliente->rutina_actual->id) }}" 
                                   class="text-emerald-600 hover:text-emerald-700">Ver detalles</a>
                            </div>
                            <div class="space-y-2">
                                <p class="text-gray-600">Próximo entrenamiento: {{ $cliente->rutina_actual->proximo_entrenamiento ?? 'Por definir' }}</p>
                                @if($cliente->rutina_actual->progreso)
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-emerald-600 h-2.5 rounded-full" 
                                             style="width: {{ $cliente->rutina_actual->progreso }}%"></div>
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">{{ $cliente->rutina_actual->progreso }}% completado</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Mi Rutina Actual</h3>
                        <div class="border rounded-lg p-6 text-center text-gray-500">
                            <p>Aún no tienes una rutina asignada</p>
                            <p class="text-sm mt-2">Tu entrenador te asignará una rutina pronto</p>
                        </div>
                    </div>
                    @endif

                    <!-- Objetivos -->
                    <div>
                        <h3 class="text-xl font-semibold mb-4">Mis Objetivos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border rounded-lg p-6">
                                <h4 class="font-medium mb-2">Objetivo Principal</h4>
                                <p class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $cliente->objetivos['objetivo_principal'] ?? 'No definido')) }}</p>
                            </div>
                            <div class="border rounded-lg p-6">
                                <h4 class="font-medium mb-2">Días de Entrenamiento</h4>
                                <p class="text-gray-600">{{ $cliente->objetivos['dias_entrenamiento'] ?? 'No definido' }} días por semana</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 