<x-app-layout>
    <div x-data="{ 
        isModalOpen: false,
        isEditModalOpen: false,
        isViewModalOpen: false,
        currentCliente: null,
        toggleModal() {
            this.isModalOpen = !this.isModalOpen;
        },
        toggleEditModal(cliente = null) {
            this.isEditModalOpen = !this.isEditModalOpen;
            this.currentCliente = cliente;
            if(cliente) {
                this.$nextTick(() => {
                    document.getElementById('edit_gimnasio_id').value = cliente.gimnasio_id;
                    document.getElementById('edit_nombre').value = cliente.nombre;
                    document.getElementById('edit_email').value = cliente.email;
                    document.getElementById('edit_telefono').value = cliente.telefono || '';
                    document.getElementById('edit_fecha_nacimiento').value = cliente.fecha_nacimiento || '';
                });
            }
        },
        toggleViewModal(cliente = null) {
            this.isViewModalOpen = !this.isViewModalOpen;
            this.currentCliente = cliente;
        }
    }">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con gradiente -->
                <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-white">
                        Clientes
                    </h2>
                    <button @click="toggleModal()" 
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear Nuevo Cliente
                    </button>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabla -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <table class="min-w-full divide-y divide-emerald-200">
                        <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Foto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha Nacimiento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Gimnasio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-emerald-100">
                            @foreach($clientes->sortByDesc('id_cliente') as $cliente)
                                <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->id_cliente }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center">
                                            @if($cliente->foto_perfil && file_exists(public_path($cliente->foto_perfil)))
                                                <img src="{{ asset($cliente->foto_perfil) }}" alt="{{ $cliente->nombre }}" 
                                                     class="h-10 w-10 rounded-full object-cover border-2 border-emerald-200">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-emerald-600 flex items-center justify-center">
                                                    <span class="text-sm font-bold text-white">
                                                        {{ substr($cliente->nombre, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->telefono ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('d/m/Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->gimnasio->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <button @click="toggleViewModal({{ $cliente->toJson() }})" 
                                                    class="text-blue-600 hover:text-blue-900">
                                                Ver
                                            </button>
                                            <button @click="toggleEditModal({{ $cliente->toJson() }})" 
                                                    class="text-teal-600 hover:text-teal-900">
                                                Editar
                                            </button>
                                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline">
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

                <!-- Modal Crear -->
                <div x-show="isModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Crear Nuevo Cliente
                                </h2>
                                <form action="{{ route('clientes.store') }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Gimnasio</label>
                                            <select name="gimnasio_id" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="">Seleccione un gimnasio</option>
                                                @foreach($gimnasios as $gimnasio)
                                                    <option value="{{ $gimnasio->id_gimnasio }}">{{ $gimnasio->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nombre</label>
                                            <input type="text" name="nombre" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Email</label>
                                            <input type="email" name="email" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Teléfono</label>
                                            <input type="text" name="telefono"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Fecha de Nacimiento</label>
                                            <input type="date" name="fecha_nacimiento"
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
                                            Crear Cliente
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
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Editar Cliente
                                </h2>
                                <form x-bind:action="'/clientes/' + currentCliente?.id_cliente" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Gimnasio</label>
                                            <select id="edit_gimnasio_id" name="gimnasio_id" required
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
                                            <label class="block text-sm font-medium text-emerald-700">Email</label>
                                            <input type="email" id="edit_email" name="email" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Teléfono</label>
                                            <input type="text" id="edit_telefono" name="telefono"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Fecha de Nacimiento</label>
                                            <input type="date" id="edit_fecha_nacimiento" name="fecha_nacimiento"
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
                                            Actualizar Cliente
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Ver -->
                <div x-show="isViewModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Detalles del Cliente
                                </h2>
                                <div class="space-y-4">
                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Nombre</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.nombre"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Email</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.email"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Teléfono</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.telefono || 'N/A'"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Fecha de Nacimiento</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.fecha_nacimiento || 'N/A'"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Gimnasio</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.gimnasio.nombre"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Fecha de Registro</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.created_at"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Última Actualización</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.updated_at"></p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="button" @click="toggleViewModal()"
                                            class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Membresías -->
                <div class="mt-8">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg mb-4">
                        <h2 class="text-xl font-semibold text-white">Todas las Membresías</h2>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-emerald-200">
                                <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Cliente</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Tipo</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Precio</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Saldo Pendiente</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Vencimiento</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Visitas</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-emerald-100">
                                    @foreach($todasLasMembresias ?? [] as $membresia)
                                        <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $membresia->usuario->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $membresia->tipoMembresia->nombre }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($membresia->precio_total, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $membresia->saldo_pendiente > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    ${{ number_format($membresia->saldo_pendiente, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $membresia->fecha_vencimiento ? $membresia->fecha_vencimiento->format('d/m/Y') : 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($membresia->visitas_permitidas)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                        {{ $membresia->visitas_restantes }}/{{ $membresia->visitas_permitidas }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('membresias.edit', $membresia->id_membresia) }}" 
                                                       class="text-teal-600 hover:text-teal-900 font-medium">
                                                        Editar
                                                    </a>
                                                    <a href="{{ route('membresias.pagos', $membresia->id_membresia) }}"
                                                       class="text-blue-600 hover:text-blue-900 font-medium">
                                                        Pagos
                                                    </a>
                                                    @if($membresia->tipo_membresia === 'por_visitas')
                                                        <form action="{{ route('membresias.registrar-visita', $membresia->id_membresia) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                                Registrar Visita
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 