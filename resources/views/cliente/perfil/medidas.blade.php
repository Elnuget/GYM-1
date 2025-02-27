<x-cliente-layout>
    <div x-data="{ showModal: false, medidaSeleccionada: null }" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Principal -->
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
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Fecha
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                            </svg>
                                            Peso
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Altura
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            IMC
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                        Acciones
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
                                        <button @click="showModal = true; medidaSeleccionada = {
                                            fecha_medicion: '{{ $medida->fecha_medicion->format('Y-m-d') }}',
                                            peso: '{{ $medida->peso }}',
                                            altura: '{{ $medida->altura }}',
                                            cintura: '{{ $medida->cintura }}',
                                            pecho: '{{ $medida->pecho }}',
                                            biceps: '{{ $medida->biceps }}',
                                            muslos: '{{ $medida->muslos }}',
                                            pantorrillas: '{{ $medida->pantorrillas }}'
                                        }"
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

        <!-- Modal para Agregar/Ver Detalles -->
        <div x-show="showModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500/75 backdrop-blur-sm"></div>
                </div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-2 border-emerald-100 dark:border-emerald-900"
                     @click.away="showModal = false">
                    
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                        <h3 class="text-xl font-medium text-white" x-text="medidaSeleccionada ? 'Detalles de Medidas' : 'Agregar Nueva Medida'">
                        </h3>
                    </div>

                    <div class="p-6 bg-gradient-to-b from-white to-emerald-50 dark:from-gray-800 dark:to-gray-900">
                        <form x-show="!medidaSeleccionada" action="{{ route('cliente.perfil.medidas.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Fecha</label>
                                    <input type="date" name="fecha_medicion" required
                                           class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Peso (kg)</label>
                                    <input type="number" name="peso" step="0.01" required
                                           class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Altura (cm)</label>
                                    <input type="number" name="altura" step="0.01" required
                                           class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Cintura (cm)</label>
                                    <input type="number" name="cintura" step="0.01" required
                                           class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Pecho (cm)</label>
                                    <input type="number" name="pecho" step="0.01" required
                                           class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Bíceps (cm)</label>
                                    <input type="number" name="biceps" step="0.01" required
                                           class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Muslos (cm)</label>
                                    <input type="number" name="muslos" step="0.01" required
                                           class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-emerald-600">Pantorrillas (cm)</label>
                                    <input type="number" name="pantorrillas" step="0.01" required
                                           class="mt-1 block w-full rounded-md border-emerald-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" @click="showModal = false"
                                        class="px-4 py-2 text-sm font-medium text-emerald-600 hover:text-emerald-700">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700">
                                    Guardar Medidas
                                </button>
                            </div>
                        </form>

                        <!-- Vista de detalles -->
                        <template x-if="medidaSeleccionada">
                            <div class="grid grid-cols-2 gap-4">
                                <template x-for="(value, key) in medidaSeleccionada" :key="key">
                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900">
                                        <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider" 
                                               x-text="key.replace('_', ' ')">
                                        </label>
                                        <p class="mt-2 text-gray-900 dark:text-white font-medium" 
                                           x-text="key === 'fecha_medicion' ? new Date(value).toLocaleDateString() : value + ' cm'">
                                        </p>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-cliente-layout> 