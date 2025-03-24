<x-app-layout>
    <div x-data="{ 
        isModalOpen: false,
        isEditModalOpen: false,
        currentTipoMembresia: null,
        showNumeroVisitas: false,
        editShowNumeroVisitas: false,
        toggleModal() {
            this.isModalOpen = !this.isModalOpen;
            if(this.isModalOpen) {
                this.showNumeroVisitas = false;
            }
        },
        toggleEditModal(tipoMembresia = null) {
            this.isEditModalOpen = !this.isEditModalOpen;
            this.currentTipoMembresia = tipoMembresia;
            if(tipoMembresia) {
                this.$nextTick(() => {
                    document.getElementById('edit_gimnasio_id').value = tipoMembresia.gimnasio_id;
                    document.getElementById('edit_nombre').value = tipoMembresia.nombre;
                    document.getElementById('edit_descripcion').value = tipoMembresia.descripcion || '';
                    document.getElementById('edit_precio').value = tipoMembresia.precio;
                    document.getElementById('edit_duracion_dias').value = tipoMembresia.duracion_dias || '';
                    document.getElementById('edit_tipo').value = tipoMembresia.tipo;
                    document.getElementById('edit_numero_visitas').value = tipoMembresia.numero_visitas || '';
                    this.editShowNumeroVisitas = tipoMembresia.tipo === 'visitas';
                });
            }
        },
        checkTipoChange() {
            this.showNumeroVisitas = document.getElementById('tipo').value === 'visitas';
        },
        checkEditTipoChange() {
            this.editShowNumeroVisitas = document.getElementById('edit_tipo').value === 'visitas';
        }
    }">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con gradiente -->
                <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-white">
                        Tipos de Membresía
                    </h2>
                    <button @click="toggleModal()" 
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nuevo Tipo de Membresía
                    </button>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Tabla con nuevo diseño -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-emerald-200">
                            <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Gimnasio</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Precio</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Duración/Visitas</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-emerald-100">
                                @foreach ($tiposMembresia as $tipoMembresia)
                                    <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $tipoMembresia->gimnasio->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $tipoMembresia->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($tipoMembresia->precio, 2) }} $</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($tipoMembresia->tipo === 'visitas')
                                                {{ $tipoMembresia->numero_visitas }} visitas
                                            @else
                                                {{ $tipoMembresia->duracion_dias }} días
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $tipoMembresia->tipo === 'mensual' ? 'bg-blue-100 text-blue-800' : 
                                                   ($tipoMembresia->tipo === 'anual' ? 'bg-emerald-100 text-emerald-800' : 
                                                   'bg-purple-100 text-purple-800') }}">
                                                {{ ucfirst($tipoMembresia->tipo) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $tipoMembresia->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $tipoMembresia->estado ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex space-x-3">
                                                <button @click="toggleEditModal({{ $tipoMembresia }})" 
                                                        class="text-teal-600 hover:text-teal-900 font-medium">
                                                    Editar
                                                </button>
                                                <form action="{{ route('tipos-membresia.cambiar-estado', $tipoMembresia) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="{{ $tipoMembresia->estado ? 'text-amber-600 hover:text-amber-900' : 'text-emerald-600 hover:text-emerald-900' }} font-medium">
                                                        {{ $tipoMembresia->estado ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('tipos-membresia.destroy', $tipoMembresia) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 font-medium"
                                                            onclick="return confirm('¿Está seguro que desea eliminar este tipo de membresía?')">
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
                    {{ $tiposMembresia->links() }}
                </div>

                <!-- Modal de Nuevo Tipo de Membresía -->
                <div x-show="isModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Nuevo Tipo de Membresía
                                </h2>
                                <form action="{{ route('tipos-membresia.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Gimnasio</label>
                                        <select name="gimnasio_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($gimnasios as $gimnasio)
                                                <option value="{{ $gimnasio->id_gimnasio }}">{{ $gimnasio->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                        <input type="text" name="nombre" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                        <textarea name="descripcion" rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Precio</label>
                                        <input type="number" name="precio" step="0.01" min="0" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                        <select id="tipo" name="tipo" @change="checkTipoChange()"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="mensual">Mensual</option>
                                            <option value="anual">Anual</option>
                                            <option value="visitas">Por Visitas</option>
                                        </select>
                                    </div>

                                    <div class="mb-4" x-show="!showNumeroVisitas">
                                        <label class="block text-sm font-medium text-gray-700">Duración (días)</label>
                                        <input type="number" name="duracion_dias" min="1"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4" x-show="showNumeroVisitas">
                                        <label class="block text-sm font-medium text-gray-700">Número de Visitas</label>
                                        <input type="number" name="numero_visitas" min="1"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" 
                                                @click="toggleModal()"
                                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                                            Crear Tipo de Membresía
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Editar Tipo de Membresía -->
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
                                    Editar Tipo de Membresía
                                </h2>

                                <form x-bind:action="'/tipos-membresia/' + currentTipoMembresia?.id_tipo_membresia" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Gimnasio</label>
                                            <select id="edit_gimnasio_id" name="gimnasio_id" 
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                @foreach($gimnasios as $gimnasio)
                                                    <option value="{{ $gimnasio->id_gimnasio }}">{{ $gimnasio->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nombre</label>
                                            <input type="text" id="edit_nombre" name="nombre" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Descripción</label>
                                            <textarea id="edit_descripcion" name="descripcion" rows="3"
                                                      class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Precio</label>
                                            <input type="number" id="edit_precio" name="precio" step="0.01" min="0" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Tipo</label>
                                            <select id="edit_tipo" name="tipo" @change="checkEditTipoChange()"
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="mensual">Mensual</option>
                                                <option value="anual">Anual</option>
                                                <option value="visitas">Por Visitas</option>
                                            </select>
                                        </div>

                                        <div x-show="!editShowNumeroVisitas">
                                            <label class="block text-sm font-medium text-emerald-700">Duración (días)</label>
                                            <input type="number" id="edit_duracion_dias" name="duracion_dias" min="1"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div x-show="editShowNumeroVisitas">
                                            <label class="block text-sm font-medium text-emerald-700">Número de Visitas</label>
                                            <input type="number" id="edit_numero_visitas" name="numero_visitas" min="1"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" 
                                                @click="toggleEditModal()"
                                                class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-md">
                                            Actualizar Tipo de Membresía
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