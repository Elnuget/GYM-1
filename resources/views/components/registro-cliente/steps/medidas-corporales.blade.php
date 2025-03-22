{{-- Paso 4: Medidas Corporales --}}
<div x-show="step === 4" style="display: none;" x-data="{
    tieneMedidasCorporales: {{ Auth::user()->cliente && Auth::user()->cliente->medidasCorporales()->where('activo', true)->exists() ? 'true' : 'false' }},
    
    guardarMedidasCorporales() {
        // Validar campos obligatorios
        let camposFaltantes = [];
        
        if (!document.getElementById('peso')?.value?.trim()) camposFaltantes.push('Peso');
        if (!document.getElementById('altura')?.value?.trim()) camposFaltantes.push('Altura');
        
        if (camposFaltantes.length > 0) {
            const errorMessage = 'Por favor, complete los campos obligatorios: ' + camposFaltantes.join(', ');
            this.$dispatch('mostrar-error', { mensaje: errorMessage });
            return;
        }
        
        // Mostrar indicador de procesamiento
        this.$dispatch('mostrar-success', { mensaje: 'Guardando medidas corporales...' });
        
        // Crear FormData con los campos del paso 4
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('step', 4);
        
        // Añadir todos los campos del formulario
        formData.append('peso', document.getElementById('peso').value);
        formData.append('altura', document.getElementById('altura').value);
        formData.append('porcentaje_grasa', document.getElementById('porcentaje_grasa').value || '');
        formData.append('porcentaje_musculo', document.getElementById('porcentaje_musculo').value || '');
        formData.append('medida_cintura', document.getElementById('medida_cintura').value || '');
        formData.append('medida_cadera', document.getElementById('medida_cadera').value || '');
        formData.append('medida_pecho', document.getElementById('medida_pecho').value || '');
        formData.append('medida_brazos', document.getElementById('medida_brazos').value || '');
        formData.append('medida_piernas', document.getElementById('medida_piernas').value || '');
        
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
                    mensaje: data.message || 'Medidas corporales guardadas exitosamente' 
                });
                
                // Esperar un momento para mostrar el mensaje de éxito antes de redireccionar
                setTimeout(() => {
                    window.location.href = '{{ route('completar.registro.cliente.form') }}?paso=5';
                }, 1500);
            } else {
                this.$dispatch('mostrar-error', { 
                    mensaje: data.message || 'Error al guardar las medidas corporales' 
                });
                console.error('Error en la respuesta:', data);
            }
        })
        .catch(error => {
            this.$dispatch('mostrar-error', { 
                mensaje: 'Error de conexión al guardar las medidas corporales' 
            });
            console.error('Error en la solicitud:', error);
        });
    },
    
    continuarAlSiguientePaso() {
        window.location.href = '{{ route('completar.registro.cliente.form') }}?paso=5';
    }
}">
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Medidas Corporales</h2>
    
    <template x-if="!tieneMedidasCorporales">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Peso -->
            <div>
                <x-input-label for="peso" :value="__('Peso (kg) *')" class="mb-1 text-sm font-medium text-gray-700" />
                <x-text-input id="peso" class="block w-full" type="number" name="peso" step="0.01" min="0" :value="old('peso')" required placeholder="Ej: 70.5" />
                <x-input-error :messages="$errors->get('peso')" class="mt-1" />
            </div>
            
            <!-- Altura -->
            <div>
                <x-input-label for="altura" :value="__('Altura (cm) *')" class="mb-1 text-sm font-medium text-gray-700" />
                <x-text-input id="altura" class="block w-full" type="number" name="altura" step="0.01" min="0" :value="old('altura')" required placeholder="Ej: 170" />
                <x-input-error :messages="$errors->get('altura')" class="mt-1" />
            </div>
            
            <!-- Porcentaje de Grasa -->
            <div>
                <x-input-label for="porcentaje_grasa" :value="__('Porcentaje de Grasa (%)')" class="mb-1 text-sm font-medium text-gray-700" />
                <x-text-input id="porcentaje_grasa" class="block w-full" type="number" name="porcentaje_grasa" step="0.1" min="0" max="100" :value="old('porcentaje_grasa')" placeholder="Ej: 20.5" />
                <x-input-error :messages="$errors->get('porcentaje_grasa')" class="mt-1" />
            </div>
            
            <!-- Porcentaje Muscular -->
            <div>
                <x-input-label for="porcentaje_musculo" :value="__('Porcentaje Muscular (%)')" class="mb-1 text-sm font-medium text-gray-700" />
                <x-text-input id="porcentaje_musculo" class="block w-full" type="number" name="porcentaje_musculo" step="0.1" min="0" max="100" :value="old('porcentaje_musculo')" placeholder="Ej: 35.5" />
                <x-input-error :messages="$errors->get('porcentaje_musculo')" class="mt-1" />
            </div>
            
            <!-- Medidas Adicionales -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Medidas Adicionales (cm)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Cintura -->
                    <div>
                        <x-input-label for="medida_cintura" :value="__('Cintura')" class="mb-1 text-sm font-medium text-gray-700" />
                        <x-text-input id="medida_cintura" class="block w-full" type="number" name="medida_cintura" step="0.1" min="0" :value="old('medida_cintura')" placeholder="Ej: 80" />
                    </div>
                    
                    <!-- Cadera -->
                    <div>
                        <x-input-label for="medida_cadera" :value="__('Cadera')" class="mb-1 text-sm font-medium text-gray-700" />
                        <x-text-input id="medida_cadera" class="block w-full" type="number" name="medida_cadera" step="0.1" min="0" :value="old('medida_cadera')" placeholder="Ej: 95" />
                    </div>
                    
                    <!-- Pecho -->
                    <div>
                        <x-input-label for="medida_pecho" :value="__('Pecho')" class="mb-1 text-sm font-medium text-gray-700" />
                        <x-text-input id="medida_pecho" class="block w-full" type="number" name="medida_pecho" step="0.1" min="0" :value="old('medida_pecho')" placeholder="Ej: 90" />
                    </div>
                    
                    <!-- Brazos -->
                    <div>
                        <x-input-label for="medida_brazos" :value="__('Brazos')" class="mb-1 text-sm font-medium text-gray-700" />
                        <x-text-input id="medida_brazos" class="block w-full" type="number" name="medida_brazos" step="0.1" min="0" :value="old('medida_brazos')" placeholder="Ej: 32" />
                    </div>
                    
                    <!-- Piernas -->
                    <div>
                        <x-input-label for="medida_piernas" :value="__('Piernas')" class="mb-1 text-sm font-medium text-gray-700" />
                        <x-text-input id="medida_piernas" class="block w-full" type="number" name="medida_piernas" step="0.1" min="0" :value="old('medida_piernas')" placeholder="Ej: 55" />
                    </div>
                </div>
            </div>
        </div>
    </template>
    
    <template x-if="tieneMedidasCorporales">
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-green-700">Ya has registrado tus medidas corporales.</p>
            </div>
            <p class="mt-2 text-sm text-green-600">Puedes continuar al siguiente paso.</p>
        </div>
    </template>

    <div class="flex justify-between mt-8">
        <button type="button" @click="step = 3" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium">
            Anterior
        </button>
        
        <template x-if="!tieneMedidasCorporales">
            <button type="button" @click="guardarMedidasCorporales()" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
                Guardar y Continuar
            </button>
        </template>
        
        <template x-if="tieneMedidasCorporales">
            <button type="button" @click="continuarAlSiguientePaso()" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
                Continuar
            </button>
        </template>
    </div>
</div> 