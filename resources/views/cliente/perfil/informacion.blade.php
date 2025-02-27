<x-cliente-layout>
    <div x-data="{ showModal: false }" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Principal con borde verde sutil -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden border-2 border-emerald-100 dark:border-emerald-900">
                <!-- Encabezado con gradiente -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-white">Información Personal</h2>
                            <p class="mt-1 text-sm text-emerald-100">Gestiona tu información personal y datos de contacto</p>
                        </div>
                        <!-- Botón Editar con diseño actualizado -->
                        <button type="button" 
                                @click="showModal = true"
                                class="inline-flex items-center px-4 py-2 bg-white text-sm text-emerald-600 rounded-lg hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-600 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Editar Información
                        </button>
                    </div>
                </div>

                <!-- Contenido con diseño mejorado -->
                <div class="p-8 bg-gradient-to-b from-white to-emerald-50 dark:from-gray-800 dark:to-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <!-- Columna Izquierda -->
                        <div class="space-y-6">
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Nombre
                                </label>
                                <p class="mt-2 text-gray-900 dark:text-white font-medium">{{ auth()->user()->name }}</p>
                            </div>
                            
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Email
                                </label>
                                <p class="mt-2 text-gray-900 dark:text-white font-medium">{{ auth()->user()->email }}</p>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Fecha de Nacimiento
                                </label>
                                <p class="mt-2 text-gray-900 dark:text-white font-medium">
                                    {{ $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('d/m/Y') : 'No especificada' }}
                                </p>
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div class="space-y-6">
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    Teléfono
                                </label>
                                <p class="mt-2 text-gray-900 dark:text-white font-medium">{{ $cliente->telefono ?? 'No especificado' }}</p>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Género
                                </label>
                                <p class="mt-2 text-gray-900 dark:text-white font-medium">
                                    @if($cliente->genero)
                                        {{ $cliente->genero == 'M' ? 'Masculino' : ($cliente->genero == 'F' ? 'Femenino' : 'Otro') }}
                                    @else
                                        No especificado
                                    @endif
                                </p>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-emerald-100 dark:border-emerald-900 hover:shadow-md transition-shadow duration-300">
                                <label class="block text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Ocupación
                                </label>
                                <p class="mt-2 text-gray-900 dark:text-white font-medium">{{ $cliente->ocupacion ?? 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edición -->
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
                            Editar Información Personal
                        </h3>
                    </div>

                    <form method="POST" action="{{ route('cliente.perfil.actualizar') }}" class="p-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">
                            <!-- Gimnasio (como ejemplo del diseño que mostraste) -->
                            <div>
                                <label class="block text-sm font-medium text-emerald-600 mb-1">
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" 
                                       name="fecha_nacimiento" 
                                       value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento?->format('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-emerald-200 bg-white shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-emerald-600 mb-1">
                                    Teléfono
                                </label>
                                <input type="text" 
                                       name="telefono" 
                                       value="{{ old('telefono', $cliente->telefono) }}"
                                       class="mt-1 block w-full rounded-md border-emerald-200 bg-white shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-emerald-600 mb-1">
                                    Género
                                </label>
                                <select name="genero" 
                                        class="mt-1 block w-full rounded-md border-emerald-200 bg-white shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                                    <option value="M" {{ old('genero', $cliente->genero) == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('genero', $cliente->genero) == 'F' ? 'selected' : '' }}>Femenino</option>
                                    <option value="O" {{ old('genero', $cliente->genero) == 'O' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-emerald-600 mb-1">
                                    Ocupación
                                </label>
                                <input type="text" 
                                       name="ocupacion" 
                                       value="{{ old('ocupacion', $cliente->ocupacion) }}"
                                       class="mt-1 block w-full rounded-md border-emerald-200 bg-white shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    @click="showModal = false"
                                    class="px-4 py-2 text-sm font-medium text-emerald-600 hover:text-emerald-700 focus:outline-none">
                                Cancelar
                            </button>

                            <button type="submit"
                                    class="px-4 py-2 bg-emerald-600 text-sm font-medium text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-cliente-layout> 