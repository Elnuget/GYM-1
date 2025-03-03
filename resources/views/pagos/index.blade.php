<x-app-layout>
    <div x-data="pagoData">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con gradiente -->
                <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-white">
                        Pagos
                    </h2>
                    <button @click="toggleModal()" 
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nuevo Pago
                    </button>
                </div>

                <!-- Tabla con nuevo diseño -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Usuario
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Membresía
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Método de Pago
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha de Pago
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($pagos as $pago)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $pago->usuario->name ?? 'Usuario no asignado' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $pago->membresia->tipoMembresia->nombre ?? 'Membresía no asignada' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                ${{ number_format($pago->monto, 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
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
                                                        Método no definido
                                                @endswitch
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $pago->fecha_pago->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button type="button" @click="toggleEditModal(@json($pago))"
                                                    class="text-emerald-600 hover:text-emerald-900">
                                                Editar
                                            </button>
                                            <form class="inline-block" action="{{ route('pagos.destroy', $pago) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-900"
                                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este pago?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginación con estilo mejorado -->
                <div class="mt-4">
                    {{ $pagos->links() }}
                </div>

                <!-- Modal de Nuevo Pago -->
                <div x-show="isModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Nuevo Pago
                                </h2>
                                <form action="{{ route('pagos.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Usuario</label>
                                        <select name="id_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Membresía</label>
                                        <select name="id_membresia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($membresias as $membresia)
                                                <option value="{{ $membresia->id_membresia }}">
                                                    {{ $membresia->tipoMembresia->nombre ?? 'No asignada' }} - {{ $membresia->usuario->name ?? 'Usuario no asignado' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Monto</label>
                                        <input type="number" step="0.01" name="monto" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Estado del Pago</label>
                                        <select name="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="pendiente">Pendiente</option>
                                            <option value="aprobado">Aprobado</option>
                                            <option value="rechazado">Rechazado</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Comprobante</label>
                                        <input type="file" name="comprobante" 
                                               class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                                        <textarea name="notas" rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                        <select name="id_metodo_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($metodosPago as $metodo)
                                                <option value="{{ $metodo->id_metodo_pago }}">
                                                    @switch($metodo->nombre_metodo)
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
                                                            {{ $metodo->nombre_metodo }}
                                                    @endswitch
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                                        <input type="date" name="fecha_pago" value="{{ date('Y-m-d') }}"
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
                                            Crear Pago
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Edición -->
                <x-modal name="edit-modal" x-show="isEditModalOpen" focusable>
                    <form method="POST" :action="'/pagos/' + currentPago?.id" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')

                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                            Editar Pago
                        </h2>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Membresía</label>
                            <select name="id_membresia" id="edit_id_membresia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @foreach($membresias as $membresia)
                                    <option value="{{ $membresia->id_membresia }}">
                                        {{ $membresia->tipoMembresia->nombre ?? 'No asignada' }} - {{ $membresia->usuario->name ?? 'Usuario no asignado' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Usuario</label>
                            <select name="id_usuario" id="edit_id_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Monto</label>
                            <input type="number" step="0.01" name="monto" id="edit_monto"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                            <input type="date" name="fecha_pago" id="edit_fecha_pago"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select name="estado" id="edit_estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="pendiente">Pendiente</option>
                                <option value="aprobado">Aprobado</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                            <select name="id_metodo_pago" id="edit_id_metodo_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @foreach($metodosPago as $metodo)
                                    <option value="{{ $metodo->id_metodo_pago }}">
                                        @switch($metodo->nombre_metodo)
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
                                                {{ $metodo->nombre_metodo }}
                                        @endswitch
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Comprobante Actual</label>
                            <div x-show="currentPago?.comprobante_url" class="mt-2">
                                <a :href="'/storage/' + currentPago?.comprobante_url" target="_blank" 
                                   class="text-emerald-600 hover:text-emerald-900">
                                    Ver comprobante actual
                                </a>
                            </div>
                            <div class="mt-2">
                                <label class="block text-sm font-medium text-gray-700">Actualizar Comprobante</label>
                                <input type="file" name="comprobante" 
                                       class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Notas</label>
                            <textarea name="notas" id="edit_notas" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button type="button" @click="$dispatch('close')">
                                Cancelar
                            </x-secondary-button>

                            <x-primary-button class="ml-3">
                                Actualizar
                            </x-primary-button>
                        </div>
                    </form>
                </x-modal>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pagoData', () => ({
            isModalOpen: false,
            isEditModalOpen: false,
            currentPago: null,
            toggleModal() {
                this.isModalOpen = !this.isModalOpen;
            },
            toggleEditModal(pago = null) {
                this.isEditModalOpen = !this.isEditModalOpen;
                this.currentPago = pago;
                if(pago) {
                    this.$nextTick(() => {
                        document.getElementById('edit_id_membresia').value = pago.id_membresia;
                        document.getElementById('edit_id_usuario').value = pago.id_usuario;
                        document.getElementById('edit_monto').value = pago.monto;
                        document.getElementById('edit_fecha_pago').value = pago.fecha_pago;
                        document.getElementById('edit_estado').value = pago.estado;
                        document.getElementById('edit_id_metodo_pago').value = pago.id_metodo_pago;
                        document.getElementById('edit_notas').value = pago.notas || '';
                    });
                }
            }
        }));
    });
</script> 