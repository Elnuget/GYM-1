<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Tarjeta de Asistencias -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Asistencias</h3>
                            <div class="bg-emerald-50 rounded-full p-2">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>

                        @if($asistenciaActual)
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium">Sesión Activa</p>
                                        <p class="text-emerald-100 text-sm">
                                            Entrada: {{ Carbon\Carbon::parse($asistenciaActual->hora_entrada)->format('H:i') }}
                                        </p>
                                    </div>
                                    <form action="{{ route('cliente.asistencias.salida', $asistenciaActual->id_asistencia) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition-colors backdrop-blur-sm">
                                            Registrar Salida
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('cliente.asistencias.entrada') }}" method="POST" class="mb-4">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg hover:from-emerald-600 hover:to-teal-700 transition-all duration-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                    Registrar Entrada
                                </button>
                            </form>
                        @endif

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Este mes</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $asistenciasMes }}</p>
                            </div>
                            @if($ultimaAsistencia)
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Última visita</p>
                                    <p class="font-medium text-gray-800">{{ $ultimaAsistencia->fecha->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ Carbon\Carbon::parse($ultimaAsistencia->hora_entrada)->format('H:i') }} - 
                                        {{ $ultimaAsistencia->hora_salida ? Carbon\Carbon::parse($ultimaAsistencia->hora_salida)->format('H:i') : 'En curso' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Rutina -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Mi Rutina</h3>
                            <div class="bg-blue-50 rounded-full p-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                        </div>

                        @if($rutinaActual)
                            <div class="space-y-4">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg p-4">
                                    <h4 class="font-medium">{{ $rutinaActual->rutina->nombre_rutina }}</h4>
                                    <p class="text-blue-100 text-sm">
                                        {{ ucfirst(str_replace('_', ' ', $rutinaActual->rutina->objetivo)) }}
                                    </p>
                                    <div class="mt-3 pt-3 border-t border-white/20">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span>Progreso</span>
                                            <span>{{ $rutinaActual->progreso }}%</span>
                                        </div>
                                        <div class="w-full bg-white/20 rounded-full h-2">
                                            <div class="bg-white rounded-full h-2" style="width: {{ $rutinaActual->progreso }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('cliente.rutinas.actual') }}" 
                                   class="block text-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                    Ver rutina completa
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="bg-blue-50 rounded-full p-3 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <p class="text-gray-600">No tienes una rutina asignada</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tarjeta de Plan Nutricional -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Plan Nutricional</h3>
                            <div class="bg-purple-50 rounded-full p-2">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                </svg>
                            </div>
                        </div>

                        @if($planNutricional)
                            <div class="space-y-4">
                                <div class="bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg p-4">
                                    <h4 class="font-medium">{{ $planNutricional->nombre_plan }}</h4>
                                    <p class="text-purple-100 text-sm">
                                        {{ $planNutricional->calorias_diarias }} kcal diarias
                                    </p>
                                </div>
                                <a href="{{ route('cliente.nutricion') }}" 
                                   class="block text-center px-4 py-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors">
                                    Ver plan completo
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="bg-purple-50 rounded-full p-3 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <p class="text-gray-600">No tienes un plan nutricional asignado</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
</x-cliente-layout> 