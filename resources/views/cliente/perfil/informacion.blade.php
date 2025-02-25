<x-cliente-layout>
    <div x-data="{ showModal: false }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Principal -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Encabezado -->
                <div class="bg-emerald-600 p-6">
                    <h2 class="text-2xl font-semibold text-white">Información Personal</h2>
                    <p class="mt-1 text-sm text-emerald-50">Gestiona tu información personal y datos de contacto</p>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna Izquierda -->
                        <div>
                            <div class="mb-6">
                                <label class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Nombre</label>
                                <p class="mt-2 text-gray-900">{{ auth()->user()->name }}</p>
                                <div class="mt-2 h-px bg-gradient-to-r from-emerald-100 to-transparent"></div>
                            </div>
                            
                            <div class="mb-6">
                                <label class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Email</label>
                                <p class="mt-2 text-gray-900">{{ auth()->user()->email }}</p>
                                <div class="mt-2 h-px bg-gradient-to-r from-emerald-100 to-transparent"></div>
                            </div>

                            <div class="mb-6">
                                <label class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Fecha de Nacimiento</label>
                                <p class="mt-2 text-gray-900">
                                    {{ $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('d/m/Y') : 'No especificada' }}
                                </p>
                                <div class="mt-2 h-px bg-gradient-to-r from-emerald-100 to-transparent"></div>
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div>
                            <div class="mb-6">
                                <label class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Teléfono</label>
                                <p class="mt-2 text-gray-900">{{ $cliente->telefono ?? 'No especificado' }}</p>
                                <div class="mt-2 h-px bg-gradient-to-r from-emerald-100 to-transparent"></div>
                            </div>

                            <div class="mb-6">
                                <label class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Género</label>
                                <p class="mt-2 text-gray-900">
                                    @if($cliente->genero)
                                        {{ $cliente->genero == 'M' ? 'Masculino' : ($cliente->genero == 'F' ? 'Femenino' : 'Otro') }}
                                    @else
                                        No especificado
                                    @endif
                                </p>
                                <div class="mt-2 h-px bg-gradient-to-r from-emerald-100 to-transparent"></div>
                            </div>

                            <div class="mb-6">
                                <label class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Ocupación</label>
                                <p class="mt-2 text-gray-900">{{ $cliente->ocupacion ?? 'No especificada' }}</p>
                                <div class="mt-2 h-px bg-gradient-to-r from-emerald-100 to-transparent"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Editar -->
                    <div class="mt-6 flex justify-end">
                        <button type="button" 
                                @click="showModal = true"
                                class="inline-flex items-center px-4 py-2 bg-emerald-600 text-sm text-white rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Editar Información
                        </button>
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