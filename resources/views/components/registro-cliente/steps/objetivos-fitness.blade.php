{{-- Paso 5: Objetivos Fitness --}}
<div x-show="step === 5" style="display: none;">
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Objetivos Fitness</h2>
    
    <p class="mb-4 sm:mb-6 text-sm sm:text-base text-gray-600">Define tus objetivos de entrenamiento para personalizar tu experiencia.</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <!-- Objetivo Principal -->
        <div>
            <x-input-label for="objetivo_principal" :value="__('Objetivo Principal *')" />
            <select id="objetivo_principal" name="objetivo_principal" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="" selected disabled>Selecciona tu objetivo principal</option>
                <option value="perdida_peso" {{ old('objetivo_principal') == 'perdida_peso' ? 'selected' : '' }}>Pérdida de Peso</option>
                <option value="ganancia_muscular" {{ old('objetivo_principal') == 'ganancia_muscular' ? 'selected' : '' }}>Ganancia Muscular</option>
                <option value="tonificacion" {{ old('objetivo_principal') == 'tonificacion' ? 'selected' : '' }}>Tonificación</option>
                <option value="mejorar_resistencia" {{ old('objetivo_principal') == 'mejorar_resistencia' ? 'selected' : '' }}>Mejorar Resistencia</option>
                <option value="fuerza" {{ old('objetivo_principal') == 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                <option value="flexibilidad" {{ old('objetivo_principal') == 'flexibilidad' ? 'selected' : '' }}>Flexibilidad</option>
            </select>
            <x-input-error :messages="$errors->get('objetivo_principal')" class="mt-2" />
        </div>
        
        <!-- Nivel de Experiencia -->
        <div>
            <x-input-label for="nivel_experiencia" :value="__('Nivel de Experiencia *')" />
            <select id="nivel_experiencia" name="nivel_experiencia" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="" selected disabled>Selecciona tu nivel</option>
                <option value="principiante" {{ old('nivel_experiencia') == 'principiante' ? 'selected' : '' }}>Principiante</option>
                <option value="intermedio" {{ old('nivel_experiencia') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                <option value="avanzado" {{ old('nivel_experiencia') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
            </select>
            <x-input-error :messages="$errors->get('nivel_experiencia')" class="mt-2" />
        </div>
        
        <!-- Días de Entrenamiento -->
        <div>
            <x-input-label for="dias_entrenamiento" :value="__('Días de Entrenamiento por Semana *')" />
            <select id="dias_entrenamiento" name="dias_entrenamiento" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="" selected disabled>Selecciona los días</option>
                @for($i = 1; $i <= 7; $i++)
                    <option value="{{ $i }}" {{ old('dias_entrenamiento') == $i ? 'selected' : '' }}>{{ $i }} día{{ $i > 1 ? 's' : '' }}</option>
                @endfor
            </select>
            <x-input-error :messages="$errors->get('dias_entrenamiento')" class="mt-2" />
        </div>
        
        <!-- Condiciones Médicas -->
        <div class="md:col-span-2">
            <x-input-label for="condiciones_medicas" :value="__('Condiciones Médicas (opcional)')" />
            <textarea id="condiciones_medicas" name="condiciones_medicas" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Menciona cualquier condición médica, lesión o alergia que debamos tener en cuenta">{{ old('condiciones_medicas') }}</textarea>
            <x-input-error :messages="$errors->get('condiciones_medicas')" class="mt-2" />
        </div>
    </div>
    
    <div class="flex flex-col sm:flex-row justify-between mt-6 space-y-4 sm:space-y-0 sm:space-x-4">
        <button type="button" x-on:click="step = 4" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Anterior
        </button>
        <button type="button" x-on:click="saveStep(5)" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Completar Registro
        </button>
    </div>
</div> 