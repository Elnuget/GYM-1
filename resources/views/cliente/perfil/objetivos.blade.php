<x-cliente-layout>
    <div x-data="{ showModal: false, objetivoSeleccionado: null }" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Principal con borde verde sutil -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden border-2 border-emerald-100 dark:border-emerald-900">
                <!-- Encabezado con gradiente -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-white">Objetivos Personales</h2>
                            <p class="mt-1 text-sm text-emerald-100">Seguimiento de tus metas y objetivos</p>
                        </div>
                        <!-- Botón Agregar -->
                        <button type="button" 
                                @click="showModal = true; objetivoSeleccionado = null"
                                class="inline-flex items-center px-4 py-2 bg-white text-sm text-emerald-600 rounded-lg hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-600 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar Objetivo
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
                                        Objetivo Principal
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        Nivel de Experiencia
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        Días de Entrenamiento
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-emerald-100 dark:divide-emerald-900">
                                @foreach($objetivos as $objetivo)
                                <tr class="hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ ucfirst(str_replace('_', ' ', $objetivo->objetivo_principal)) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ ucfirst($objetivo->nivel_experiencia) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $objetivo->dias_entrenamiento }} días
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $objetivo->activo ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                            {{ $objetivo->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button @click="showModal = true; objetivoSeleccionado = {{ $objetivo->toJson() }}"
                                                class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 font-medium transition-colors duration-200">
                                            Ver detalles
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
                            Detalles del Objetivo
                        </h3>
                    </div>

                    <div class="p-6 bg-gradient-to-b from-white to-emerald-50 dark:from-gray-800 dark:to-gray-900">
                        <div class="space-y-4">
                            <template x-if="objetivoSeleccionado">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                        <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Objetivo Principal</label>
                                        <p class="mt-2 text-gray-900 dark:text-white font-medium" x-text="objetivoSeleccionado.objetivo_principal ? objetivoSeleccionado.objetivo_principal.charAt(0).toUpperCase() + objetivoSeleccionado.objetivo_principal.slice(1).replace(/_/g, ' ') : ''"></p>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                        <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Nivel de Experiencia</label>
                                        <p class="mt-2 text-gray-900 dark:text-white font-medium" x-text="objetivoSeleccionado.nivel_experiencia ? objetivoSeleccionado.nivel_experiencia.charAt(0).toUpperCase() + objetivoSeleccionado.nivel_experiencia.slice(1) : ''"></p>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                        <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Días de Entrenamiento</label>
                                        <p class="mt-2 text-gray-900 dark:text-white font-medium" x-text="objetivoSeleccionado.dias_entrenamiento + ' días'"></p>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                        <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Condiciones Médicas</label>
                                        <p class="mt-2 text-gray-900 dark:text-white font-medium" x-text="objetivoSeleccionado.condiciones_medicas ? objetivoSeleccionado.condiciones_medicas.charAt(0).toUpperCase() + objetivoSeleccionado.condiciones_medicas.slice(1) : 'Ninguna'"></p>
                                    </div>
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