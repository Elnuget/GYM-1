<x-app-layout>
    <div x-data="{ 
        isModalOpen: false,
        isEditModalOpen: false,
        showVisitasFields: false,
        currentMembresia: null,
        toggleModal() {
            this.isModalOpen = !this.isModalOpen;
        },
        toggleEditModal(membresia = null) {
            this.isEditModalOpen = !this.isEditModalOpen;
            this.currentMembresia = membresia;
            if(membresia) {
                this.$nextTick(() => {
                    document.getElementById('edit_id_usuario').value = membresia.id_usuario;
                    document.getElementById('edit_tipo_membresia').value = membresia.tipo_membresia;
                    document.getElementById('edit_precio_total').value = membresia.precio_total;
                    document.getElementById('edit_saldo_pendiente').value = membresia.saldo_pendiente;
                    document.getElementById('edit_fecha_compra').value = membresia.fecha_compra;
                    document.getElementById('edit_fecha_vencimiento').value = membresia.fecha_vencimiento;
                    document.getElementById('edit_visitas_permitidas').value = membresia.visitas_permitidas;
                    document.getElementById('edit_renovacion').checked = membresia.renovacion;
                    this.toggleEditVisitasFields(membresia.tipo_membresia);
                });
            }
        },
        toggleVisitasFields() {
            this.showVisitasFields = document.getElementById('tipo_membresia').value === 'por_visitas';
        },
        toggleEditVisitasFields(value) {
            const visitasFields = document.querySelector('.edit-visitas-fields');
            if (visitasFields) {
                visitasFields.style.display = value === 'por_visitas' ? 'block' : 'none';
            }
        }
    }">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con gradiente -->
                <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-white">
                        Membresías
                    </h2>
                    <button @click="toggleModal()" 
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nueva Membresía
                    </button>
                </div>

                <!-- Tabla con nuevo diseño -->
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
                                @foreach ($membresias as $membresia)
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
                                                <button @click="toggleEditModal({{ $membresia }})" 
                                                        class="text-teal-600 hover:text-teal-900 font-medium">
                                                    Editar
                                                </button>
                                                @if($membresia->tipo_membresia === 'por_visitas')
                                                    <form action="{{ route('membresias.registrar-visita', $membresia) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                            Registrar Visita
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('membresias.destroy', $membresia) }}" method="POST" class="inline">
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
                    {{ $membresias->links() }}
                </div>

                <!-- Modal de Nueva Membresía -->
                <div x-show="isModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Nueva Membresía
                                </h2>
                                <form action="{{ route('membresias.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Cliente</label>
                                        <select name="id_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Tipo de Membresía</label>
                                        <select name="id_tipo_membresia" 
                                                id="tipo_membresia" 
                                                @change="toggleVisitasFields()"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($tiposMembresia as $tipo)
                                                <option value="{{ $tipo->id_tipo_membresia }}" 
                                                        data-precio="{{ $tipo->precio }}"
                                                        data-duracion="{{ $tipo->duracion_dias }}"
                                                        data-tipo="{{ $tipo->tipo }}">
                                                    {{ $tipo->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Compra</label>
                                        <input type="date" name="fecha_compra" id="fecha_compra" value="{{ date('Y-m-d') }}" 
                                               @change="calcularVencimiento()"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Vencimiento</label>
                                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Precio Total</label>
                                        <input type="number" step="0.01" name="precio_total" id="precio_total" required readonly
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Saldo Pendiente</label>
                                        <input type="number" step="0.01" name="saldo_pendiente" id="saldo_pendiente" required readonly
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4 visitas-fields" style="display: none;">
                                        <label class="block text-sm font-medium text-gray-700">Número de Visitas</label>
                                        <input type="number" name="visitas_permitidas" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="renovacion" value="1" checked
                                                   class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <span class="ml-2 text-sm text-gray-600">¿Es Renovable?</span>
                                        </label>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" 
                                                @click="toggleModal()"
                                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                                            Crear Membresía
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Editar Membresía -->
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
                                    Editar Membresía
                                </h2>

                                <form x-bind:action="'/membresias/' + currentMembresia?.id" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Cliente</label>
                                            <select id="edit_id_usuario" name="id_usuario" 
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                @foreach($usuarios as $usuario)
                                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Tipo de Membresía</label>
                                            <select id="edit_tipo_membresia" 
                                                    name="id_tipo_membresia" 
                                                    @change="toggleEditVisitasFields($event.target.value)"
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                @foreach($tiposMembresia as $tipo)
                                                    <option value="{{ $tipo->id_tipo_membresia }}">{{ $tipo->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Fecha de Compra</label>
                                            <input type="date" id="edit_fecha_compra" name="fecha_compra"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Fecha de Vencimiento</label>
                                            <input type="date" id="edit_fecha_vencimiento" name="fecha_vencimiento"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Precio Total</label>
                                            <input type="number" step="0.01" id="edit_precio_total" name="precio_total"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Saldo Pendiente</label>
                                            <input type="number" step="0.01" id="edit_saldo_pendiente" name="saldo_pendiente"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div class="edit-visitas-fields" style="display: none;">
                                            <label class="block text-sm font-medium text-emerald-700">Número de Visitas</label>
                                            <input type="number" id="edit_visitas_permitidas" name="visitas_permitidas"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="flex items-center">
                                                <input type="checkbox" id="edit_renovacion" name="renovacion" value="1"
                                                       class="rounded border-emerald-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <span class="ml-2 text-sm text-emerald-700">¿Es Renovable?</span>
                                            </label>
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
                                            Actualizar Membresía
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoMembresia = document.getElementById('tipo_membresia');
            const visitasFields = document.querySelector('.visitas-fields');
            const fechaVencimiento = document.getElementById('fecha_vencimiento');
            const precioTotal = document.getElementById('precio_total');
            const saldoPendiente = document.getElementById('saldo_pendiente');

            function calcularVencimiento() {
                const fechaCompra = new Date(document.getElementById('fecha_compra').value);
                const tipoSeleccionado = tipoMembresia.options[tipoMembresia.selectedIndex];
                const duracion = parseInt(tipoSeleccionado.dataset.duracion);
                const precio = parseFloat(tipoSeleccionado.dataset.precio);
                const tipo = tipoSeleccionado.dataset.tipo;

                // Calcular precio y saldo pendiente
                precioTotal.value = precio;
                saldoPendiente.value = precio;

                // Calcular fecha de vencimiento solo si no es por visitas
                if (tipo !== 'por_visitas') {
                    const fechaVenc = new Date(fechaCompra);
                    fechaVenc.setDate(fechaVenc.getDate() + duracion);
                    fechaVencimiento.value = fechaVenc.toISOString().split('T')[0];
                    fechaVencimiento.disabled = false;
                } else {
                    fechaVencimiento.value = '';
                    fechaVencimiento.disabled = true;
                }
            }

            tipoMembresia.addEventListener('change', function() {
                visitasFields.style.display = this.options[this.selectedIndex].dataset.tipo === 'por_visitas' ? 'block' : 'none';
                calcularVencimiento();
            });

            // Calcular valores iniciales
            calcularVencimiento();
        });
    </script>
</x-app-layout> 