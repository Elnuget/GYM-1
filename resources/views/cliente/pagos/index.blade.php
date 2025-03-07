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
                                            {{ $pago->membresia->nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${{ number_format($pago->monto, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pago->metodoPago->nombre }}
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
                                            <a href="{{ route('cliente.pagos.show', $pago) }}" 
                                               class="text-indigo-600 hover:text-indigo-900">Ver detalles</a>
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
                        <option value="1">Efectivo</option>
                        <option value="2">Transferencia Bancaria</option>
                        <option value="3">Tarjeta de Crédito/Débito</option>
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
                            x-on:click="$dispatch('close')">
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

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pagoData', () => ({
                isModalOpen: false,
                isEditModalOpen: false,
                currentPago: null,
                filePreview: null,
                fileName: '',
                fileSize: '',
                showPreview: false,

                // Función para previsualizar archivo
                previewFile(event) {
                    const file = event.target.files[0];
                    
                    if (!file) {
                        this.clearFilePreview();
                        return;
                    }
                    
                    // Validar tamaño (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('El archivo es demasiado grande. Máximo 5MB permitido.');
                        event.target.value = '';
                        this.clearFilePreview();
                        return;
                    }
                    
                    // Validar tipo
                    if (!file.type.match('image.*') && file.type !== 'application/pdf') {
                        alert('Solo se permiten archivos de imagen o PDF.');
                        event.target.value = '';
                        this.clearFilePreview();
                        return;
                    }
                    
                    // Mostrar información del archivo
                    this.fileName = file.name;
                    this.fileSize = (file.size / 1024 / 1024).toFixed(2);
                    
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.filePreview = e.target.result;
                            this.showPreview = true;
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        this.filePreview = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzODQgNTEyIj48cGF0aCBmaWxsPSIjZWQ0YzVjIiBkPSJNMTgxLjkgMjU2LjFjLTUtMTYtNC45LTQ2LjktMi0xNjguOUMxODIuMSA0MS44IDE4MiAzMi4xIDE4MiAzMmMwLTEzLjMtMTAuOC0yNC0yNC0yNGgtODIuM2MtMTMuMiAwLTI0IDEwLjctMjQgMjQgMCAwIC4xIDkuOCAzLjIgOTAuMSAyLjkgMTIyLjkgMyAxNTMuOC0yIDE2OC45LTEyLjYgMzcuOS0yNC45IDQ1LjQtMzUuNCA1Ny4xLTUuMiA1LjYtNS42IDE0LjYtLjkgMjAuOSA1IDYuNyAxMy44IDcuOSAyMC4zIDMuNiA2LjUtNC4zIDEyLjktOC45IDE5LjMtMTMuNSAxMy0xMC4yIDI3LjItMjAuNSA0MC45LTI3LjIgMzkuMy0xOS4xIDkzLjYtMTEuOCAxMzYuMyAxNC45IDkuOSA2LjIgMTQuOC0xLjYgMTMuNi0xMS45LS40LTMuOC0xLjgtMTYuNy0zLjgtMzIuOC0zLjEtMjYuMy03LjctNTYuOS0xNS4xLTc0LjJtMTM4LjggMTY1LjljNS41IDUuNSAxNC42IDUuNSAyMC4yIDBsNDEuOS00MS45YzUuNS01LjUgNS41LTE0LjYgMC0yMC4yTDIxNy45IDIwMy41Yy01LjUtNS41LTE0LjYtNS41LTIwLjIgMGwtNDEuOSA0MS45Yy01LjUgNS41LTUuNSAxNC42IDAgMjAuMmwxNjQuOSAxNjUuNHoiLz48L3N2Zz4=';
                        this.showPreview = true;
                    }
                },

                clearFilePreview() {
                    this.filePreview = null;
                    this.fileName = '';
                    this.fileSize = '';
                    this.showPreview = false;
                    this.$refs.comprobante.value = '';
                },

                toggleModal() {
                    this.isModalOpen = !this.isModalOpen;
                    if (!this.isModalOpen) {
                        this.clearFilePreview();
                    }
                },

                toggleEditModal(pago = null) {
                    this.isEditModalOpen = !this.isEditModalOpen;
                    this.currentPago = pago;
                    if (pago) {
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
    @endpush
</x-cliente-layout> 