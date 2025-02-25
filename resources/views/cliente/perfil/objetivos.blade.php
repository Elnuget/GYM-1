<x-cliente-layout>
    <div x-data="{ showModal: false, objetivoSeleccionado: null }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Principal -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Encabezado -->
                <div class="bg-emerald-600 p-6">
                    <h2 class="text-2xl font-semibold text-white">Objetivos Personales</h2>
                    <p class="mt-1 text-sm text-emerald-50">Seguimiento de tus metas y objetivos</p>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">
                                        Objetivo Principal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">
                                        Nivel de Experiencia
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">
                                        Días de Entrenamiento
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-emerald-600 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($objetivos as $objetivo)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $objetivo->objetivo_principal)) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ ucfirst($objetivo->nivel_experiencia) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $objetivo->dias_entrenamiento }} días
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $objetivo->activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $objetivo->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button @click="showModal = true; objetivoSeleccionado = {{ $objetivo->toJson() }}"
                                                class="text-emerald-600 hover:text-emerald-800 font-medium transition-colors duration-200">
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
                            Detalles del Objetivo
                        </h3>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <template x-if="objetivoSeleccionado">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Objetivo Principal</label>
                                        <p class="mt-1 text-gray-900" x-text="objetivoSeleccionado.objetivo_principal ? objetivoSeleccionado.objetivo_principal.charAt(0).toUpperCase() + objetivoSeleccionado.objetivo_principal.slice(1).replace(/_/g, ' ') : ''"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Nivel de Experiencia</label>
                                        <p class="mt-1 text-gray-900" x-text="objetivoSeleccionado.nivel_experiencia ? objetivoSeleccionado.nivel_experiencia.charAt(0).toUpperCase() + objetivoSeleccionado.nivel_experiencia.slice(1) : ''"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Días de Entrenamiento</label>
                                        <p class="mt-1 text-gray-900" x-text="objetivoSeleccionado.dias_entrenamiento + ' días'"></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-emerald-600 uppercase tracking-wider">Condiciones Médicas</label>
                                        <p class="mt-1 text-gray-900" x-text="objetivoSeleccionado.condiciones_medicas ? objetivoSeleccionado.condiciones_medicas.charAt(0).toUpperCase() + objetivoSeleccionado.condiciones_medicas.slice(1) : 'Ninguna'"></p>
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