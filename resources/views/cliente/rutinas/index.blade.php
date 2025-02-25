<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">Mis Rutinas</h2>

                    <!-- Rutina Actual -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Rutina Actual</h3>
                        @if($rutinaActual)
                            <div class="border rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-lg font-medium">{{ $rutinaActual->rutina->nombre_rutina }}</h4>
                                        <p class="text-gray-600">{{ $rutinaActual->rutina->objetivo }}</p>
                                    </div>
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-sm">
                                        Activa
                                    </span>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Progreso</span>
                                            <span>{{ $rutinaActual->progreso }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-emerald-600 h-2.5 rounded-full" 
                                                 style="width: {{ $rutinaActual->progreso }}%"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Fecha de inicio:</span>
                                            <span class="font-medium">{{ $rutinaActual->fecha_inicio->format('d/m/Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Última actualización:</span>
                                            <span class="font-medium">{{ $rutinaActual->updated_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex justify-end space-x-4">
                                        <button type="button" 
                                                class="text-emerald-600 hover:text-emerald-700"
                                                onclick="solicitarCambio()">
                                            Solicitar cambio
                                        </button>
                                        <a href="{{ route('cliente.rutinas.show', $rutinaActual) }}" 
                                           class="text-emerald-600 hover:text-emerald-700">
                                            Ver detalles →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="border rounded-lg p-6 text-center text-gray-500">
                                <p>No tienes una rutina asignada actualmente</p>
                                <p class="text-sm mt-2">Tu entrenador te asignará una rutina pronto</p>
                            </div>
                        @endif
                    </div>

                    <!-- Historial de Rutinas -->
                    @if($rutinasAnteriores->isNotEmpty())
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Historial de Rutinas</h3>
                            <div class="space-y-4">
                                @foreach($rutinasAnteriores as $rutina)
                                    <div class="border rounded-lg p-4">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h4 class="font-medium">{{ $rutina->rutina->nombre_rutina }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    {{ $rutina->fecha_inicio->format('d/m/Y') }} - 
                                                    {{ $rutina->fecha_fin ? $rutina->fecha_fin->format('d/m/Y') : 'N/A' }}
                                                </p>
                                            </div>
                                            <a href="{{ route('cliente.rutinas.show', $rutina) }}" 
                                               class="text-emerald-600 hover:text-emerald-700 text-sm">
                                                Ver detalles
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para solicitar cambio -->
    <div x-data="{ open: false }" 
         x-show="open" 
         class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold mb-4">Solicitar Cambio de Rutina</h3>
            <form action="{{ route('cliente.rutinas.solicitar-cambio', $rutinaActual) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo del cambio
                    </label>
                    <textarea name="motivo" rows="4" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                              required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            @click="open = false"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function solicitarCambio() {
            Alpine.store('modalSolicitud').open = true;
        }
    </script>
</x-app-layout> 