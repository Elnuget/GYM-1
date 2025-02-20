<x-app-layout>
    <div x-data="{ 
        isModalOpen: false,
        isEditModalOpen: false,
        currentImplemento: null,
        toggleModal() {
            this.isModalOpen = !this.isModalOpen;
        },
        toggleEditModal(implemento = null) {
            this.isEditModalOpen = !this.isEditModalOpen;
            this.currentImplemento = implemento;
            if(implemento) {
                this.$nextTick(() => {
                    document.getElementById('edit_nombre_implemento').value = implemento.nombre_implemento;
                    document.getElementById('edit_descripcion').value = implemento.descripcion;
                    document.getElementById('edit_cantidad').value = implemento.cantidad;
                    document.getElementById('edit_estado').value = implemento.estado;
                });
            }
        }
    }">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con gradiente -->
                <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-white">
                        Gestión de Implementos
                    </h2>
                    <button @click="toggleModal()" 
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nuevo Implemento
                    </button>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabla -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <table class="min-w-full divide-y divide-emerald-200">
                        <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-emerald-100">
                            @foreach ($implementos as $implemento)
                                <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $implemento->nombre_implemento }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ Str::limit($implemento->descripcion, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $implemento->cantidad }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $implemento->estado === 'operativo' ? 'bg-green-100 text-green-800' : 
                                               ($implemento->estado === 'en_reparacion' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $implemento->estado_formateado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <button @click="toggleEditModal({{ $implemento->toJson() }})" 
                                                    class="text-teal-600 hover:text-teal-900">
                                                Editar
                                            </button>
                                            <form action="{{ route('implementos.destroy', $implemento) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('¿Está seguro?')">
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

                <div class="mt-4">
                    {{ $implementos->links() }}
                </div>

                <!-- Modal Crear -->
                <div x-show="isModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-lg w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Registrar Nuevo Implemento
                                </h2>
                                <form action="{{ route('implementos.store') }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nombre del Implemento</label>
                                            <input type="text" name="nombre_implemento" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Descripción</label>
                                            <textarea name="descripcion" rows="3" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Cantidad</label>
                                            <input type="number" name="cantidad" min="0" required
                                                   value="1"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Estado</label>
                                            <select name="estado" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="operativo">Operativo</option>
                                                <option value="en_reparacion">En Reparación</option>
                                                <option value="fuera_servicio">Fuera de Servicio</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" @click="toggleModal()"
                                                class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-md">
                                            Guardar Implemento
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Editar -->
                <div x-show="isEditModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-lg w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Editar Implemento
                                </h2>
                                <form :action="'{{ route('implementos.update', '') }}/' + currentImplemento?.id_implemento" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nombre del Implemento</label>
                                            <input type="text" id="edit_nombre_implemento" name="nombre_implemento" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Descripción</label>
                                            <textarea id="edit_descripcion" name="descripcion" rows="3" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Cantidad</label>
                                            <input type="number" id="edit_cantidad" name="cantidad" min="0" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Estado</label>
                                            <select id="edit_estado" name="estado" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="operativo">Operativo</option>
                                                <option value="en_reparacion">En Reparación</option>
                                                <option value="fuera_servicio">Fuera de Servicio</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" @click="toggleEditModal()"
                                                class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-md">
                                            Actualizar Implemento
                                        </button>
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