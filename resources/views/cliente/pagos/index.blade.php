<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Pagos de Membresía</h2>
                        <button @click="$dispatch('open-modal', 'nuevo-pago')" 
                                class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                            Registrar Pago
                        </button>
                    </div>

                    <!-- Lista de Pagos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Membresía
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monto
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Método
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pagos as $pago)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pago->fecha_pago->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $pago->membresia->tipoMembresia->nombre ?? 'No disponible' }}
                                            <div class="text-xs text-gray-500">
                                                Duración: {{ $pago->membresia->duracion_dias > 0 ? $pago->membresia->duracion_dias : '30' }} días
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${{ number_format($pago->monto, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center space-x-2">
                                                @switch($pago->id_metodo_pago)
                                                    @case(1)
                                                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                        </svg>
                                                        <span>Tarjeta de Crédito/Débito</span>
                                                        @break

                                                    @case(2)
                                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                        </svg>
                                                        <span>Efectivo</span>
                                                        @break

                                                    @case(3)
                                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                                        </svg>
                                                        <span>Transferencia Bancaria</span>
                                                        @break

                                                    @default
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span>{{ $pago->metodoPago->nombre }}</span>
                                                @endswitch
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $pago->estado === 'aprobado' ? 'bg-green-100 text-green-800' : 
                                                   ($pago->estado === 'rechazado' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($pago->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <button type="button" 
                                                    onclick="cargarDetallesPago({{ $pago->id_pago }})"
                                                    class="text-indigo-600 hover:text-indigo-900 underline bg-transparent border-none cursor-pointer p-0 text-left">
                                                Ver detalles
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No hay pagos registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Nuevo Pago -->
    <x-modal name="nuevo-pago" :show="false" persistent max-width="md">
        <div class="modal-container" 
             x-data="{ 
                previewUrl: null,
                fileName: '',
                fileSize: '',
                showPreview: false,
                
                previewFile(event) {
                    const file = event.target.files[0];
                    
                    if (!file) {
                        this.clearPreview();
                        return;
                    }
                    
                    // Validar tamaño (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('El archivo es demasiado grande. Máximo 5MB permitido.');
                        event.target.value = '';
                        this.clearPreview();
                        return;
                    }
                    
                    // Validar tipo
                    if (!file.type.match('image.*') && file.type !== 'application/pdf') {
                        alert('Solo se permiten archivos de imagen o PDF.');
                        event.target.value = '';
                        this.clearPreview();
                        return;
                    }
                    
                    // Mostrar información del archivo
                    this.fileName = file.name;
                    this.fileSize = (file.size / 1024 / 1024).toFixed(2);
                    
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previewUrl = e.target.result;
                            this.showPreview = true;
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        this.previewUrl = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzODQgNTEyIj48cGF0aCBmaWxsPSIjZWQ0YzVjIiBkPSJNMTgxLjkgMjU2LjFjLTUtMTYtNC45LTQ2LjktMi0xNjguOUMxODIuMSA0MS44IDE4MiAzMi4xIDE4MiAzMmMwLTEzLjMtMTAuOC0yNC0yNC0yNGgtODIuM2MtMTMuMiAwLTI0IDEwLjctMjQgMjQgMCAwIC4xIDkuOCAzLjIgOTAuMSAyLjkgMTIyLjkgMyAxNTMuOC0yIDE2OC45LTEyLjYgMzcuOS0yNC45IDQ1LjQtMzUuNCA1Ny4xLTUuMiA1LjYtNS42IDE0LjYtLjkgMjAuOSA1IDYuNyAxMy44IDcuOSAyMC4zIDMuNiA2LjUtNC4zIDEyLjktOC45IDE5LjMtMTMuNSAxMy0xMC4yIDI3LjItMjAuNSA0MC45LTI3LjIgMzkuMy0xOS4xIDkzLjYtMTEuOCAxMzYuMyAxNC45IDkuOSA2LjIgMTQuOC0xLjYgMTMuNi0xMS45LS40LTMuOC0xLjgtMTYuNy0zLjgtMzIuOC0zLjEtMjYuMy03LjctNTYuOS0xNS4xLTc0LjJtMTM4LjggMTY1LjljNS41IDUuNSAxNC42IDUuNSAyMC4yIDBsNDEuOS00MS45YzUuNS01LjUgNS41LTE0LjYgMC0yMC4yTDIxNy45IDIwMy41Yy01LjUtNS41LTE0LjYtNS41LTIwLjIgMGwtNDEuOSA0MS45Yy01LjUgNS41LTUuNSAxNC42IDAgMjAuMmwxNjQuOSAxNjUuNHoiLz48L3N2Zz4=';
                        this.showPreview = true;
                    }
                },
                
                clearPreview() {
                    this.previewUrl = null;
                    this.fileName = '';
                    this.fileSize = '';
                    this.showPreview = false;
                    $refs.comprobante.value = '';
                }
             }"
             onclick="event.stopPropagation()">
            
            <form id="formPago" method="POST" action="{{ route('cliente.pagos.store') }}" 
                  enctype="multipart/form-data" class="p-6" onclick="event.stopPropagation()">
                @csrf

                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Registrar Nuevo Pago
                </h2>

                <!-- Membresía -->
                <div class="mb-4">
                    <x-input-label for="id_membresia" value="Membresía" />
                    <select id="id_membresia" name="id_membresia" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Selecciona una membresía</option>
                        @foreach($membresias as $membresia)
                            <option value="{{ $membresia->id_membresia }}" 
                                    data-precio="{{ $membresia->precio_total }}">
                                {{ $membresia->tipoMembresia->nombre ?? 'Membresía' }} - 
                                ${{ number_format($membresia->precio_total, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <div id="membresia-error" class="text-red-500 text-sm mt-1"></div>
                </div>

                <!-- Monto -->
                <div class="mb-4">
                    <x-input-label for="monto" value="Monto" />
                    <x-text-input id="monto" name="monto" type="number" step="0.01" required class="mt-1 block w-full" />
                </div>

                <!-- Método de Pago -->
                <div class="mb-4">
                    <x-input-label for="id_metodo_pago" value="Método de Pago" />
                    <select id="id_metodo_pago" name="id_metodo_pago" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Selecciona un método de pago</option>
                        <option value="2">Efectivo</option>
                        <option value="3">Transferencia Bancaria</option>
                        <option value="1">Tarjeta de Crédito/Débito</option>
                    </select>
                </div>

                <!-- Comprobante de Pago -->
                <div class="mb-4">
                    <x-input-label for="comprobante" value="Comprobante de Pago" />
                    <div class="mt-2">
                        <!-- Vista previa -->
                        <div x-show="showPreview" class="mb-4">
                            <div class="bg-white p-3 rounded-lg border border-emerald-200 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-emerald-700">Vista previa</span>
                                    <button type="button" @click="clearPreview" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex justify-center">
                                    <img :src="previewUrl" alt="Vista previa" class="max-h-48 object-contain rounded"/>
                                </div>
                                <div class="mt-2 text-sm text-center text-emerald-600">
                                    <span x-text="fileName"></span>
                                    <span x-show="fileSize">(<span x-text="fileSize"></span> MB)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Área de carga -->
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-emerald-300 border-dashed rounded-lg cursor-pointer bg-emerald-50 hover:bg-emerald-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mb-2 text-sm text-emerald-600">
                                    <span class="font-semibold">Click para subir comprobante</span>
                                </p>
                                <p class="text-xs text-emerald-500">PNG, JPG o PDF (Máximo 5MB)</p>
                            </div>
                            <input id="comprobante" 
                                   name="comprobante" 
                                   type="file" 
                                   x-ref="comprobante"
                                   @change="previewFile($event)"
                                   class="hidden"
                                   accept="image/*,.pdf" />
                        </label>
                    </div>
                </div>

                <!-- Notas -->
                <div class="mb-4">
                    <x-input-label for="notas" value="Notas Adicionales" />
                    <textarea id="notas" name="notas" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" 
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors mr-3"
                            x-on:click="$dispatch('close-modal', 'nuevo-pago')">
                        Cancelar
                    </button>

                    <button type="submit" 
                            class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                        Registrar Pago
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Reemplazar el modal de detalles con esta versión más simple -->
    <div id="modal-detalles" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Detalles del Pago</h2>
                    <button onclick="cerrarModalDetalles()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="loading-detalles" class="flex justify-center items-center py-10">
                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-emerald-600">Cargando detalles...</span>
                </div>
                
                <div id="contenido-detalles" class="hidden">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-500">Membresía</p>
                            <p id="detalle-membresia" class="font-medium">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Fecha</p>
                            <p id="detalle-fecha" class="font-medium">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Monto</p>
                            <p id="detalle-monto" class="font-medium">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Estado</p>
                            <p id="detalle-estado" class="inline-flex px-2 text-xs font-semibold rounded-full">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Método de Pago</p>
                            <p id="detalle-metodo" class="font-medium">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Duración</p>
                            <p id="detalle-duracion" class="font-medium">-</p>
                        </div>
                    </div>

                    <div id="detalle-comprobante-container" class="mb-4 hidden">
                        <p class="text-sm text-gray-500 mb-2">Comprobante</p>
                        <div class="bg-gray-100 p-4 rounded">
                            <a id="detalle-comprobante-link" href="#" target="_blank" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver comprobante
                            </a>
                        </div>
                    </div>

                    <div id="detalle-notas-container" class="mb-4 hidden">
                        <p class="text-sm text-gray-500 mb-1">Notas</p>
                        <p id="detalle-notas" class="text-gray-700">-</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" 
                            onclick="cerrarModalDetalles()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function cargarDetallesPago(id) {
            // Mostrar el modal
            document.getElementById('modal-detalles').classList.remove('hidden');
            
            // Mostrar loading, ocultar contenido
            document.getElementById('loading-detalles').classList.remove('hidden');
            document.getElementById('contenido-detalles').classList.add('hidden');
            
            // Cargar los datos
            fetch(`/cliente/pagos/${id}/info`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos cargados:', data);
                    mostrarDetallesPago(data);
                })
                .catch(error => {
                    console.error('Error al cargar detalles:', error);
                    alert('No se pudieron cargar los detalles del pago');
                    cerrarModalDetalles();
                });
            
            // Evitar que la página se desplace
            return false;
        }
        
        function mostrarDetallesPago(pago) {
            // Ocultar loading, mostrar contenido
            document.getElementById('loading-detalles').classList.add('hidden');
            document.getElementById('contenido-detalles').classList.remove('hidden');
            
            // Llenar los datos
            document.getElementById('detalle-membresia').textContent = 
                pago.membresia?.tipoMembresia?.nombre || 'No disponible';
            
            document.getElementById('detalle-fecha').textContent = 
                formatDate(pago.fecha_pago);
            
            document.getElementById('detalle-monto').textContent = 
                '$' + formatNumber(pago.monto);
            
            const estadoElement = document.getElementById('detalle-estado');
            estadoElement.textContent = capitalizeFirst(pago.estado);
            estadoElement.className = 'inline-flex px-2 text-xs font-semibold rounded-full ' + 
                getStatusClass(pago.estado);
            
            document.getElementById('detalle-metodo').textContent = 
                getPaymentMethod(pago.id_metodo_pago);
            
            document.getElementById('detalle-duracion').textContent = 
                getDuration(pago);
            
            // Comprobante
            if (pago.comprobante_url) {
                document.getElementById('detalle-comprobante-container').classList.remove('hidden');
                document.getElementById('detalle-comprobante-link').href = '/storage/' + pago.comprobante_url;
            } else {
                document.getElementById('detalle-comprobante-container').classList.add('hidden');
            }
            
            // Notas
            if (pago.notas) {
                document.getElementById('detalle-notas-container').classList.remove('hidden');
                document.getElementById('detalle-notas').textContent = pago.notas;
            } else {
                document.getElementById('detalle-notas-container').classList.add('hidden');
            }
        }
        
        function cerrarModalDetalles() {
            document.getElementById('modal-detalles').classList.add('hidden');
        }
        
        // Funciones auxiliares
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES');
        }
        
        function formatNumber(number) {
            if (number === null || number === undefined) return '-';
            return parseFloat(number).toFixed(2);
        }
        
        function capitalizeFirst(str) {
            if (!str) return '-';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
        
        function getStatusClass(status) {
            switch(status) {
                case 'aprobado': return 'bg-green-100 text-green-800';
                case 'rechazado': return 'bg-red-100 text-red-800';
                default: return 'bg-yellow-100 text-yellow-800';
            }
        }
        
        function getPaymentMethod(id) {
            switch(parseInt(id)) {
                case 1: return 'Tarjeta de Crédito/Débito';
                case 2: return 'Efectivo';
                case 3: return 'Transferencia Bancaria';
                default: return 'Otro';
            }
        }
        
        function getDuration(payment) {
            if (!payment?.membresia) return '-';
            const days = payment.membresia.duracion_dias > 0 
                ? payment.membresia.duracion_dias 
                : '30';
            return `${days} días`;
        }
        
        // Cerrar el modal al hacer clic fuera de él
        document.getElementById('modal-detalles').addEventListener('click', function(event) {
            if (event.target === this) {
                cerrarModalDetalles();
            }
        });
    </script>
    @endpush
</x-cliente-layout> 