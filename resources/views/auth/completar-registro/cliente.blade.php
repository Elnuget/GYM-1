<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Completa tu Perfil de Cliente</h2>
                    
                    <p class="mb-6 text-gray-600">Para brindarte una mejor experiencia, necesitamos algunos datos adicionales. Esta información nos ayudará a personalizar tu plan de entrenamiento y seguimiento.</p>
                    
                    <form method="POST" action="{{ route('completar.registro.cliente') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Información Personal</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Foto de Perfil -->
                                <div class="md:col-span-2">
                                    <x-input-label for="foto_perfil" :value="__('Foto de Perfil')" />
                                    <div class="mt-2 flex items-center">
                                        <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                            <img id="preview-image" src="{{ $user->foto_perfil ? asset($user->foto_perfil) : asset('images/default-avatar.png') }}" alt="Vista previa" class="w-full h-full object-cover">
                                        </div>
                                        <input type="file" id="foto_perfil" name="foto_perfil" class="ml-5 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*" onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0])">
                                    </div>
                                    <x-input-error :messages="$errors->get('foto_perfil')" class="mt-2" />
                                </div>
                                
                                <!-- Fecha de Nacimiento -->
                                <div>
                                    <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" />
                                    <x-text-input id="fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento')" required />
                                    <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
                                </div>
                                
                                <!-- Teléfono -->
                                <div>
                                    <x-input-label for="telefono" :value="__('Teléfono')" />
                                    <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono', $user->telefono ?? '')" required placeholder="+34 612345678" />
                                    <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                                </div>
                                
                                <!-- Género -->
                                <div>
                                    <x-input-label for="genero" :value="__('Género')" />
                                    <select id="genero" name="genero" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="" selected disabled>Selecciona tu género</option>
                                        <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                        <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                        <option value="otro" {{ old('genero') == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('genero')" class="mt-2" />
                                </div>
                                
                                <!-- Ocupación -->
                                <div>
                                    <x-input-label for="ocupacion" :value="__('Ocupación')" />
                                    <x-text-input id="ocupacion" class="block mt-1 w-full" type="text" name="ocupacion" :value="old('ocupacion')" placeholder="Ej: Estudiante, Profesional, etc." />
                                    <x-input-error :messages="$errors->get('ocupacion')" class="mt-2" />
                                </div>
                                
                                <!-- Dirección -->
                                <div class="md:col-span-2">
                                    <x-input-label for="direccion" :value="__('Dirección')" />
                                    <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" required />
                                    <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Información Física y Objetivos</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Peso -->
                                <div>
                                    <x-input-label for="peso" :value="__('Peso (kg)')" />
                                    <x-text-input id="peso" class="block mt-1 w-full" type="number" step="0.1" name="peso" :value="old('peso')" placeholder="Ej: 70.5" />
                                    <x-input-error :messages="$errors->get('peso')" class="mt-2" />
                                </div>
                                
                                <!-- Altura -->
                                <div>
                                    <x-input-label for="altura" :value="__('Altura (cm)')" />
                                    <x-text-input id="altura" class="block mt-1 w-full" type="number" name="altura" :value="old('altura')" placeholder="Ej: 175" />
                                    <x-input-error :messages="$errors->get('altura')" class="mt-2" />
                                </div>
                                
                                <!-- Objetivo Fitness -->
                                <div>
                                    <x-input-label for="objetivo_fitness" :value="__('Objetivo Fitness')" />
                                    <select id="objetivo_fitness" name="objetivo_fitness" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="" selected disabled>Selecciona tu objetivo principal</option>
                                        <option value="perder_peso" {{ old('objetivo_fitness') == 'perder_peso' ? 'selected' : '' }}>Perder peso</option>
                                        <option value="ganar_musculo" {{ old('objetivo_fitness') == 'ganar_musculo' ? 'selected' : '' }}>Ganar músculo</option>
                                        <option value="mejorar_resistencia" {{ old('objetivo_fitness') == 'mejorar_resistencia' ? 'selected' : '' }}>Mejorar resistencia</option>
                                        <option value="tonificar" {{ old('objetivo_fitness') == 'tonificar' ? 'selected' : '' }}>Tonificar</option>
                                        <option value="mantenimiento" {{ old('objetivo_fitness') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                        <option value="otro" {{ old('objetivo_fitness') == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('objetivo_fitness')" class="mt-2" />
                                </div>
                                
                                <!-- Nivel de Actividad -->
                                <div>
                                    <x-input-label for="nivel_actividad" :value="__('Nivel de Actividad')" />
                                    <select id="nivel_actividad" name="nivel_actividad" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="" selected disabled>Selecciona tu nivel de actividad</option>
                                        <option value="sedentario" {{ old('nivel_actividad') == 'sedentario' ? 'selected' : '' }}>Sedentario (poco o nada de ejercicio)</option>
                                        <option value="ligero" {{ old('nivel_actividad') == 'ligero' ? 'selected' : '' }}>Ligero (ejercicio 1-3 días/semana)</option>
                                        <option value="moderado" {{ old('nivel_actividad') == 'moderado' ? 'selected' : '' }}>Moderado (ejercicio 3-5 días/semana)</option>
                                        <option value="activo" {{ old('nivel_actividad') == 'activo' ? 'selected' : '' }}>Activo (ejercicio 6-7 días/semana)</option>
                                        <option value="muy_activo" {{ old('nivel_actividad') == 'muy_activo' ? 'selected' : '' }}>Muy activo (ejercicio intenso diario o 2 veces/día)</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('nivel_actividad')" class="mt-2" />
                                </div>
                                
                                <!-- Condiciones Médicas -->
                                <div class="md:col-span-2">
                                    <x-input-label for="condiciones_medicas" :value="__('Condiciones Médicas (opcional)')" />
                                    <textarea id="condiciones_medicas" name="condiciones_medicas" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Menciona cualquier condición médica, lesión o alergia que debamos tener en cuenta">{{ old('condiciones_medicas') }}</textarea>
                                    <x-input-error :messages="$errors->get('condiciones_medicas')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button class="ml-3 bg-emerald-600 hover:bg-emerald-700">
                                {{ __('Guardar y Continuar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 