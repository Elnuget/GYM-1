<x-app-layout>
    <div x-data="{ 
        isModalOpen: false,
        isEditModalOpen: false,
        currentAsistencia: null,
        toggleModal() {
            this.isModalOpen = !this.isModalOpen;
        },
        toggleEditModal(asistencia = null) {
            this.isEditModalOpen = !this.isEditModalOpen;
            this.currentAsistencia = asistencia;
            if(asistencia) {
                this.$nextTick(() => {
                    document.getElementById('edit_cliente_id').value = asistencia.cliente_id;
                    document.getElementById('edit_fecha').value = asistencia.fecha;
                    document.getElementById('edit_hora_entrada').value = asistencia.hora_entrada;
                    document.getElementById('edit_hora_salida').value = asistencia.hora_salida;
                    document.getElementById('edit_notas').value = asistencia.notas;
                });
            }
        }
    }">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con gradiente -->
                <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-white">
                        Asistencias
                    </h2>
                    <button @click="toggleModal()" 
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nueva Asistencia
                    </button>
                </div>

                <!-- Tabla con nuevo diseño -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-emerald-200">
                            <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Hora Entrada</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Hora Salida</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Duración</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-emerald-100">
                                @foreach ($asistencias as $asistencia)
                                    <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asistencia->cliente->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($asistencia->fecha)->timezone('America/Guayaquil')->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($asistencia->hora_entrada)->timezone('America/Guayaquil')->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($asistencia->hora_salida)
                                                {{ \Carbon\Carbon::parse($asistencia->hora_salida)->timezone('America/Guayaquil')->format('H:i') }}
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($asistencia->hora_salida)
                                                {{ $asistencia->duracion_formateada }}
                                            @else
                                                En curso
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $asistencia->estado == 'activa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($asistencia->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex space-x-3">
                                                <button @click="toggleEditModal({{ $asistencia }})" 
                                                        class="text-teal-600 hover:text-teal-900 font-medium">
                                                    Editar
                                                </button>
                                                @if(!$asistencia->hora_salida)
                                                    <form action="{{ route('asistencias.registrar-salida', $asistencia) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                            Registrar Salida
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('asistencias.destroy', $asistencia) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 font-medium">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginación con estilo mejorado -->
                <div class="mt-4">
                    {{ $asistencias->links() }}
                </div>

                <!-- Modal de Nueva Asistencia -->
                <div x-show="isModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Nueva Asistencia
                                </h2>
                                <form action="{{ route('asistencias.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Cliente</label>
                                        <select name="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                                        <input type="date" name="fecha" value="{{ \Carbon\Carbon::now('America/Guayaquil')->format('Y-m-d') }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Hora de Entrada</label>
                                        <input type="time" name="hora_entrada" value="{{ \Carbon\Carbon::now('America/Guayaquil')->format('H:i') }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Hora de Salida (opcional)</label>
                                        <input type="time" name="hora_salida" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Notas (opcional)</label>
                                        <textarea name="notas" rows="3" 
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" 
                                                @click="toggleModal()"
                                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                                            Registrar Asistencia
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Editar Asistencia -->
                <div x-show="isEditModalOpen" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <!-- Overlay con opacidad mejorada -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                    <!-- Modal Content -->
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <!-- Header del modal con gradiente -->
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Editar Asistencia
                                </h2>

                                <form x-bind:action="'/asistencias/' + currentAsistencia?.id_asistencia" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                                            <select id="edit_cliente_id" name="cliente_id" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                @foreach($clientes as $cliente)
                                                    <option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Fecha</label>
                                            <input type="date" id="edit_fecha" name="fecha" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Hora de Entrada</label>
                                            <input type="time" id="edit_hora_entrada" name="hora_entrada" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Hora de Salida</label>
                                            <input type="time" id="edit_hora_salida" name="hora_salida" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Notas</label>
                                            <textarea id="edit_notas" name="notas" rows="3" 
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                        </div>

                                        <div class="mt-6 flex justify-end space-x-3">
                                            <button type="button" 
                                                    @click="toggleEditModal()"
                                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                                Cancelar
                                            </button>
                                            <button type="submit"
                                                    class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                                                Actualizar Asistencia
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
