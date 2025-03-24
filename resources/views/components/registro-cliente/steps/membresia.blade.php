{{-- Paso 1: Componente de Membresía --}}
<div x-show="step === 1" style="display: none;" x-data="{
    precioTotal: 0,
    nombreTipo: '',
    showVisitasFields: false,
    
    calcularVencimiento() {
        const selectTipo = document.getElementById('id_tipo_membresia');
        const fechaCompra = document.getElementById('fecha_compra').value;
        const fechaVencimiento = document.getElementById('fecha_vencimiento');
        
        if (selectTipo && fechaCompra) {
            const option = selectTipo.options[selectTipo.selectedIndex];
            
            if (option) {
                // Obtener el precio desde el atributo data
                const precio = parseFloat(option.dataset.precio || 0).toFixed(2);
                
                // Actualizar los campos de precio y saldo pendiente
                this.precioTotal = precio;
                document.getElementById('precio_total').value = precio;
                document.getElementById('saldo_pendiente').value = precio;
                
                const duracion = option.dataset.duracion || 0;
                this.nombreTipo = option.textContent || '';
                
                const tieneVisitas = parseInt(option.dataset.visitas) > 0;
                const nombreContieneVisita = this.nombreTipo.toLowerCase().includes('visita');
                this.showVisitasFields = tieneVisitas || nombreContieneVisita;
                
                if (this.showVisitasFields && option.dataset.visitas) {
                    document.getElementById('visitas_permitidas').value = option.dataset.visitas;
                }
                
                // Calcular fecha de vencimiento
                if (fechaVencimiento && fechaCompra) {
                    if (this.showVisitasFields) {
                        // Si es membresía por visitas, mantener la fecha actual
                        fechaVencimiento.value = new Date().toISOString().split('T')[0];
                    } else if (duracion) {
                        // Para otros tipos de membresía, calcular según la duración
                        const fecha = new Date(fechaCompra);
                        fecha.setDate(fecha.getDate() + parseInt(duracion));
                        fechaVencimiento.value = fecha.toISOString().split('T')[0];
                    }
                }
            }
        }
    },
    
    guardarMembresia() {
        // Validar campos obligatorios
        let camposFaltantes = [];
        
        if (!document.getElementById('id_tipo_membresia')?.value) camposFaltantes.push('Tipo de Membresía');
        if (!document.getElementById('precio_total')?.value) camposFaltantes.push('Precio Total');
        if (!document.getElementById('fecha_compra')?.value) camposFaltantes.push('Fecha de Compra');
        if (!document.getElementById('fecha_vencimiento')?.value) camposFaltantes.push('Fecha de Vencimiento');
        
        if (this.showVisitasFields && !document.getElementById('visitas_permitidas')?.value) {
            camposFaltantes.push('Visitas Permitidas');
        }
        
        if (camposFaltantes.length > 0) {
            const errorMessage = 'Por favor, complete los campos obligatorios: ' + camposFaltantes.join(', ');
            this.$dispatch('mostrar-error', { mensaje: errorMessage });
            return;
        }
        
        // Mostrar indicador de procesamiento
        this.$dispatch('mostrar-success', { mensaje: 'Guardando membresía...' });
        
        // Crear FormData con los campos del paso 1
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('step', 1);
        
        // Añadir todos los campos del formulario
        formData.append('id_tipo_membresia', document.getElementById('id_tipo_membresia').value);
        formData.append('precio_total', document.getElementById('precio_total').value);
        formData.append('fecha_compra', document.getElementById('fecha_compra').value);
        formData.append('fecha_vencimiento', document.getElementById('fecha_vencimiento').value);
        formData.append('saldo_pendiente', document.getElementById('saldo_pendiente').value);
        
        if (this.showVisitasFields) {
            formData.append('visitas_permitidas', document.getElementById('visitas_permitidas').value);
        }
        
        formData.append('renovacion', document.getElementById('renovacion').checked ? '1' : '0');
        
        // Enviar los datos
        fetch('{{ route('guardar.paso.cliente') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.$dispatch('mostrar-success', { 
                    mensaje: data.message || 'Membresía guardada exitosamente' 
                });
                
                // Esperar un momento para mostrar el mensaje de éxito antes de continuar
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        // Si no hay redirección específica, avanzar al siguiente paso
                        this.step = 2;
                    }
                }, 1500);
            } else {
                this.$dispatch('mostrar-error', { 
                    mensaje: data.message || 'Error al guardar la membresía' 
                });
                console.error('Error en la respuesta:', data);
            }
        })
        .catch(error => {
            this.$dispatch('mostrar-error', { 
                mensaje: 'Error de conexión al guardar la membresía' 
            });
            console.error('Error en la solicitud:', error);
        });
    }
}">
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Membresía</h2>
    
    <div x-show="!tieneMembresiaActiva" class="grid grid-cols-1 gap-6">
        <!-- Contenido del paso de membresía -->
        <input type="hidden" name="id_usuario" value="{{ auth()->id() }}">
        <input type="hidden" id="saldo_pendiente" name="saldo_pendiente" value="0">
        
        @php
            // Obtener el cliente actual y su gimnasio
            $cliente = auth()->user()->cliente;
            $gimnasioId = $cliente ? $cliente->gimnasio_id : null;
            
            // Filtrar tipos de membresía por el gimnasio del cliente
            $tiposMembresia = \App\Models\TipoMembresia::where('gimnasio_id', $gimnasioId)
                ->where('estado', true)
                ->get();
        @endphp
        
        <div>
            <x-input-label for="id_tipo_membresia" :value="__('Tipo de Membresía')" class="mb-1 text-sm font-medium text-gray-700" />
            <select id="id_tipo_membresia" name="id_tipo_membresia" required @change="calcularVencimiento" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                <option value="" selected disabled>Selecciona un tipo</option>
                @foreach($tiposMembresia as $tipo)
                    <option value="{{ $tipo->id_tipo_membresia }}" 
                            data-precio="{{ $tipo->precio }}"
                            data-duracion="{{ $tipo->duracion_dias }}"
                            data-visitas="{{ $tipo->numero_visitas }}"
                            {{ old('id_tipo_membresia') == $tipo->id_tipo_membresia ? 'selected' : '' }}>
                        {{ $tipo->nombre }} - ${{ number_format($tipo->precio, 2) }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('id_tipo_membresia')" class="mt-1" />
        </div>
        
        <!-- Precio Total -->
        <div>
            <x-input-label for="precio_total" :value="__('Precio Total')" class="mb-1 text-sm font-medium text-gray-700" />
            <x-text-input id="precio_total" class="block w-full bg-gray-100" type="number" step="0.01" name="precio_total" value="0" readonly />
            <x-input-error :messages="$errors->get('precio_total')" class="mt-1" />
        </div>
        
        <!-- Fecha de Compra -->
        <div>
            <x-input-label for="fecha_compra" :value="__('Fecha de Compra')" class="mb-1 text-sm font-medium text-gray-700" />
            <x-text-input id="fecha_compra" class="block w-full" type="date" name="fecha_compra" :value="old('fecha_compra', date('Y-m-d'))" required @change="calcularVencimiento" />
            <x-input-error :messages="$errors->get('fecha_compra')" class="mt-1" />
        </div>
        
        <!-- Fecha de Vencimiento -->
        <div>
            <x-input-label for="fecha_vencimiento" :value="__('Fecha de Vencimiento')" class="mb-1 text-sm font-medium text-gray-700" />
            <x-text-input id="fecha_vencimiento" class="block w-full bg-gray-100" type="date" name="fecha_vencimiento" :value="old('fecha_vencimiento')" required readonly />
            <x-input-error :messages="$errors->get('fecha_vencimiento')" class="mt-1" />
        </div>
        
        <!-- Visitas Permitidas - Solo visible para membresías por visitas -->
        <div x-show="showVisitasFields">
            <x-input-label for="visitas_permitidas" :value="__('Visitas Permitidas')" class="mb-1 text-sm font-medium text-gray-700" />
            <x-text-input id="visitas_permitidas" class="block w-full" type="number" name="visitas_permitidas" :value="old('visitas_permitidas')" min="1" />
            <x-input-error :messages="$errors->get('visitas_permitidas')" class="mt-1" />
        </div>
        
        <!-- Renovación -->
        <div>
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="renovacion" name="renovacion" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" {{ old('renovacion') ? 'checked' : '' }} checked>
                </div>
                <div class="ml-3 text-sm">
                    <label for="renovacion" class="font-medium text-gray-700">Renovación</label>
                    <p class="text-gray-500">Marcar si es una renovación de membresía</p>
                </div>
            </div>
            <x-input-error :messages="$errors->get('renovacion')" class="mt-1" />
        </div>
    </div>
    
    <div x-show="tieneMembresiaActiva" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">Ya tienes una membresía activa</h3>
                <div class="mt-2 text-sm text-gray-600">
                    <p>Este paso está completo. Puedes continuar con el proceso de registro.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex justify-end mt-8">
        <button type="button" @click="guardarMembresia()" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
            Guardar y Continuar
        </button>
    </div>
</div> 