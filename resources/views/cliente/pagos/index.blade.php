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
    <x-modal name="nuevo-pago" focusable>
        <form method="POST" action="{{ route('cliente.pagos.store') }}" enctype="multipart/form-data" class="p-6">
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
                                data-precio="{{ $membresia->precio }}">
                            {{ $membresia->nombre }} - ${{ number_format($membresia->precio, 2) }}
                        </option>
                    @endforeach
                </select>
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
            <div id="comprobante-container" class="mb-4">
                <x-input-label for="comprobante" value="Comprobante de Pago" />
                <div class="mt-2">
                    <div class="flex items-center justify-center w-full">
                        <label for="comprobante" 
                               class="flex flex-col items-center justify-center w-full h-32 border-2 border-emerald-300 border-dashed rounded-lg cursor-pointer bg-emerald-50 hover:bg-emerald-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mb-2 text-sm text-emerald-600">
                                    <span class="font-semibold">Click para subir comprobante</span>
                                </p>
                                <p class="text-xs text-emerald-500">
                                    PNG, JPG o PDF (Máximo 5MB)
                                </p>
                            </div>
                            <input id="comprobante" 
                                   name="comprobante" 
                                   type="file" 
                                   class="hidden" 
                                   accept="image/*,.pdf" />
                        </label>
                    </div>
                    <!-- Preview de la imagen -->
                    <div id="image-preview" class="mt-4 hidden">
                        <img id="preview" src="" alt="Preview" class="max-w-xs mx-auto rounded-lg shadow-sm"/>
                    </div>
                    <div id="file-name" class="mt-2 text-sm text-center text-emerald-600"></div>
                </div>
            </div>

            <!-- Notas -->
            <div class="mb-4">
                <x-input-label for="notas" value="Notas Adicionales" />
                <textarea id="notas" name="notas" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    Registrar Pago
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const metodoPago = document.getElementById('id_metodo_pago');
            const comprobanteContainer = document.getElementById('comprobante-container');
            const comprobante = document.getElementById('comprobante');
            const membresiaSelect = document.getElementById('id_membresia');
            const montoInput = document.getElementById('monto');
            const fileNameDiv = document.getElementById('file-name');
            const imagePreview = document.getElementById('image-preview');
            const preview = document.getElementById('preview');

            // Mostrar/ocultar el contenedor de comprobante según el método de pago
            metodoPago.addEventListener('change', function() {
                const isTransferencia = this.value === '2';
                comprobanteContainer.style.display = isTransferencia ? 'block' : 'none';
                if (!isTransferencia) {
                    comprobante.value = '';
                    imagePreview.classList.add('hidden');
                    fileNameDiv.textContent = '';
                }
            });

            membresiaSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.dataset.precio) {
                    montoInput.value = selectedOption.dataset.precio;
                }
            });

            // Manejar la selección de archivo
            comprobante.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    fileNameDiv.textContent = `Archivo seleccionado: ${file.name}`;

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                        }
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.classList.add('hidden');
                    }
                }
            });
        });
    </script>
    @endpush
</x-cliente-layout> 