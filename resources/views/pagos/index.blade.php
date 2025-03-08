<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>
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
                                            @if($pago->estado === 'pendiente')
                                                <button type="button" @click="toggleDetallesModal({{ $pago->id_pago }})"
                                                        class="text-blue-600 hover:text-blue-900 mr-2 inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Ver Detalles
                                                </button>
                                            @endif
                                            <button type="button" @click="toggleEditModal({{ $pago->id_pago }})"
                                                    class="text-emerald-600 hover:text-emerald-900 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </button>
                                            <form class="inline-block" action="{{ route('pagos.destroy', $pago) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="ml-2 text-red-600 hover:text-red-900 inline-flex items-center"
                                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este pago?')">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
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

                <!-- Modal de Detalles del Pago -->
                <div x-show="isDetallesModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     x-cloak
                     @keydown.escape.window="toggleDetallesModal()"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4" @click.away="toggleDetallesModal()">
                        <div class="relative bg-white rounded-xl max-w-2xl w-full shadow-xl">
                            <div class="absolute top-0 right-0 pt-4 pr-4">
                                <button type="button" @click="toggleDetallesModal()"
                                        class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Detalles del Pago
                                </h2>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Usuario</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="currentPago?.usuario?.name || 'No asignado'"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Membresía</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="currentPago?.membresia?.tipoMembresia?.nombre || 'No asignada'"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Monto</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="'$' + (currentPago?.monto || 0)"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="formatMetodoPago(currentPago?.metodoPago?.nombre_metodo)"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="currentPago?.fecha_pago || ''"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                                        <p class="mt-1">
                                            <span x-bind:class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800': currentPago?.estado === 'aprobado',
                                                'bg-yellow-100 text-yellow-800': currentPago?.estado === 'pendiente',
                                                'bg-red-100 text-red-800': currentPago?.estado === 'rechazado'
                                            }" x-text="currentPago?.estado"></span>
                                        </p>
                                    </div>

                                    <div class="col-span-2" x-show="currentPago?.comprobante_url">
                                        <label class="block text-sm font-medium text-gray-700">Comprobante</label>
                                        <a :href="'/storage/' + currentPago?.comprobante_url" 
                                           target="_blank"
                                           class="mt-1 text-sm text-emerald-600 hover:text-emerald-900 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver comprobante
                                        </a>
                                    </div>

                                    <div class="col-span-2" x-show="currentPago?.notas">
                                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="currentPago?.notas"></p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" 
                                            @click="toggleDetallesModal()"
                                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Cerrar
                                    </button>
                                    <template x-if="currentPago?.estado === 'pendiente'">
                                        <form :action="'/pagos/' + currentPago?.id_pago + '/aprobar'" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Aprobar Pago
                                            </button>
                                        </form>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Nuevo Pago -->
                <div x-show="isModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     x-cloak
                     @keydown.escape.window="toggleModal()"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4" @click.away="toggleModal()">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="absolute top-0 right-0 pt-4 pr-4">
                                <button type="button" @click="toggleModal()"
                                        class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Nuevo Pago
                                </h2>
                                <form action="{{ route('pagos.store') }}" method="POST" enctype="multipart/form-data">
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
                                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Crear Pago
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Edición -->
                <div x-show="isEditModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     x-cloak
                     @keydown.escape.window="toggleEditModal()"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4" @click.away="toggleEditModal()">
                        <div class="relative bg-white rounded-xl max-w-2xl w-full shadow-xl">
                            <div class="absolute top-0 right-0 pt-4 pr-4">
                                <button type="button" @click="toggleEditModal()"
                                        class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Editar Pago
                                </h2>
                                <form :action="'/pagos/' + currentPago?.id_pago" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Usuario</label>
                                        <select name="id_usuario" id="edit_id_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

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
                                        <label class="block text-sm font-medium text-gray-700">Monto</label>
                                        <input type="number" step="0.01" name="monto" id="edit_monto" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                                        <input type="date" name="fecha_pago" id="edit_fecha_pago" required
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
                                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                                        <textarea name="notas" id="edit_notas" rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" 
                                                @click="toggleEditModal()"
                                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Guardar Cambios
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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pagoData', () => ({
            isModalOpen: false,
            isEditModalOpen: false,
            isDetallesModalOpen: false,
            currentPago: null,
            pagos: @json($pagos->items()),
            
            toggleModal() {
                this.isModalOpen = !this.isModalOpen;
            },
            
            toggleEditModal(pagoId = null) {
                if (pagoId === null) {
                    // Si no se proporciona ID, solo alternamos el estado del modal
                    this.isEditModalOpen = !this.isEditModalOpen;
                    return;
                }
                
                // Busca el pago en el array de pagos
                const pago = this.pagos.find(p => p.id_pago === pagoId);
                
                if (pago) {
                    this.currentPago = pago;
                    this.isEditModalOpen = true;
                    
                    this.$nextTick(() => {
                        // Asegurarse de que los elementos existen antes de intentar asignar valores
                        if (document.getElementById('edit_id_membresia')) {
                            document.getElementById('edit_id_membresia').value = pago.id_membresia;
                        }
                        if (document.getElementById('edit_id_usuario')) {
                            document.getElementById('edit_id_usuario').value = pago.id_usuario;
                        }
                        if (document.getElementById('edit_monto')) {
                            document.getElementById('edit_monto').value = pago.monto;
                        }
                        if (document.getElementById('edit_fecha_pago')) {
                            document.getElementById('edit_fecha_pago').value = pago.fecha_pago;
                        }
                        if (document.getElementById('edit_estado')) {
                            document.getElementById('edit_estado').value = pago.estado;
                        }
                        if (document.getElementById('edit_id_metodo_pago')) {
                            document.getElementById('edit_id_metodo_pago').value = pago.id_metodo_pago;
                        }
                        if (document.getElementById('edit_notas')) {
                            document.getElementById('edit_notas').value = pago.notas || '';
                        }
                    });
                }
            },
            
            toggleDetallesModal(pagoId = null) {
                if (pagoId === null) {
                    // Si no se proporciona ID, solo alternamos el estado del modal
                    this.isDetallesModalOpen = !this.isDetallesModalOpen;
                    return;
                }
                
                // Busca el pago en el array de pagos
                const pago = this.pagos.find(p => p.id_pago === pagoId);
                
                if (pago) {
                    this.currentPago = pago;
                    this.isDetallesModalOpen = true;
                }
            },
            
            formatMetodoPago(metodo) {
                if (!metodo) return 'No definido';
                switch(metodo) {
                    case 'tarjeta_credito':
                        return 'Tarjeta de Crédito';
                    case 'efectivo':
                        return 'Efectivo';
                    case 'transferencia_bancaria':
                        return 'Transferencia Bancaria';
                    default:
                        return metodo;
                }
            }
        }));
    });
</script> 