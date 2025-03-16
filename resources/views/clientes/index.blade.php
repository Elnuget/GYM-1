<x-app-layout>
    <div x-data="{ 
        isModalOpen: false,
        isEditModalOpen: false,
        isViewModalOpen: false,
        currentCliente: null,
        showNotificationModal: false,
        notificationType: '',
        notificationMessage: '',
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
                    document.getElementById('edit_genero').value = cliente.genero || '';
                    document.getElementById('edit_direccion').value = cliente.direccion || '';
                });
            }
        },
        toggleViewModal(cliente = null) {
            this.isViewModalOpen = !this.isViewModalOpen;
            this.currentCliente = cliente;
        },
        tablaClientesAbierta: false,
        deleteCliente(clienteId, clienteNombre) {
            if (confirm('¿Está seguro de eliminar a ' + clienteNombre + '?')) {
                const csrfToken = document.querySelector('meta[name=csrf-token]').content;
                
                fetch(`/clientes/${clienteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    this.showNotificationModal = true;
                    if (data.success) {
                        this.notificationType = 'success';
                        this.notificationMessage = data.message || 'Cliente eliminado exitosamente';
                        
                        // Remover la fila de la tabla
                        document.querySelector(`tr[data-cliente-id='${clienteId}']`).remove();
                    } else {
                        this.notificationType = 'error';
                        this.notificationMessage = data.message || 'Error al eliminar el cliente';
                    }
                })
                .catch(error => {
                    this.showNotificationModal = true;
                    this.notificationType = 'error';
                    this.notificationMessage = 'Error de conexión: ' + error.message;
                });
            }
        }
    }">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con gradiente -->
                <div @click="tablaClientesAbierta = !tablaClientesAbierta" 
                     class="flex flex-col sm:flex-row justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg cursor-pointer hover:from-emerald-700 hover:to-teal-700 transition-colors duration-200">
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div class="flex items-center">
                            <h2 class="text-2xl font-semibold text-white mr-2">
                                Clientes
                            </h2>
                            <svg class="w-5 h-5 transform transition-transform text-white" 
                                 :class="{'rotate-180': tablaClientesAbierta}"
                                 fill="none" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($gimnasios as $gimnasio)
                                <span class="px-3 py-1 text-sm bg-white bg-opacity-20 rounded-full text-white">
                                    {{ $gimnasio->nombre }}: {{ $clientes->where('gimnasio_id', $gimnasio->id_gimnasio)->count() }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <button @click.stop="toggleModal()" 
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="whitespace-nowrap">Añadir Cliente</span>
                    </button>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabla -->
                <div x-show="tablaClientesAbierta" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-4"
                     class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-emerald-200">
                            <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Foto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Teléfono</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Gimnasio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-emerald-100">
                                @foreach($clientes->sortByDesc('id_cliente') as $cliente)
                                    <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150"
                                        data-cliente-id="{{ $cliente->id_cliente }}">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->telefono ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->gimnasio->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <button @click="toggleViewModal({{ $cliente->toJson() }})" 
                                                        class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-100 transition-colors duration-150"
                                                        title="Ver detalles">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button @click="toggleEditModal({{ $cliente->toJson() }})" 
                                                        class="text-teal-600 hover:text-teal-900 p-1 rounded-full hover:bg-teal-100 transition-colors duration-150"
                                                        title="Editar cliente">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button @click="deleteCliente({{ $cliente->id_cliente }}, '{{ $cliente->nombre }}')" 
                                                        class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100 transition-colors duration-150"
                                                        title="Eliminar cliente">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal Crear -->
                <div x-show="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Crear Nuevo Cliente
                                </h2>
                                <form id="createClientForm" @submit.prevent="
                                    const formData = new FormData($event.target);
                                    fetch($event.target.action, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            if (response.status === 422) {
                                                return response.json().then(data => {
                                                    throw { type: 'validation', errors: data.errors };
                                                });
                                            } else if (response.status === 500) {
                                                return response.json().then(data => {
                                                    throw { type: 'server', message: data.message || 'Error interno del servidor' };
                                                });
                                            }
                                            throw { type: 'http', status: response.status };
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        showNotificationModal = true;
                                        if (data.success) {
                                            notificationType = 'success';
                                            notificationMessage = data.message || '¡Cliente creado exitosamente!';
                                            setTimeout(() => {
                                                window.location.reload();
                                            }, 2000);
                                        } else {
                                            notificationType = 'error';
                                            notificationMessage = data.message || 'Error al crear el cliente';
                                        }
                                    })
                                    .catch(error => {
                                        showNotificationModal = true;
                                        notificationType = 'error';
                                        if (error.type === 'validation') {
                                            const errorMessages = Object.values(error.errors).flat();
                                            notificationMessage = errorMessages.join('\n');
                                        } else if (error.type === 'server') {
                                            notificationMessage = 'Error del servidor: ' + error.message;
                                        } else {
                                            notificationMessage = 'Error al procesar la solicitud. Por favor, intente nuevamente.';
                                        }
                                    })"
                                    action="{{ route('clientes.store') }}">
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

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Género</label>
                                            <select name="genero" 
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="">Seleccione un género</option>
                                                <option value="masculino">Masculino</option>
                                                <option value="femenino">Femenino</option>
                                                <option value="otro">Otro</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Dirección</label>
                                            <input type="text" name="direccion"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <!-- Campo oculto para la contraseña predeterminada -->
                                        <input type="hidden" name="password" value="gymflow2025">
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

                <!-- Modal de Notificación -->
                <div x-show="showNotificationModal" 
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4"
                         :class="{ 'border-l-4 border-green-500': notificationType === 'success', 'border-l-4 border-red-500': notificationType === 'error' }">
                        <div class="flex items-start">
                            <!-- Icono de éxito -->
                            <template x-if="notificationType === 'success'">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </template>
                            <!-- Icono de error -->
                            <template x-if="notificationType === 'error'">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </template>
                            
                            <div class="ml-3 w-full">
                                <h3 class="text-lg font-medium" :class="{ 'text-green-700': notificationType === 'success', 'text-red-700': notificationType === 'error' }">
                                    <span x-text="notificationType === 'success' ? 'Operación Exitosa' : 'Error'"></span>
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-700 whitespace-pre-line" x-text="notificationMessage"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex justify-end">
                            <button @click="showNotificationModal = false" 
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                                Cerrar
                            </button>
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
                                <form @submit.prevent="
                                    const formData = new FormData($event.target);
                                    fetch('/clientes/' + currentCliente.id_cliente, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            if (response.status === 422) {
                                                return response.json().then(data => {
                                                    throw { type: 'validation', errors: data.errors };
                                                });
                                            } else if (response.status === 500) {
                                                return response.json().then(data => {
                                                    throw { type: 'server', message: data.message || 'Error interno del servidor' };
                                                });
                                            }
                                            throw { type: 'http', status: response.status };
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        showNotificationModal = true;
                                        if (data.success) {
                                            notificationType = 'success';
                                            notificationMessage = data.message || '¡Cliente actualizado exitosamente!';
                                            setTimeout(() => {
                                                window.location.reload();
                                            }, 2000);
                                        } else {
                                            notificationType = 'error';
                                            notificationMessage = data.message || 'Error al actualizar el cliente';
                                        }
                                    })
                                    .catch(error => {
                                        showNotificationModal = true;
                                        notificationType = 'error';
                                        if (error.type === 'validation') {
                                            const errorMessages = Object.values(error.errors).flat();
                                            notificationMessage = errorMessages.join('\n');
                                        } else if (error.type === 'server') {
                                            notificationMessage = 'Error del servidor: ' + error.message;
                                        } else {
                                            notificationMessage = 'Error al procesar la solicitud. Por favor, intente nuevamente.';
                                        }
                                    })">
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

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Género</label>
                                            <select id="edit_genero" name="genero" 
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="">Seleccione un género</option>
                                                <option value="masculino">Masculino</option>
                                                <option value="femenino">Femenino</option>
                                                <option value="otro">Otro</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Dirección</label>
                                            <input type="text" id="edit_direccion" name="direccion"
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

                <!-- Tabla de Pagos -->
                <div class="mt-8">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg mb-4">
                        <h2 class="text-xl font-semibold text-white">Todos los Pagos</h2>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-emerald-200">
                                <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Usuario</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Membresía</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Monto</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Método de Pago</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha de Pago</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-emerald-100">
                                    @foreach($todosLosPagos ?? [] as $pago)
                                        <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->usuario->name ?? 'Usuario no asignado' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->membresia->tipoMembresia->nombre ?? 'Membresía no asignada' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($pago->monto, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @switch($pago->metodoPago->nombre_metodo ?? '')
                                                    @case('tarjeta_credito')
                                                        Tarjeta de Crédito
                                                        @break
                                                    @case('efectivo')
                                                        Efectivo
                                                        @break
                                                    @case('transferencia_bancaria')
                                                        Transferencia Bancaria
                                                        @break
                                                    @default
                                                        {{ $pago->metodoPago->nombre_metodo ?? 'Método no definido' }}
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    @switch($pago->estado)
                                                        @case('aprobado')
                                                            bg-green-100 text-green-800
                                                            @break
                                                        @case('pendiente')
                                                            bg-yellow-100 text-yellow-800
                                                            @break
                                                        @case('rechazado')
                                                            bg-red-100 text-red-800
                                                            @break
                                                        @default
                                                            bg-gray-100 text-gray-800
                                                    @endswitch
                                                ">
                                                    {{ ucfirst($pago->estado) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('pagos.edit', $pago->id_pago) }}" 
                                                       class="text-teal-600 hover:text-teal-900 font-medium">
                                                        Editar
                                                    </a>
                                                    @if($pago->estado === 'pendiente')
                                                        <form action="{{ route('pagos.aprobar', $pago->id_pago) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="text-green-600 hover:text-green-900 font-medium">
                                                                Aprobar
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('pagos.destroy', $pago->id_pago) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 font-medium"
                                                                onclick="return confirm('¿Está seguro de eliminar este pago?')">
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
                </div>

                <!-- Tabla de Asistencias -->
                <div class="mt-8">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg mb-4">
                        <h2 class="text-xl font-semibold text-white">Todas las Asistencias</h2>
                    </div>
                    
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
                                    @foreach ($todasLasAsistencias ?? [] as $asistencia)
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
                                                    <span class="text-sm text-gray-500">En curso</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $asistencia->estado == 'activa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($asistencia->estado) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('asistencias.edit', $asistencia->id_asistencia) }}" 
                                                       class="text-teal-600 hover:text-teal-900 font-medium">
                                                        Editar
                                                    </a>
                                                    @if(!$asistencia->hora_salida)
                                                        <form action="{{ route('asistencias.registrar-salida', $asistencia->id_asistencia) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                                Registrar Salida
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('asistencias.destroy', $asistencia->id_asistencia) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 font-medium"
                                                                onclick="return confirm('¿Está seguro de eliminar esta asistencia?')">
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 