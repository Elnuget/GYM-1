<x-app-layout>
    <div x-data="{ 
        isModalOpen: false,
        isEditModalOpen: false,
        currentDueno: null,
        toggleModal() {
            this.isModalOpen = !this.isModalOpen;
        },
        toggleEditModal(dueno = null) {
            this.isEditModalOpen = !this.isEditModalOpen;
            this.currentDueno = dueno;
            if(dueno) {
                this.$nextTick(() => {
                    document.getElementById('edit_name').value = dueno.name || '';
                    document.getElementById('edit_email').value = dueno.email || '';
                    document.getElementById('edit_telefono').value = dueno.telefono || '';
                    document.getElementById('edit_direccion').value = dueno.direccion || '';
                });
            }
        }
    }">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con gradiente -->
                <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-white">
                        Dueños de Gimnasios
                    </h2>
                    <button @click="toggleModal()" 
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Registrar Nuevo Dueño
                    </button>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabla con nuevo diseño -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-emerald-200">
                            <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Teléfono</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Dirección</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-emerald-100">
                                @foreach($duenos as $dueno)
                                    <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dueno->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dueno->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dueno->telefono }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $dueno->direccion }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <button @click="toggleEditModal({{ $dueno->toJson() }})" 
                                                        class="text-teal-600 hover:text-teal-900">
                                                    Editar
                                                </button>
                                                <form action="{{ route('duenos-gimnasio.destroy', $dueno) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('¿Estás seguro?')">
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

                <!-- Modal Crear Dueño -->
                <div x-show="isModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Registrar Nuevo Dueño
                                </h2>
                                <form action="{{ route('duenos-gimnasio.store') }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nombre</label>
                                            <input type="text" name="name" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Email</label>
                                            <input type="email" name="email" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Teléfono</label>
                                            <input type="text" name="telefono" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Dirección</label>
                                            <input type="text" name="direccion" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Contraseña</label>
                                            <input type="password" name="password" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Confirmar Contraseña</label>
                                            <input type="password" name="password_confirmation" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" @click="toggleModal()"
                                                class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-md">
                                            Registrar Dueño
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Editar Dueño -->
                <div x-show="isEditModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Editar Dueño
                                </h2>
                                <form :action="'{{ route('duenos-gimnasio.update', '') }}/' + currentDueno?.id" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nombre</label>
                                            <input type="text" id="edit_name" name="name" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Email</label>
                                            <input type="email" id="edit_email" name="email" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Teléfono</label>
                                            <input type="text" id="edit_telefono" name="telefono" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Dirección</label>
                                            <input type="text" id="edit_direccion" name="direccion" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nueva Contraseña (opcional)</label>
                                            <input type="password" name="password"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Confirmar Nueva Contraseña</label>
                                            <input type="password" name="password_confirmation"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" @click="toggleEditModal()"
                                                class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-md">
                                            Actualizar Dueño
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