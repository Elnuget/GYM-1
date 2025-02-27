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
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Objetivo Principal
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            Nivel de Experiencia
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Días de Entrenamiento
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Estado
                                        </div>
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

        <!-- Modal para Agregar/Ver Detalles -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay con blur -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500/75 backdrop-blur-sm"></div>
                </div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                        <h3 class="text-xl font-medium text-white" x-text="objetivoSeleccionado ? 'Detalles del Objetivo' : 'Agregar Nuevo Objetivo'"></h3>
                    </div>

                    <div class="p-6 bg-gradient-to-b from-white to-emerald-50">
                        <!-- Formulario para agregar objetivo -->
                        <form x-show="!objetivoSeleccionado" action="{{ route('cliente.perfil.objetivos.store') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Objetivo Principal</label>
                                    <select name="objetivo_principal" required class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        <option value="">Selecciona un objetivo</option>
                                        <option value="perdida_peso">Pérdida de Peso</option>
                                        <option value="ganancia_muscular">Ganancia Muscular</option>
                                        <option value="tonificacion">Tonificación</option>
                                        <option value="resistencia">Resistencia</option>
                                        <option value="fuerza">Fuerza</option>
                                        <option value="flexibilidad">Flexibilidad</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Nivel de Experiencia</label>
                                    <select name="nivel_experiencia" required class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        <option value="">Selecciona tu nivel</option>
                                        <option value="principiante">Principiante</option>
                                        <option value="intermedio">Intermedio</option>
                                        <option value="avanzado">Avanzado</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Días de Entrenamiento por Semana</label>
                                    <select name="dias_entrenamiento" required class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        @for($i = 1; $i <= 7; $i++)
                                            <option value="{{ $i }}">{{ $i }} día{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Condiciones Médicas</label>
                                    <textarea name="condiciones_medicas" rows="3" 
                                              class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200"
                                              placeholder="Describe cualquier condición médica relevante (opcional)"></textarea>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" @click="showModal = false"
                                        class="px-4 py-2 text-sm font-medium text-emerald-600 hover:text-emerald-700">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700">
                                    Guardar Objetivo
                                </button>
                            </div>
                        </form>

                        <!-- Vista de detalles -->
                        <template x-if="objetivoSeleccionado">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Objetivo Principal</label>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium" x-text="objetivoSeleccionado.objetivo_principal.replace(/_/g, ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')"></p>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Nivel de Experiencia</label>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium" x-text="objetivoSeleccionado.nivel_experiencia.charAt(0).toUpperCase() + objetivoSeleccionado.nivel_experiencia.slice(1)"></p>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Días de Entrenamiento</label>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium" x-text="objetivoSeleccionado.dias_entrenamiento + ' días por semana'"></p>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Estado</label>
                                        </div>
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                              :class="objetivoSeleccionado.activo ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800'"
                                              x-text="objetivoSeleccionado.activo ? 'Activo' : 'Inactivo'"></span>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300 md:col-span-2">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Condiciones Médicas</label>
                                        </div>
                                        <p class="text-gray-900 dark:text-white font-medium" x-text="objetivoSeleccionado.condiciones_medicas || 'Ninguna condición médica registrada'"></p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" @click="showModal = false"
                                            class="px-4 py-2 bg-white text-sm font-medium text-emerald-600 rounded-lg border border-emerald-200 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-cliente-layout> 