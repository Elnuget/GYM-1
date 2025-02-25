<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">Mi Rutina Actual</h2>

                    @if($rutinaActual)
                        <!-- Información General de la Rutina -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-6 mb-8">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-semibold text-emerald-800">
                                        {{ $rutinaActual->rutina->nombre_rutina }}
                                    </h3>
                                    <p class="text-emerald-600 mt-1">
                                        Objetivo: {{ ucfirst(str_replace('_', ' ', $rutinaActual->rutina->objetivo)) }}
                                    </p>
                                </div>
                                <span class="px-4 py-2 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium">
                                    Activa
                                </span>
                            </div>

                            <!-- Progreso General -->
                            <div class="mt-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-emerald-700">Progreso General</span>
                                    <span class="text-sm font-medium text-emerald-700">{{ $rutinaActual->progreso }}%</span>
                                </div>
                                <div class="w-full bg-emerald-100 rounded-full h-3">
                                    <div class="bg-emerald-500 h-3 rounded-full transition-all duration-500"
                                         style="width: {{ $rutinaActual->progreso }}%">
                                    </div>
                                </div>
                            </div>

                            <!-- Detalles Adicionales -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <span class="text-sm text-gray-500">Fecha de inicio</span>
                                    <p class="font-medium text-gray-900">{{ $rutinaActual->fecha_inicio->format('d/m/Y') }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <span class="text-sm text-gray-500">Nivel</span>
                                    <p class="font-medium text-gray-900">{{ ucfirst($rutinaActual->rutina->nivel) }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <span class="text-sm text-gray-500">Duración estimada</span>
                                    <p class="font-medium text-gray-900">{{ $rutinaActual->rutina->duracion }} semanas</p>
                                </div>
                            </div>
                        </div>

                        <!-- Plan de Ejercicios -->
                        <div x-data="{ diaActivo: 1 }" class="mt-8">
                            <h3 class="text-lg font-semibold mb-4">Plan de Ejercicios</h3>
                            
                            <!-- Selector de Días -->
                            <div class="flex space-x-2 mb-6 overflow-x-auto pb-2">
                                @foreach($rutinaActual->rutina->ejerciciosPorDia() as $dia => $ejercicios)
                                    <button @click="diaActivo = {{ $dia }}"
                                            :class="{ 'bg-emerald-600 text-white': diaActivo === {{ $dia }}, 
                                                     'bg-gray-100 text-gray-700 hover:bg-gray-200': diaActivo !== {{ $dia }} }"
                                            class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex-shrink-0">
                                        Día {{ $dia }}
                                    </button>
                                @endforeach
                            </div>

                            <!-- Lista de Ejercicios por Día -->
                            @foreach($rutinaActual->rutina->ejerciciosPorDia() as $dia => $ejercicios)
                                <div x-show="diaActivo === {{ $dia }}"
                                     class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($ejercicios as $ejercicio)
                                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $ejercicio->nombre }}</h4>
                                                    <p class="text-sm text-gray-500">{{ $ejercicio->grupo_muscular }}</p>
                                                </div>
                                                <button @click="$dispatch('show-ejercicio', { id: '{{ $ejercicio->id_ejercicio }}' })"
                                                        class="text-emerald-600 hover:text-emerald-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="mt-3 flex items-center justify-between text-sm">
                                                <span class="text-gray-600">
                                                    {{ $ejercicio->pivot->series }} series × 
                                                    {{ $ejercicio->pivot->repeticiones }} reps
                                                </span>
                                                @if($ejercicio->pivot->peso_sugerido)
                                                    <span class="bg-gray-100 px-2 py-1 rounded">
                                                        {{ $ejercicio->pivot->peso_sugerido }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <!-- Notas del Entrenador -->
                        @if($rutinaActual->notas_entrenador)
                            <div class="mt-8 bg-blue-50 rounded-lg p-6">
                                <h4 class="font-medium text-blue-800 mb-2">Notas del Entrenador</h4>
                                <p class="text-blue-700">{{ $rutinaActual->notas_entrenador }}</p>
                            </div>
                        @endif

                        <!-- Acciones -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <button @click="$dispatch('open-modal', { type: 'actualizar-progreso' })"
                                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition-colors">
                                Actualizar Progreso
                            </button>
                            <button @click="$dispatch('open-modal', { type: 'solicitar-cambio' })"
                                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                Solicitar Cambio
                            </button>
                        </div>

                    @else
                        <!-- Estado sin Rutina -->
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">No tienes una rutina asignada</h3>
                            <p class="mt-2 text-sm text-gray-500">Tu entrenador te asignará una rutina pronto.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Ejercicio -->
    <div x-data="ejercicioModal()"
         x-show="isOpen"
         @show-ejercicio.window="showEjercicio($event.detail.id)"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg max-w-2xl w-full mx-4" @click.away="isOpen = false">
            <div class="p-6">
                <template x-if="ejercicio">
                    <div>
                        <h3 x-text="ejercicio.nombre" class="text-xl font-bold mb-4"></h3>
                        
                        <div class="space-y-4">
                            <template x-if="ejercicio.video_url">
                                <div class="aspect-w-16 aspect-h-9">
                                    <iframe x-bind:src="ejercicio.video_url" 
                                            frameborder="0" 
                                            allowfullscreen></iframe>
                                </div>
                            </template>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Descripción</h4>
                                <p x-text="ejercicio.descripcion" class="text-gray-600"></p>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Instrucciones</h4>
                                <div x-html="ejercicio.instrucciones" class="text-gray-600"></div>
                            </div>
                        </div>

                        <button @click="isOpen = false" 
                                class="mt-6 w-full bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                            Cerrar
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function ejercicioModal() {
            return {
                isOpen: false,
                ejercicio: null,
                showEjercicio(id) {
                    // Aquí iría la lógica para cargar los detalles del ejercicio mediante AJAX
                    this.ejercicio = {
                        nombre: 'Nombre del ejercicio',
                        descripcion: 'Descripción del ejercicio...',
                        instrucciones: 'Instrucciones detalladas...',
                        video_url: null
                    };
                    this.isOpen = true;
                }
            }
        }
    </script>
    @endpush
</x-cliente-layout> 