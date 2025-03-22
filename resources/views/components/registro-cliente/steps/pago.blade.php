{{-- Paso 2: Componente de Pago --}}
<div x-show="step === 2" style="display: none;" x-data="{
    montoPendiente: '',
    
    init() {
        // Se intenta obtener el valor pendiente desde el input 'saldo_pendiente'
        let saldoElem = document.getElementById('saldo_pendiente');
        this.montoPendiente = saldoElem ? saldoElem.value : (document.getElementById('precio_total')?.value || '0');
        if (document.getElementById('monto_pago')) {
            document.getElementById('monto_pago').value = this.montoPendiente;
        }
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
    
    <div x-show="!tieneMembresiaActiva || tieneMembresiaConPagoPendiente" class="grid grid-cols-1 gap-6">
        <!-- Campo oculto para estado -->
        <input type="hidden" name="estado" value="pendiente">
        
        <!-- Monto del Pago -->
        <div>
            <x-input-label for="monto_pago" :value="__('Monto del Pago *')" class="mb-1 text-sm font-medium text-gray-700" />
            <x-text-input id="monto_pago" class="block w-full" type="number" step="0.01" name="monto_pago" :value="old('monto_pago')" required x-model="montoPendiente" />
            <p class="mt-1 text-sm text-gray-500">Este es el monto pendiente de la membresía seleccionada.</p>
            <x-input-error :messages="$errors->get('monto_pago')" class="mt-1" />
        </div>

        <!-- Método de Pago -->
        <div>
            <x-input-label for="metodo_pago" :value="__('Método de Pago *')" class="mb-1 text-sm font-medium text-gray-700" />
            <select id="metodo_pago" name="id_metodo_pago" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
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
        <div>
            <x-input-label for="comprobante" :value="__('Comprobante de Pago (opcional)')" class="mb-1 text-sm font-medium text-gray-700" />
            <input type="file" 
                  id="comprobante" 
                  name="comprobante" 
                  class="mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" 
                  accept="image/*,.pdf">
            <p class="mt-1 text-sm text-gray-500">Puede subir una imagen o PDF del comprobante de pago (opcional).</p>
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
        <button type="button" x-on:click="saveStep(2)" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
            Guardar y Continuar
        </button>
    </div>
</div> 