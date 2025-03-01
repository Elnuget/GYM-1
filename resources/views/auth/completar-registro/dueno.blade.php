<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ step: 1 }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Indicador de Progreso -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div x-bind:class="{ 'bg-emerald-500': step >= 1, 'bg-gray-300': step < 1 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                                    <span class="text-white font-bold">1</span>
                                </div>
                                <div class="ml-2">
                                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 1, 'text-gray-500': step < 1 }">Información Personal</p>
                                </div>
                            </div>
                            <div class="w-16 h-1 bg-gray-200" x-bind:class="{ 'bg-emerald-300': step > 1 }"></div>
                            <div class="flex items-center">
                                <div x-bind:class="{ 'bg-emerald-500': step >= 2, 'bg-gray-300': step < 2 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                                    <span class="text-white font-bold">2</span>
                                </div>
                                <div class="ml-2">
                                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 2, 'text-gray-500': step < 2 }">Información del Gimnasio</p>
                                </div>
                            </div>
                            <div class="w-16 h-1 bg-gray-200" x-bind:class="{ 'bg-emerald-300': step > 2 }"></div>
                            <div class="flex items-center">
                                <div x-bind:class="{ 'bg-emerald-500': step >= 3, 'bg-gray-300': step < 3 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                                    <span class="text-white font-bold">3</span>
                                </div>
                                <div class="ml-2">
                                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 3, 'text-gray-500': step < 3 }">Membresía Inicial</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('completar.registro.dueno') }}" enctype="multipart/form-data" x-ref="form">
                        @csrf
                        
                        <!-- Paso 1: Información Personal -->
                        <div x-show="step === 1">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Información Personal</h2>
                            
                            <p class="mb-6 text-gray-600">Completa tu información personal para configurar tu cuenta como dueño de gimnasio.</p>
                            
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
                                
                                <!-- Teléfono Personal -->
                                <div>
                                    <x-input-label for="telefono_personal" :value="__('Teléfono Personal')" />
                                    <x-text-input id="telefono_personal" class="block mt-1 w-full" type="text" name="telefono_personal" :value="old('telefono_personal', $user->telefono ?? '')" required placeholder="+34 612345678" />
                                    <x-input-error :messages="$errors->get('telefono_personal')" class="mt-2" />
                                </div>
                                
                                <!-- Dirección Personal -->
                                <div>
                                    <x-input-label for="direccion_personal" :value="__('Dirección Personal')" />
                                    <x-text-input id="direccion_personal" class="block mt-1 w-full" type="text" name="direccion_personal" :value="old('direccion_personal', $user->direccion ?? '')" required />
                                    <x-input-error :messages="$errors->get('direccion_personal')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="button" x-on:click="step = 2" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Siguiente
                                </button>
                            </div>
                        </div>
                        
                        <!-- Paso 2: Información del Gimnasio -->
                        <div x-show="step === 2" x-cloak>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Registra tu Gimnasio</h2>
                            
                            <p class="mb-6 text-gray-600">¡Gracias por unirte a nuestra plataforma! Ahora necesitamos registrar la información de tu gimnasio para completar el proceso.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nombre Comercial -->
                                <div>
                                    <x-input-label for="nombre_comercial" :value="__('Nombre Comercial del Gimnasio')" />
                                    <x-text-input id="nombre_comercial" class="block mt-1 w-full" type="text" name="nombre_comercial" :value="old('nombre_comercial')" required autofocus />
                                    <x-input-error :messages="$errors->get('nombre_comercial')" class="mt-2" />
                                </div>
                                
                                <!-- Teléfono del Gimnasio -->
                                <div>
                                    <x-input-label for="telefono_gimnasio" :value="__('Teléfono del Gimnasio')" />
                                    <x-text-input id="telefono_gimnasio" class="block mt-1 w-full" type="text" name="telefono_gimnasio" :value="old('telefono_gimnasio')" required placeholder="+34 912345678" />
                                    <x-input-error :messages="$errors->get('telefono_gimnasio')" class="mt-2" />
                                </div>
                                
                                <!-- Dirección del Gimnasio -->
                                <div class="md:col-span-2">
                                    <x-input-label for="direccion_gimnasio" :value="__('Dirección del Gimnasio')" />
                                    <x-text-input id="direccion_gimnasio" class="block mt-1 w-full" type="text" name="direccion_gimnasio" :value="old('direccion_gimnasio')" required />
                                    <x-input-error :messages="$errors->get('direccion_gimnasio')" class="mt-2" />
                                </div>
                                
                                <!-- Logo del Gimnasio -->
                                <div class="md:col-span-2">
                                    <x-input-label for="logo_gimnasio" :value="__('Logo del Gimnasio')" />
                                    <div class="mt-2 flex items-center">
                                        <div class="w-32 h-32 bg-gray-200 flex items-center justify-center overflow-hidden">
                                            <img id="preview-logo" src="{{ asset('images/default-gym-logo.png') }}" alt="Vista previa del logo" class="w-full h-full object-contain">
                                        </div>
                                        <input type="file" id="logo_gimnasio" name="logo_gimnasio" class="ml-5 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*" onchange="document.getElementById('preview-logo').src = window.URL.createObjectURL(this.files[0])">
                                    </div>
                                    <x-input-error :messages="$errors->get('logo_gimnasio')" class="mt-2" />
                                </div>
                                
                                <!-- Descripción -->
                                <div class="md:col-span-2">
                                    <x-input-label for="descripcion" :value="__('Descripción del Gimnasio')" />
                                    <textarea id="descripcion" name="descripcion" rows="4" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describe brevemente tu gimnasio, servicios, especialidades, etc.">{{ old('descripcion') }}</textarea>
                                    <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div class="flex justify-between mt-6">
                                <button type="button" x-on:click="step = 1" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Anterior
                                </button>
                                <button type="button" x-on:click="step = 3" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Siguiente
                                </button>
                            </div>
                        </div>
                        
                        <!-- Paso 3: Membresía Inicial -->
                        <div x-show="step === 3" x-cloak>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Configura tu Membresía Inicial</h2>
                            
                            <p class="mb-6 text-gray-600">Configura al menos una membresía para tu gimnasio. Podrás añadir más opciones después desde el panel de administración.</p>
                            
                            <div class="bg-blue-50 p-4 mb-6 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            Las membresías son los planes que ofreces a tus clientes. Define al menos una para comenzar.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nombre de la Membresía -->
                                <div>
                                    <x-input-label for="membresia_nombre" :value="__('Nombre de la Membresía')" />
                                    <x-text-input id="membresia_nombre" class="block mt-1 w-full" type="text" name="membresia_nombre" :value="old('membresia_nombre')" required placeholder="Ej: Plan Básico, Plan Premium, etc." />
                                    <x-input-error :messages="$errors->get('membresia_nombre')" class="mt-2" />
                                </div>
                                
                                <!-- Precio -->
                                <div>
                                    <x-input-label for="membresia_precio" :value="__('Precio (€)')" />
                                    <x-text-input id="membresia_precio" class="block mt-1 w-full" type="number" step="0.01" name="membresia_precio" :value="old('membresia_precio')" required placeholder="Ej: 29.99" />
                                    <x-input-error :messages="$errors->get('membresia_precio')" class="mt-2" />
                                </div>
                                
                                <!-- Duración -->
                                <div>
                                    <x-input-label for="membresia_duracion" :value="__('Duración')" />
                                    <select id="membresia_duracion" name="membresia_duracion" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="" selected disabled>Selecciona la duración</option>
                                        <option value="7" {{ old('membresia_duracion') == '7' ? 'selected' : '' }}>1 semana</option>
                                        <option value="30" {{ old('membresia_duracion') == '30' ? 'selected' : '' }}>1 mes</option>
                                        <option value="90" {{ old('membresia_duracion') == '90' ? 'selected' : '' }}>3 meses</option>
                                        <option value="180" {{ old('membresia_duracion') == '180' ? 'selected' : '' }}>6 meses</option>
                                        <option value="365" {{ old('membresia_duracion') == '365' ? 'selected' : '' }}>1 año</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('membresia_duracion')" class="mt-2" />
                                </div>
                                
                                <!-- Tipo de Membresía -->
                                <div>
                                    <x-input-label for="membresia_tipo" :value="__('Tipo de Membresía')" />
                                    <select id="membresia_tipo" name="membresia_tipo" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="" selected disabled>Selecciona el tipo</option>
                                        <option value="basica" {{ old('membresia_tipo') == 'basica' ? 'selected' : '' }}>Básica</option>
                                        <option value="estandar" {{ old('membresia_tipo') == 'estandar' ? 'selected' : '' }}>Estándar</option>
                                        <option value="premium" {{ old('membresia_tipo') == 'premium' ? 'selected' : '' }}>Premium</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('membresia_tipo')" class="mt-2" />
                                </div>
                                
                                <!-- Descripción de la Membresía -->
                                <div class="md:col-span-2">
                                    <x-input-label for="membresia_descripcion" :value="__('Descripción de la Membresía')" />
                                    <textarea id="membresia_descripcion" name="membresia_descripcion" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describe los beneficios y características de esta membresía">{{ old('membresia_descripcion') }}</textarea>
                                    <x-input-error :messages="$errors->get('membresia_descripcion')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div class="flex justify-between mt-6">
                                <button type="button" x-on:click="step = 2" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Anterior
                                </button>
                                <x-primary-button class="ml-3 bg-emerald-600 hover:bg-emerald-700">
                                    {{ __('Completar Registro') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formData', () => ({
                step: 1
            }));
        });
    </script>
    @endpush
</x-app-layout> 