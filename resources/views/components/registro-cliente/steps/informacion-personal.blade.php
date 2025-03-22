{{-- Paso 3: Información Personal --}}
<div x-show="step === 3" style="display: none;" x-data="{
    tieneInformacionPersonal: {{ Auth::user()->cliente && Auth::user()->cliente->fecha_nacimiento ? 'true' : 'false' }},
    
    guardarInformacionPersonal() {
        // Validar campos obligatorios
        let camposFaltantes = [];
        
        if (!document.getElementById('fecha_nacimiento')?.value?.trim()) camposFaltantes.push('Fecha de Nacimiento');
        if (!document.getElementById('telefono')?.value?.trim()) camposFaltantes.push('Teléfono');
        if (!document.getElementById('genero')?.value) camposFaltantes.push('Género');
        if (!document.getElementById('ocupacion')?.value?.trim()) camposFaltantes.push('Ocupación');
        if (!document.getElementById('direccion')?.value?.trim()) camposFaltantes.push('Dirección');
        
        if (camposFaltantes.length > 0) {
            const errorMessage = 'Por favor, complete los campos obligatorios: ' + camposFaltantes.join(', ');
            this.$dispatch('mostrar-error', { mensaje: errorMessage });
            return;
        }
        
        // Mostrar indicador de procesamiento
        this.$dispatch('mostrar-success', { mensaje: 'Guardando información personal...' });
        
        // Crear FormData con los campos del paso 3
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('step', 3);
        
        // Añadir todos los campos del formulario
        formData.append('fecha_nacimiento', document.getElementById('fecha_nacimiento').value);
        formData.append('telefono', document.getElementById('telefono').value);
        formData.append('genero', document.getElementById('genero').value);
        formData.append('ocupacion', document.getElementById('ocupacion').value);
        formData.append('direccion', document.getElementById('direccion').value);
        
        // Añadir foto de perfil si existe
        const fotoPerfil = document.getElementById('foto_perfil');
        if (fotoPerfil && fotoPerfil.files[0]) {
            formData.append('foto_perfil', fotoPerfil.files[0]);
        }
        
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
                    mensaje: data.message || 'Información personal guardada exitosamente' 
                });
                
                // Esperar un momento para mostrar el mensaje de éxito antes de redireccionar
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }, 1500);
            } else {
                this.$dispatch('mostrar-error', { 
                    mensaje: data.message || 'Error al guardar la información personal' 
                });
                console.error('Error en la respuesta:', data);
            }
        })
        .catch(error => {
            this.$dispatch('mostrar-error', { 
                mensaje: 'Error de conexión al guardar la información personal' 
            });
            console.error('Error en la solicitud:', error);
        });
    },
    
    continuarAlSiguientePaso() {
        window.location.href = '{{ route('completar.registro.cliente.form') }}?paso=4';
    }
}">
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Información Personal</h2>
    
    <template x-if="!tieneInformacionPersonal">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Fecha de Nacimiento -->
            <div>
                <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento *')" class="mb-1 text-sm font-medium text-gray-700" />
                <x-text-input id="fecha_nacimiento" class="block w-full" type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento')" required />
                <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-1" />
            </div>
            
            <!-- Género -->
            <div>
                <x-input-label for="genero" :value="__('Género *')" class="mb-1 text-sm font-medium text-gray-700" />
                <select id="genero" name="genero" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="" selected disabled>Selecciona tu género</option>
                    <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                    <option value="otro" {{ old('genero') == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
                <x-input-error :messages="$errors->get('genero')" class="mt-1" />
            </div>
            
            <!-- Teléfono -->
            <div>
                <x-input-label for="telefono" :value="__('Teléfono *')" class="mb-1 text-sm font-medium text-gray-700" />
                <x-text-input id="telefono" class="block w-full" type="tel" name="telefono" :value="old('telefono')" required placeholder="Ej: 555-123-4567" />
                <x-input-error :messages="$errors->get('telefono')" class="mt-1" />
            </div>
            
            <!-- Ocupación -->
            <div>
                <x-input-label for="ocupacion" :value="__('Ocupación *')" class="mb-1 text-sm font-medium text-gray-700" />
                <x-text-input id="ocupacion" class="block w-full" type="text" name="ocupacion" :value="old('ocupacion')" required placeholder="Ej: Estudiante, Ingeniero, etc." />
                <x-input-error :messages="$errors->get('ocupacion')" class="mt-1" />
            </div>
            
            <!-- Dirección -->
            <div class="md:col-span-2">
                <x-input-label for="direccion" :value="__('Dirección *')" class="mb-1 text-sm font-medium text-gray-700" />
                <textarea id="direccion" name="direccion" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Ingresa tu dirección completa">{{ old('direccion') }}</textarea>
                <x-input-error :messages="$errors->get('direccion')" class="mt-1" />
            </div>
            
            <!-- Foto de Perfil -->
            <div class="md:col-span-2">
                <x-input-label for="foto_perfil" :value="__('Foto de Perfil (opcional)')" class="mb-1 text-sm font-medium text-gray-700" />
                <div class="flex items-center space-x-6">
                    <div id="foto-perfil-container" class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center border border-gray-300">
                        <svg id="default-user-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-12 h-12 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <label for="foto_perfil" class="cursor-pointer">
                        <span class="block text-sm font-medium text-gray-700">Cambiar foto</span>
                        <span class="block text-xs text-gray-500">JPG, PNG, GIF (max. 2MB)</span>
                        <input id="foto_perfil" name="foto_perfil" type="file" class="hidden" accept="image/*" @change="previewImage">
                    </label>
                </div>
            </div>
        </div>
    </template>
    
    <template x-if="tieneInformacionPersonal">
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-green-700">Ya has completado tu información personal.</p>
            </div>
            <p class="mt-2 text-sm text-green-600">Puedes continuar al siguiente paso.</p>
        </div>
    </template>

    <div class="flex justify-between mt-8">
        <button type="button" @click="step = 2" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium">
            Anterior
        </button>
        
        <template x-if="!tieneInformacionPersonal">
            <button type="button" @click="guardarInformacionPersonal()" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
                Guardar y Continuar
            </button>
        </template>
        
        <template x-if="tieneInformacionPersonal">
            <button type="button" @click="continuarAlSiguientePaso()" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
                Continuar
            </button>
        </template>
    </div>
</div>