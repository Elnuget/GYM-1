<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-data="{ 
                step: {{ session('current_step', 1) }},
                showSuccessModal: false,
                showErrorModal: false,
                modalMessage: '',
                errorMessage: '',
                formData: {},
                
                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        
                        reader.onload = (e) => {
                            const container = document.getElementById('foto-perfil-container');
                            const defaultIcon = document.getElementById('default-user-icon');
                            
                            // Eliminar el icono SVG si existe
                            if (defaultIcon) {
                                defaultIcon.remove();
                            }
                            
                            // Buscar si ya existe una imagen de vista previa
                            let previewImg = document.getElementById('preview-image');
                            
                            if (!previewImg) {
                                // Si no existe, crear una nueva imagen
                                previewImg = document.createElement('img');
                                previewImg.id = 'preview-image';
                                previewImg.className = 'w-full h-full object-cover';
                                previewImg.alt = 'Vista previa';
                                container.appendChild(previewImg);
                            }
                            
                            // Actualizar la fuente de la imagen
                            previewImg.src = e.target.result;
                        };
                        
                        reader.readAsDataURL(file);
                    }
                },
                
                saveStep(currentStep) {
                    const formElement = this.$refs.form;
                    const formData = new FormData(formElement);
                    formData.append('step', currentStep);
                    
                    // Validar campos obligatorios
                    let camposFaltantes = [];
                    
                    if (currentStep === 1) {
                        if (!formElement.fecha_nacimiento.value.trim()) camposFaltantes.push('Fecha de Nacimiento');
                        if (!formElement.telefono.value.trim()) camposFaltantes.push('Teléfono');
                        if (!formElement.genero.value) camposFaltantes.push('Género');
                        if (!formElement.ocupacion.value.trim()) camposFaltantes.push('Ocupación');
                        if (!formElement.direccion.value.trim()) camposFaltantes.push('Dirección');
                    } else if (currentStep === 2) {
                        if (!formElement.peso.value.trim()) camposFaltantes.push('Peso');
                        if (!formElement.altura.value.trim()) camposFaltantes.push('Altura');
                    }
                    
                    if (camposFaltantes.length > 0) {
                        this.errorMessage = 'Por favor, complete los campos obligatorios: ' + camposFaltantes.join(', ');
                        this.showErrorModal = true;
                        return;
                    }
                    
                    // Mostrar indicador de procesamiento
                    this.modalMessage = 'Guardando información...';
                    this.showSuccessModal = true;
                    
                    // Enviar el formulario
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
                        this.showSuccessModal = false;
                        
                        if (data.success) {
                            this.modalMessage = data.message;
                            this.showSuccessModal = true;
                            
                            setTimeout(() => {
                                this.showSuccessModal = false;
                                if (currentStep < 3) {
                                    this.step = currentStep + 1;
                                }
                            }, 1500);
                        } else {
                            this.errorMessage = data.message || 'Error al guardar los datos';
                            this.showErrorModal = true;
                        }
                    })
                    .catch(error => {
                        this.showSuccessModal = false;
                        this.errorMessage = 'Error de conexión';
                        this.showErrorModal = true;
                    });
                }
            }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Mensajes de Error o Éxito -->
                    @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">¡Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">¡Éxito!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">¡Hay errores en el formulario!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <div class="mt-2 font-semibold">
                            Por favor, complete todos los campos obligatorios marcados con * para poder continuar.
                        </div>
                    </div>
                    @endif
                    
                    <!-- Indicador de Progreso -->
                    <div class="mb-8">
                        <!-- Indicador de progreso para móviles (vertical) -->
                        <div class="sm:hidden">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= 1, 'bg-gray-300': step < 1 }" class="flex items-center justify-center w-8 h-8 rounded-full">
                                        <span class="text-white font-bold text-sm">1</span>
                                    </div>
                                    <div class="ml-2 flex-1">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 1, 'text-gray-500': step < 1 }">Información Personal</p>
                                        <div x-bind:class="{ 'bg-emerald-300': step > 1, 'bg-gray-200': step <= 1 }" class="h-1 w-full mt-2"></div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= 2, 'bg-gray-300': step < 2 }" class="flex items-center justify-center w-8 h-8 rounded-full">
                                        <span class="text-white font-bold text-sm">2</span>
                                    </div>
                                    <div class="ml-2 flex-1">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 2, 'text-gray-500': step < 2 }">Medidas Corporales</p>
                                        <div x-bind:class="{ 'bg-emerald-300': step > 2, 'bg-gray-200': step <= 2 }" class="h-1 w-full mt-2"></div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= 3, 'bg-gray-300': step < 3 }" class="flex items-center justify-center w-8 h-8 rounded-full">
                                        <span class="text-white font-bold text-sm">3</span>
                                    </div>
                                    <div class="ml-2 flex-1">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 3, 'text-gray-500': step < 3 }">Objetivos Fitness</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Indicador de progreso para desktop (horizontal) -->
                        <div class="hidden sm:block">
                            <div class="flex items-center">
                                <div class="flex items-center relative">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= 1, 'bg-gray-300': step < 1 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                                        <span class="text-white font-bold">1</span>
                                    </div>
                                    <div class="ml-2 mr-8">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 1, 'text-gray-500': step < 1 }">Información Personal</p>
                                    </div>
                                </div>
                                
                                <div x-bind:class="{ 'bg-emerald-300': step > 1, 'bg-gray-200': step <= 1 }" class="flex-1 h-1"></div>
                                
                                <div class="flex items-center relative">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= 2, 'bg-gray-300': step < 2 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                                        <span class="text-white font-bold">2</span>
                                    </div>
                                    <div class="ml-2 mr-8">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 2, 'text-gray-500': step < 2 }">Medidas Corporales</p>
                                    </div>
                                </div>
                                
                                <div x-bind:class="{ 'bg-emerald-300': step > 2, 'bg-gray-200': step <= 2 }" class="flex-1 h-1"></div>
                                
                                <div class="flex items-center relative">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= 3, 'bg-gray-300': step < 3 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                                        <span class="text-white font-bold">3</span>
                                    </div>
                                    <div class="ml-2">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 3, 'text-gray-500': step < 3 }">Objetivos Fitness</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('completar.registro.cliente') }}" enctype="multipart/form-data" x-ref="form" @submit.prevent="
                        const formData = new FormData($event.target);
                        
                        // Validar campos requeridos antes de enviar
                        let camposFaltantes = [];
                        
                        if (step === 3) {
                            if (!$event.target.objetivo_principal.value) camposFaltantes.push('Objetivo Principal');
                            if (!$event.target.nivel_experiencia.value) camposFaltantes.push('Nivel de Experiencia');
                            if (!$event.target.dias_entrenamiento.value) camposFaltantes.push('Días de Entrenamiento');
                        }
                        
                        if (camposFaltantes.length > 0) {
                            errorMessage = `Por favor, complete los siguientes campos obligatorios: ${camposFaltantes.join(', ')}`;
                            showErrorModal = true;
                            return;
                        }
                        
                        fetch($event.target.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                modalMessage = data.message;
                                showSuccessModal = true;
                                // Redirigir después de 3 segundos
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 3000);
                            } else {
                                errorMessage = data.message;
                                showErrorModal = true;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            errorMessage = 'Ha ocurrido un error al procesar la solicitud';
                            showErrorModal = true;
                        });">
                        @csrf
                        <input type="hidden" name="current_step" x-bind:value="step">
                        
                        <!-- Paso 1: Información Personal -->
                        <div x-show="step === 1">
                            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Información Personal</h2>
                            
                            <p class="mb-4 sm:mb-6 text-sm sm:text-base text-gray-600">Completa tu información personal para configurar tu perfil de cliente.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <!-- Foto de Perfil -->
                                <div class="md:col-span-2">
                                    <x-input-label for="foto_perfil" :value="__('Foto de Perfil')" />
                                    <div class="mt-2 flex items-center">
                                        <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden" id="foto-perfil-container">
                                            @if($user->foto_perfil && file_exists(public_path($user->foto_perfil)))
                                                <img id="preview-image" src="{{ asset($user->foto_perfil) }}" alt="Vista previa" class="w-full h-full object-cover">
                                            @else
                                                <svg id="default-user-icon" class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <input type="file" 
                                               id="foto_perfil" 
                                               name="foto_perfil" 
                                               class="ml-5 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" 
                                               accept="image/*"
                                               @change="previewImage($event)">
                                    </div>
                                    <x-input-error :messages="$errors->get('foto_perfil')" class="mt-2" />
                                </div>
                                
                                <!-- Fecha de Nacimiento -->
                                <div>
                                    <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento *')" />
                                    <x-text-input id="fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento')" required />
                                    <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
                                </div>
                                
                                <!-- Teléfono -->
                                <div>
                                    <x-input-label for="telefono" :value="__('Teléfono *')" />
                                    <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono', $user->telefono ?? '')" required placeholder="0999999999" />
                                    <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                                </div>
                                
                                <!-- Género -->
                                <div>
                                    <x-input-label for="genero" :value="__('Género *')" />
                                    <select id="genero" name="genero" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="" selected disabled>Selecciona tu género</option>
                                        <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                        <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('genero')" class="mt-2" />
                                </div>
                                
                                <!-- Ocupación -->
                                <div>
                                    <x-input-label for="ocupacion" :value="__('Ocupación *')" />
                                    <x-text-input id="ocupacion" class="block mt-1 w-full" type="text" name="ocupacion" :value="old('ocupacion')" required placeholder="Ej: Estudiante, Profesional, etc." />
                                    <x-input-error :messages="$errors->get('ocupacion')" class="mt-2" />
                                </div>
                                
                                <!-- Dirección -->
                                <div class="md:col-span-2">
                                    <x-input-label for="direccion" :value="__('Dirección *')" />
                                    <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion', $user->direccion ?? '')" required />
                                    <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row justify-end mt-6 space-y-4 sm:space-y-0 sm:space-x-4">
                                <button type="button" x-on:click="saveStep(1)" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Guardar y Continuar
                                </button>
                            </div>
                        </div>
                        
                        <!-- Paso 2: Medidas Corporales -->
                        <div x-show="step === 2" style="display: none;">
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
                                            x-on:click="step = 1" 
                                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Anterior
                                    </button>
                                    <button type="button" 
                                            x-on:click="saveStep(2)" 
                                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Guardar y Continuar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Paso 3: Objetivos Fitness -->
                        <div x-show="step === 3" style="display: none;">
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
                                <button type="button" x-on:click="step = 2" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Anterior
                                </button>
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Finalizar Registro
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Modal de Éxito -->
                    <div x-show="showSuccessModal" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-90"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
                         style="display: none;">
                        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-center text-gray-900 mb-2">¡Éxito!</h3>
                            <p class="text-center text-gray-600" x-text="modalMessage"></p>
                        </div>
                    </div>
                    
                    <!-- Modal de Error -->
                    <div x-show="showErrorModal" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-90"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
                         style="display: none;">
                        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-center text-gray-900 mb-2">Error</h3>
                            <p class="text-center text-gray-600" x-text="errorMessage"></p>
                            <div class="mt-4 flex justify-center">
                                <button @click="showErrorModal = false" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 