{{-- Paso 4: Medidas Corporales --}}
<div x-show="step === 4" style="display: none;">
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4">Medidas Corporales</h2>
    
    <p class="mb-4 text-sm sm:text-base text-gray-600">Registra tus medidas corporales para un mejor seguimiento de tu progreso.</p>
    
    <div class="space-y-6">
        <!-- Medidas Obligatorias -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Peso -->
            <div>
                <x-input-label for="peso" :value="__('Peso (kg) *')" />
                <x-text-input id="peso" class="block mt-1 w-full" type="number" step="0.1" name="peso" :value="old('peso')" required placeholder="Ej: 70.5" />
                <x-input-error :messages="$errors->get('peso')" class="mt-2" />
            </div>
            
            <!-- Altura -->
            <div>
                <x-input-label for="altura" :value="__('Altura (cm) *')" />
                <x-text-input id="altura" class="block mt-1 w-full" type="number" step="0.1" name="altura" :value="old('altura')" required placeholder="Ej: 175" />
                <x-input-error :messages="$errors->get('altura')" class="mt-2" />
            </div>
        </div>

        <!-- Medidas Opcionales -->
        <div x-data="{ mostrarOpcionales: false }">
            <button type="button" 
                    @click="mostrarOpcionales = !mostrarOpcionales"
                    class="flex items-center justify-between w-full px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <span class="text-sm font-medium text-gray-600">Medidas Adicionales (Opcional)</span>
                <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" 
                     :class="{ 'rotate-180': mostrarOpcionales }"
                     fill="none" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="mostrarOpcionales" 
                 class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                <!-- Cintura -->
                <div>
                    <x-input-label for="cintura" :value="__('Cintura (cm)')" />
                    <x-text-input id="cintura" class="block mt-1 w-full" type="number" step="0.1" name="cintura" :value="old('cintura')" placeholder="Ej: 80" />
                    <x-input-error :messages="$errors->get('cintura')" class="mt-2" />
                </div>
                
                <!-- Pecho -->
                <div>
                    <x-input-label for="pecho" :value="__('Pecho (cm)')" />
                    <x-text-input id="pecho" class="block mt-1 w-full" type="number" step="0.1" name="pecho" :value="old('pecho')" placeholder="Ej: 95" />
                    <x-input-error :messages="$errors->get('pecho')" class="mt-2" />
                </div>
                
                <!-- Bíceps -->
                <div>
                    <x-input-label for="biceps" :value="__('Bíceps (cm)')" />
                    <x-text-input id="biceps" class="block mt-1 w-full" type="number" step="0.1" name="biceps" :value="old('biceps')" placeholder="Ej: 32" />
                    <x-input-error :messages="$errors->get('biceps')" class="mt-2" />
                </div>
                
                <!-- Muslos -->
                <div>
                    <x-input-label for="muslos" :value="__('Muslos (cm)')" />
                    <x-text-input id="muslos" class="block mt-1 w-full" type="number" step="0.1" name="muslos" :value="old('muslos')" placeholder="Ej: 55" />
                    <x-input-error :messages="$errors->get('muslos')" class="mt-2" />
                </div>
                
                <!-- Pantorrillas -->
                <div class="md:col-span-2">
                    <x-input-label for="pantorrillas" :value="__('Pantorrillas (cm)')" />
                    <x-text-input id="pantorrillas" class="block mt-1 w-full" type="number" step="0.1" name="pantorrillas" :value="old('pantorrillas')" placeholder="Ej: 37" />
                    <x-input-error :messages="$errors->get('pantorrillas')" class="mt-2" />
                </div>
            </div>
        </div>
        
        <!-- Botones de navegación -->
        <div class="flex flex-col sm:flex-row justify-between mt-6 space-y-4 sm:space-y-0 sm:space-x-4">
            <button type="button" 
                    x-on:click="step = 3" 
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Anterior
            </button>
            <button type="button" 
                    x-on:click="saveStep(4)" 
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Guardar y Continuar
            </button>
        </div>
    </div>
</div> 