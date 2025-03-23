{{-- Paso 2: Componente de Pago --}}
<div x-show="step === 2" style="display: none;" x-data="{
    montoPendiente: '',
    membresiaInfo: null,
    id_membresia: null,
    showSuccessModal: false,
    showErrorModal: false,
    modalMessage: '',
    errorMessage: '',
    fileMessage: '',
    
    init() {
        // Obtener información de la membresía más reciente
        this.cargarDatosMembresiaReciente();
    },
    
    cargarDatosMembresiaReciente() {
        // Obtener información de la membresía más reciente
        fetch('{{ route('api.membresia.reciente') }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar la información de la membresía
                this.membresiaInfo = data.membresia;
                this.id_membresia = data.membresia.id_membresia;
                
                // Actualizar el monto pendiente
                this.montoPendiente = data.membresia.saldo_pendiente || data.membresia.precio_total || '0';
                
                // Actualizar el campo de monto de pago
                if (document.getElementById('monto_pago')) {
                    document.getElementById('monto_pago').value = this.montoPendiente;
                }
                
                // Actualizar el campo oculto de saldo pendiente
                if (document.getElementById('saldo_pendiente')) {
                    document.getElementById('saldo_pendiente').value = this.montoPendiente;
                }
                
                console.log('ID de membresia cargado:', this.id_membresia);
            } else {
                // Si no hay membresía, intentar cargar desde el input existente
                let saldoElem = document.getElementById('saldo_pendiente');
                this.montoPendiente = saldoElem ? saldoElem.value : (document.getElementById('precio_total')?.value || '0');
                
                if (document.getElementById('monto_pago')) {
                    document.getElementById('monto_pago').value = this.montoPendiente;
                }
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos de la membresía:', error);
            
            // En caso de error, intentar cargar desde el input existente
            let saldoElem = document.getElementById('saldo_pendiente');
            this.montoPendiente = saldoElem ? saldoElem.value : (document.getElementById('precio_total')?.value || '0');
            
            if (document.getElementById('monto_pago')) {
                document.getElementById('monto_pago').value = this.montoPendiente;
            }
        });
    },
    
    validateFile(event) {
        const file = event.target.files[0];
        if (!file) {
            this.fileMessage = '';
            return;
        }
        
        // Validar tipo de archivo
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            this.fileMessage = 'Tipo de archivo no válido. Solo se permiten imágenes (JPG, PNG, GIF) y PDF.';
            event.target.value = '';
            return;
        }
        
        // Validar tamaño (5MB)
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            this.fileMessage = 'El archivo es demasiado grande. El tamaño máximo permitido es 5MB.';
            event.target.value = '';
            return;
        }
        
        this.fileMessage = `Archivo seleccionado: ${file.name}`;
    },
    
    guardarPago() {
        console.log('Iniciando proceso de guardado de pago...');
        
        // Validar campos obligatorios
        let camposFaltantes = [];
        
        const montoInput = document.getElementById('monto_pago');
        const metodoInput = document.getElementById('id_metodo_pago');
        const fechaInput = document.getElementById('fecha_pago');
        const idMembresiaInput = document.getElementById('id_membresia');
        const comprobanteInput = document.getElementById('comprobante');
        
        console.log('Valores de campos antes de la validación:');
        console.log('- Monto:', montoInput?.value);
        console.log('- Método:', metodoInput?.value);
        console.log('- Fecha:', fechaInput?.value);
        console.log('- ID Membresía:', idMembresiaInput?.value);
        console.log('- Comprobante:', comprobanteInput?.files[0]?.name);
        
        if (!montoInput?.value?.trim()) camposFaltantes.push('Monto del Pago');
        if (!metodoInput?.value) camposFaltantes.push('Método de Pago');
        if (!fechaInput?.value?.trim()) camposFaltantes.push('Fecha de Pago');
        if (!idMembresiaInput?.value) camposFaltantes.push('ID de Membresía');
        
        if (camposFaltantes.length > 0) {
            const errorMsg = 'Por favor, complete los campos obligatorios: ' + camposFaltantes.join(', ');
            console.error('Validación fallida:', errorMsg);
            this.$dispatch('mostrar-error', { mensaje: errorMsg });
            return;
        }
        
        // Crear FormData con los campos del pago
        const pagoFormData = new FormData();
        
        // Añadir campos básicos
        pagoFormData.append('_token', '{{ csrf_token() }}');
        pagoFormData.append('step', 2);
        
        // Añadir campos del formulario con validación adicional
        const monto = montoInput.value.trim();
        pagoFormData.append('monto_pago', monto);
        console.log('Monto del pago añadido:', monto);
        
        const metodo = metodoInput.value;
        pagoFormData.append('id_metodo_pago', metodo);
        console.log('Método de pago añadido:', metodo);
        
        const fecha = fechaInput.value.trim();
        pagoFormData.append('fecha_pago', fecha);
        console.log('Fecha de pago añadida:', fecha);
        
        const idMembresia = idMembresiaInput.value;
        pagoFormData.append('id_membresia', idMembresia);
        console.log('ID de membresía añadido:', idMembresia);
        
        pagoFormData.append('estado', 'pendiente');
        
        // Añadir campos opcionales
        const notasInput = document.getElementById('notas');
        if (notasInput?.value) {
            pagoFormData.append('notas', notasInput.value);
            console.log('Notas añadidas');
        }
        
        // Manejo del comprobante
        if (comprobanteInput?.files[0]) {
            const file = comprobanteInput.files[0];
            // Validar el tipo de archivo
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                this.$dispatch('mostrar-error', { 
                    mensaje: 'El tipo de archivo no es válido. Solo se permiten imágenes (JPG, PNG, GIF) y PDF.' 
                });
                return;
            }
            
            // Validar el tamaño del archivo (5MB máximo)
            const maxSize = 5 * 1024 * 1024; // 5MB en bytes
            if (file.size > maxSize) {
                this.$dispatch('mostrar-error', { 
                    mensaje: 'El archivo es demasiado grande. El tamaño máximo permitido es 5MB.' 
                });
                return;
            }
            
            pagoFormData.append('comprobante', file);
            console.log('Comprobante añadido:', file.name);
        }
        
        // Mostrar mensaje de procesamiento
        this.$dispatch('mostrar-success', { mensaje: 'Procesando pago...' });
        console.log('Enviando datos al servidor...');
        
        // Enviar la información del pago
        fetch('{{ route('guardar.paso.cliente') }}', {
            method: 'POST',
            body: pagoFormData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta del servidor recibida');
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Error al parsear respuesta:', text);
                    throw new Error('Respuesta del servidor no válida');
                }
            });
        })
        .then(data => {
            console.log('Respuesta procesada:', data);
            
            if (data.success) {
                this.$dispatch('mostrar-success', { 
                    mensaje: data.message || 'Pago registrado exitosamente'
                });
                
                console.log('Pago guardado exitosamente, redirigiendo...');
                
                // Esperar un momento para mostrar el mensaje de éxito antes de redireccionar
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }, 1500);
            } else {
                const errorMsg = data.message || 'Error al guardar el pago';
                console.error('Error en la respuesta:', data);
                this.$dispatch('mostrar-error', { mensaje: errorMsg });
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            this.$dispatch('mostrar-error', { 
                mensaje: 'Error de conexión al guardar el pago. Por favor, inténtalo de nuevo.'
            });
        });
    }
}">
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Pago de Membresía</h2>
    
    <div x-show="tieneMembresiaConPagoPendiente" class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Tienes una membresía pendiente de pago</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Se ha detectado que tienes una membresía sin pagos registrados. Por favor, completa el proceso de pago para continuar.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div x-show="tieneMembresiaActiva && !tieneMembresiaConPagoPendiente" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">Ya has completado el pago de tu membresía</h3>
                <div class="mt-2 text-sm text-gray-600">
                    <p>Este paso está completo. Puedes continuar con el proceso de registro.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div id="form-pago" x-show="!tieneMembresiaActiva || tieneMembresiaConPagoPendiente" class="grid grid-cols-1 gap-6">
        <!-- Campo oculto para estado -->
        <input type="hidden" name="estado" value="pendiente">
        
        <!-- Campo oculto para id_membresia -->
        <input type="hidden" id="id_membresia" name="id_membresia" x-bind:value="id_membresia">
        
        <!-- Información de la membresía -->
        <div x-show="membresiaInfo" class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-medium text-gray-700 mb-2">Detalles de la Membresía</h3>
            <p class="text-sm text-gray-600" x-text="'Tipo: ' + (membresiaInfo?.tipo_membresia?.nombre || 'No disponible')"></p>
            <p class="text-sm text-gray-600" x-text="'Fecha de Vencimiento: ' + (membresiaInfo?.fecha_vencimiento || 'No disponible')"></p>
            <p class="text-sm font-semibold text-gray-700 mt-1" x-text="'Monto a Pagar: $' + parseFloat(montoPendiente).toFixed(2)"></p>
        </div>
        
        <!-- Monto del Pago -->
        <div>
            <x-input-label for="monto_pago" :value="__('Monto del Pago *')" class="mb-1 text-sm font-medium text-gray-700" />
            <x-text-input id="monto_pago" class="block w-full" type="number" step="0.01" name="monto_pago" :value="old('monto_pago')" required x-model="montoPendiente" />
            <p class="mt-1 text-sm text-gray-500">Este es el monto pendiente de la membresía seleccionada.</p>
            <x-input-error :messages="$errors->get('monto_pago')" class="mt-1" />
        </div>

        <!-- Método de Pago -->
        <div>
            <x-input-label for="id_metodo_pago" :value="__('Método de Pago *')" class="mb-1 text-sm font-medium text-gray-700" />
            <select id="id_metodo_pago" name="id_metodo_pago" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                <option value="" selected disabled>Selecciona un método de pago</option>
                <option value="1">Efectivo</option>
                <option value="2">Tarjeta de Crédito/Débito</option>
                <option value="3">Transferencia Bancaria</option>
            </select>
            <x-input-error :messages="$errors->get('id_metodo_pago')" class="mt-1" />
        </div>

        <!-- Fecha de Pago -->
        <div>
            <x-input-label for="fecha_pago" :value="__('Fecha de Pago *')" class="mb-1 text-sm font-medium text-gray-700" />
            <x-text-input id="fecha_pago" class="block w-full" type="date" name="fecha_pago" :value="old('fecha_pago', date('Y-m-d'))" required />
            <x-input-error :messages="$errors->get('fecha_pago')" class="mt-1" />
        </div>
        
        <!-- Comprobante de Pago -->
        <div class="mb-4">
            <label for="comprobante" class="block text-sm font-medium text-gray-700">
                Comprobante de Pago
                <span class="text-xs text-gray-500">(Formatos permitidos: JPG, PNG, GIF, PDF. Máximo 5MB)</span>
            </label>
            <input type="file" 
                   id="comprobante" 
                   name="comprobante" 
                   accept="image/jpeg,image/png,image/gif,application/pdf"
                   @change="validateFile($event)"
                   class="mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 w-full">
            <p class="mt-1 text-xs text-gray-500" x-text="fileMessage || 'Sube una imagen o PDF de tu comprobante de pago.'"></p>
            <x-input-error :messages="$errors->get('comprobante')" class="mt-1" />
        </div>

        <!-- Observaciones -->
        <div>
            <x-input-label for="notas" :value="__('Observaciones')" class="mb-1 text-sm font-medium text-gray-700" />
            <textarea id="notas" name="notas" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Observaciones adicionales sobre el pago">{{ old('notas') }}</textarea>
            <x-input-error :messages="$errors->get('notas')" class="mt-1" />
        </div>
    </div>
    
    <div class="flex justify-between mt-8">
        <button type="button" @click="step = 1" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium">
            Anterior
        </button>
        
        <!-- Botón condicional según el estado del pago -->
        <template x-if="tieneMembresiaActiva && !tieneMembresiaConPagoPendiente">
            <button type="button" @click="step = 3" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
                Continuar
            </button>
        </template>
        <template x-if="!tieneMembresiaActiva || tieneMembresiaConPagoPendiente">
            <button type="button" @click="guardarPago()" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
                Guardar y Continuar
            </button>
        </template>
    </div>
</div> 