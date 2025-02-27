<x-cliente-layout>
    <div x-data="{ showModal: false, medidaSeleccionada: null }" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Principal con borde verde sutil -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden border-2 border-emerald-100 dark:border-emerald-900">
                <!-- Encabezado con gradiente -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-white">Historial de Medidas</h2>
                            <p class="mt-1 text-sm text-emerald-100">Seguimiento de tus medidas corporales</p>
                        </div>
                        <!-- Botón Agregar -->
                        <button type="button" 
                                @click="showModal = true; medidaSeleccionada = null"
                                class="inline-flex items-center px-4 py-2 bg-white text-sm text-emerald-600 rounded-lg hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-600 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar Medida
                        </button>
                    </div>
                </div>

                <!-- Contenido con diseño mejorado -->
                <div class="p-6 bg-gradient-to-b from-white to-emerald-50 dark:from-gray-800 dark:to-gray-900">
                    <div class="overflow-x-auto rounded-xl border border-emerald-100 dark:border-emerald-900">
                        <table class="min-w-full divide-y divide-emerald-100 dark:divide-emerald-900">
                            <thead>
                                <tr class="bg-emerald-50 dark:bg-emerald-900/30">
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        Peso
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        Altura
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        IMC
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        Detalles
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-emerald-100 dark:divide-emerald-900">
                                @foreach($medidas as $medida)
                                <tr class="hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $medida->fecha_medicion->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($medida->peso, 2) }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($medida->altura, 2) }} cm
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($medida->peso / (($medida->altura/100) ** 2), 1) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button @click="showModal = true; medidaSeleccionada = {{ $medida->toJson() }}"
                                                class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 font-medium transition-colors duration-200">
                                            Ver más
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Detalles -->
        <div x-show="showModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay con blur -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500/75 backdrop-blur-sm"></div>
                </div>

                <!-- Modal con diseño mejorado -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-2 border-emerald-100 dark:border-emerald-900"
                     @click.away="showModal = false">
                    
                    <!-- Encabezado del Modal -->
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                        <h3 class="text-xl font-medium text-white">
                            Detalles de Medidas
                        </h3>
                    </div>

                    <div class="p-6 bg-gradient-to-b from-white to-emerald-50 dark:from-gray-800 dark:to-gray-900">
                        <div class="space-y-4">
                            <template x-if="medidaSeleccionada">
                                <div class="grid grid-cols-2 gap-6">
                                    <!-- Campos de medidas con diseño mejorado -->
                                    <template x-for="(value, key) in medidaSeleccionada" :key="key">
                                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                            <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider" x-text="key.replace('_', ' ')"></label>
                                            <p class="mt-2 text-gray-900 dark:text-white font-medium" x-text="key === 'fecha_medicion' ? new Date(value).toLocaleDateString() : value + (key !== 'id' ? ' cm' : '')"></p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    @click="showModal = false"
                                    class="px-4 py-2 bg-white text-sm font-medium text-emerald-600 rounded-lg border border-emerald-200 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-cliente-layout> 