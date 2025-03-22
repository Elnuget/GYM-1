{{-- Paso 3: Información Personal --}}
<div x-show="step === 3" style="display: none;">
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Información Personal</h2>
    
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

    <div class="flex justify-between mt-8">
        <button type="button" @click="step = 2" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium">
            Anterior
        </button>
        <button type="button" x-on:click="saveStep(3)" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
            Guardar y Continuar
        </button>
    </div>
</div>