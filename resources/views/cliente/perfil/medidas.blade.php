<x-cliente-layout>
    <div x-data="{ showModal: false, medidaSeleccionada: null }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Principal -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Encabezado -->
                <div class="bg-emerald-600 p-6">
                    <h2 class="text-2xl font-semibold text-white">Historial de Medidas</h2>
                    <p class="mt-1 text-sm text-emerald-50">Seguimiento de tus medidas corporales</p>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">
                                        Peso
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">
                                        Altura
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">
                                        IMC
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">
                                        Detalles
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($medidas as $medida)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $medida->fecha_medicion->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($medida->peso, 2) }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($medida->altura, 2) }} cm
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($medida->peso / (($medida->altura/100) ** 2), 1) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button @click="showModal = true; medidaSeleccionada = {{ $medida->toJson() }}"
                                                class="text-emerald-600 hover:text-emerald-800 font-medium transition-colors duration-200">
                                            Ver más
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Botón Agregar Nueva Medida -->
                    <div class="mt-6 flex justify-end">
                        <button type="button" 
                                @click="showModal = true; medidaSeleccionada = null"
                                class="inline-flex items-center px-4 py-2 bg-emerald-600 text-sm text-white rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar Medida
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Detalles -->
        <div x-show="showModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500/75"></div>
                </div>

                <!-- Modal -->
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                     @click.away="showModal = false">
                    
                    <!-- Encabezado -->
                    <div class="bg-emerald-600 px-6 py-4">
                        <h3 class="text-xl font-medium text-white">
                            Detalles de Medidas
                        </h3>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <template x-if="medidaSeleccionada">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Fecha</label>
                                        <p class="mt-1 text-gray-900" x-text="new Date(medidaSeleccionada.fecha_medicion).toLocaleDateString()"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Peso</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.peso + ' kg'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Altura</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.altura + ' cm'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Cuello</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.cuello + ' cm'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Hombros</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.hombros + ' cm'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Pecho</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.pecho + ' cm'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Cintura</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.cintura + ' cm'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Cadera</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.cadera + ' cm'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Bíceps</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.biceps + ' cm'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Antebrazos</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.antebrazos + ' cm'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Muslos</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.muslos + ' cm'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Pantorrillas</label>
                                        <p class="mt-1 text-gray-900" x-text="medidaSeleccionada.pantorrillas + ' cm'"></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" 
                                    @click="showModal = false"
                                    class="px-4 py-2 bg-emerald-600 text-sm font-medium text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-cliente-layout> 