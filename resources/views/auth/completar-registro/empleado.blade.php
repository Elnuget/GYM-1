<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Completa tu Perfil de Empleado</h2>
                    
                    <p class="mb-6 text-gray-600">Para configurar correctamente tu cuenta, necesitamos algunos datos adicionales sobre tu experiencia y especialización.</p>
                    
                    <form method="POST" action="{{ route('completar.registro.empleado') }}" enctype="multipart/form-data">
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
                                
                                <!-- Dirección -->
                                <div>
                                    <x-input-label for="direccion" :value="__('Dirección')" />
                                    <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" required />
                                    <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Información Profesional</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Experiencia -->
                                <div>
                                    <x-input-label for="experiencia" :value="__('Experiencia Profesional')" />
                                    <select id="experiencia" name="experiencia" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="" selected disabled>Selecciona tu nivel de experiencia</option>
                                        <option value="Menos de 1 año" {{ old('experiencia') == 'Menos de 1 año' ? 'selected' : '' }}>Menos de 1 año</option>
                                        <option value="1-3 años" {{ old('experiencia') == '1-3 años' ? 'selected' : '' }}>1-3 años</option>
                                        <option value="3-5 años" {{ old('experiencia') == '3-5 años' ? 'selected' : '' }}>3-5 años</option>
                                        <option value="Más de 5 años" {{ old('experiencia') == 'Más de 5 años' ? 'selected' : '' }}>Más de 5 años</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('experiencia')" class="mt-2" />
                                </div>
                                
                                <!-- Especialidad -->
                                <div>
                                    <x-input-label for="especialidad" :value="__('Especialidad')" />
                                    <x-text-input id="especialidad" class="block mt-1 w-full" type="text" name="especialidad" :value="old('especialidad')" placeholder="Ej: Nutrición deportiva, CrossFit, Yoga, etc." />
                                    <x-input-error :messages="$errors->get('especialidad')" class="mt-2" />
                                </div>
                                
                                <!-- Certificaciones -->
                                <div>
                                    <x-input-label for="certificaciones" :value="__('Certificaciones')" />
                                    <x-text-input id="certificaciones" class="block mt-1 w-full" type="text" name="certificaciones" :value="old('certificaciones')" placeholder="Ej: Personal Trainer, Nutricionista, etc." />
                                    <x-input-error :messages="$errors->get('certificaciones')" class="mt-2" />
                                </div>
                                
                                <!-- Horario de Disponibilidad -->
                                <div>
                                    <x-input-label for="horario_disponibilidad" :value="__('Horario de Disponibilidad')" />
                                    <x-text-input id="horario_disponibilidad" class="block mt-1 w-full" type="text" name="horario_disponibilidad" :value="old('horario_disponibilidad')" placeholder="Ej: Lunes a Viernes, 8:00 - 16:00" />
                                    <x-input-error :messages="$errors->get('horario_disponibilidad')" class="mt-2" />
                                </div>
                                
                                <!-- Descripción Profesional -->
                                <div class="md:col-span-2">
                                    <x-input-label for="descripcion_profesional" :value="__('Descripción Profesional')" />
                                    <textarea id="descripcion_profesional" name="descripcion_profesional" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describe brevemente tu experiencia, enfoque de entrenamiento y filosofía profesional">{{ old('descripcion_profesional') }}</textarea>
                                    <x-input-error :messages="$errors->get('descripcion_profesional')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 p-4 mt-6 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Esta información será utilizada para asignar tareas y responsabilidades dentro del gimnasio.
                                    </p>
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