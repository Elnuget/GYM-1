<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">Catálogo de Ejercicios</h2>

                    <!-- Filtros -->
                    <div class="mb-8">
                        <div class="flex flex-wrap gap-4">
                            <select x-model="grupoMuscular" class="rounded-md border-gray-300">
                                <option value="">Todos los grupos musculares</option>
                                <option value="pecho">Pecho</option>
                                <option value="espalda">Espalda</option>
                                <option value="piernas">Piernas</option>
                                <option value="brazos">Brazos</option>
                                <option value="hombros">Hombros</option>
                                <option value="abdominales">Abdominales</option>
                            </select>

                            <select x-model="equipamiento" class="rounded-md border-gray-300">
                                <option value="">Todo el equipamiento</option>
                                <option value="mancuernas">Mancuernas</option>
                                <option value="barras">Barras</option>
                                <option value="maquinas">Máquinas</option>
                                <option value="peso_corporal">Peso Corporal</option>
                            </select>
                        </div>
                    </div>

                    <!-- Lista de Ejercicios -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($ejercicios as $ejercicio)
                            <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                @if($ejercicio->imagen_url)
                                    <img src="{{ $ejercicio->imagen_url }}" 
                                         alt="{{ $ejercicio->nombre }}"
                                         class="w-full h-48 object-cover">
                                @endif
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold mb-2">{{ $ejercicio->nombre }}</h3>
                                    
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p>
                                            <span class="font-medium">Grupo muscular:</span> 
                                            {{ ucfirst($ejercicio->grupo_muscular) }}
                                        </p>
                                        @if($ejercicio->equipamiento_necesario)
                                            <p>
                                                <span class="font-medium">Equipamiento:</span> 
                                                {{ ucfirst($ejercicio->equipamiento_necesario) }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Botón para ver detalles -->
                                    <button @click="showEjercicioModal('{{ $ejercicio->id_ejercicio }}')"
                                            class="mt-4 w-full bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors">
                                        Ver detalles
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Modal de Detalles -->
                    <div x-show="modalOpen" 
                         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg max-w-2xl w-full mx-4 overflow-hidden">
                            <div class="p-6">
                                <h3 x-text="ejercicioSeleccionado.nombre" class="text-xl font-bold mb-4"></h3>
                                
                                <div class="space-y-4">
                                    <!-- Imagen/Video -->
                                    <template x-if="ejercicioSeleccionado.video_url">
                                        <div class="aspect-w-16 aspect-h-9">
                                            <iframe x-bind:src="ejercicioSeleccionado.video_url" 
                                                    frameborder="0" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen></iframe>
                                        </div>
                                    </template>

                                    <!-- Descripción -->
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Descripción</h4>
                                        <p x-text="ejercicioSeleccionado.descripcion" class="text-gray-600"></p>
                                    </div>

                                    <!-- Instrucciones -->
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Instrucciones</h4>
                                        <div x-html="ejercicioSeleccionado.instrucciones" class="text-gray-600"></div>
                                    </div>
                                </div>

                                <button @click="modalOpen = false" 
                                        class="mt-6 w-full bg-gray-200 text-gray-800 py-2 rounded-md hover:bg-gray-300 transition-colors">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function ejerciciosData() {
            return {
                modalOpen: false,
                ejercicioSeleccionado: null,
                grupoMuscular: '',
                equipamiento: '',
                
                showEjercicioModal(ejercicioId) {
                    // Aquí iría la lógica para cargar los detalles del ejercicio
                    this.ejercicioSeleccionado = this.ejercicios.find(e => e.id_ejercicio === ejercicioId);
                    this.modalOpen = true;
                }
            }
        }
    </script>
    @endpush
</x-cliente-layout> 