{{-- Paso 5: Objetivos Fitness --}}
<div x-show="step === 5" style="display: none;" x-data="{
    guardarObjetivosFitness() {
        // Validar campos obligatorios
        let camposFaltantes = [];
        
        if (!document.getElementById('objetivo_principal')?.value) camposFaltantes.push('Objetivo Principal');
        if (!document.getElementById('nivel_experiencia')?.value) camposFaltantes.push('Nivel de Experiencia');
        if (!document.getElementById('dias_entrenamiento')?.value) camposFaltantes.push('Días de Entrenamiento');
        
        if (camposFaltantes.length > 0) {
            const errorMessage = 'Por favor, complete los campos obligatorios: ' + camposFaltantes.join(', ');
            this.$dispatch('mostrar-error', { mensaje: errorMessage });
            return;
        }
        
        // Mostrar indicador de procesamiento
        this.$dispatch('mostrar-success', { mensaje: 'Guardando objetivos fitness...' });
        
        // Crear FormData con los campos del paso 5
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('step', 5);
        
        // Añadir todos los campos del formulario
        formData.append('objetivo_principal', document.getElementById('objetivo_principal').value);
        formData.append('nivel_experiencia', document.getElementById('nivel_experiencia').value);
        formData.append('dias_entrenamiento', document.getElementById('dias_entrenamiento').value);
        formData.append('condiciones_medicas', document.getElementById('condiciones_medicas')?.value || '');
        
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
                    mensaje: data.message || 'Objetivos fitness guardados exitosamente' 
                });
                
                // Esperar un momento para mostrar el mensaje de éxito antes de redireccionar
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }, 1500);
            } else {
                this.$dispatch('mostrar-error', { 
                    mensaje: data.message || 'Error al guardar los objetivos fitness' 
                });
                console.error('Error en la respuesta:', data);
            }
        })
        .catch(error => {
            this.$dispatch('mostrar-error', { 
                mensaje: 'Error de conexión al guardar los objetivos fitness' 
            });
            console.error('Error en la solicitud:', error);
        });
    }
}">
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Objetivos Fitness</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Objetivo Principal -->
        <div class="md:col-span-2">
            <x-input-label for="objetivo_principal" :value="__('Objetivo Principal *')" class="mb-1 text-sm font-medium text-gray-700" />
            <select id="objetivo_principal" name="objetivo_principal" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                <option value="" selected disabled>Selecciona tu objetivo principal</option>
                <option value="perdida_peso">Pérdida de peso</option>
                <option value="ganancia_muscular">Ganancia muscular</option>
                <option value="tonificacion">Tonificación</option>
                <option value="resistencia">Mejorar resistencia</option>
                <option value="flexibilidad">Mejorar flexibilidad</option>
                <option value="salud_general">Salud general</option>
            </select>
            <x-input-error :messages="$errors->get('objetivo_principal')" class="mt-1" />
        </div>
        
        <!-- Nivel de Experiencia -->
        <div>
            <x-input-label for="nivel_experiencia" :value="__('Nivel de Experiencia *')" class="mb-1 text-sm font-medium text-gray-700" />
            <select id="nivel_experiencia" name="nivel_experiencia" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                <option value="" selected disabled>Selecciona tu nivel</option>
                <option value="principiante">Principiante</option>
                <option value="intermedio">Intermedio</option>
                <option value="avanzado">Avanzado</option>
            </select>
            <x-input-error :messages="$errors->get('nivel_experiencia')" class="mt-1" />
        </div>
        
        <!-- Días de Entrenamiento -->
        <div>
            <x-input-label for="dias_entrenamiento" :value="__('Días de Entrenamiento por Semana *')" class="mb-1 text-sm font-medium text-gray-700" />
            <select id="dias_entrenamiento" name="dias_entrenamiento" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                <option value="" selected disabled>Selecciona los días</option>
                @for ($i = 1; $i <= 7; $i++)
                    <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'día' : 'días' }}</option>
                @endfor
            </select>
            <x-input-error :messages="$errors->get('dias_entrenamiento')" class="mt-1" />
        </div>
        
        <!-- Condiciones Médicas -->
        <div class="md:col-span-2">
            <x-input-label for="condiciones_medicas" :value="__('Condiciones Médicas o Lesiones (opcional)')" class="mb-1 text-sm font-medium text-gray-700" />
            <textarea id="condiciones_medicas" name="condiciones_medicas" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Describe cualquier condición médica o lesión que debamos tener en cuenta"></textarea>
            <x-input-error :messages="$errors->get('condiciones_medicas')" class="mt-1" />
        </div>
    </div>

    <div class="flex justify-between mt-8">
        <button type="button" @click="step = 4" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium">
            Anterior
        </button>
        
        <button type="button" @click="guardarObjetivosFitness()" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
            Finalizar Registro
        </button>
    </div>
</div> 