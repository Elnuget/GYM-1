<x-app-layout>
    @php
        // Verificar si el usuario actual tiene membresías
        $tieneMembresiaActiva = \App\Models\Membresia::where('id_usuario', auth()->id())->exists();
        
        // Verificar si tiene membresía pero con pagos pendientes
        $tieneMembresiaConPagoPendiente = false;
        if ($tieneMembresiaActiva) {
            $membresia = \App\Models\Membresia::where('id_usuario', auth()->id())->latest()->first();
            if ($membresia && $membresia->saldo_pendiente > 0) {
                // Verificar si tiene algún pago registrado
                $tienePago = \App\Models\Pago::where('id_membresia', $membresia->id_membresia)->exists();
                $tieneMembresiaConPagoPendiente = !$tienePago;
            }
        }
        
        $totalPasos = $tieneMembresiaActiva ? 3 : 5;
        $pasoInicial = $tieneMembresiaConPagoPendiente ? 1 : (session('current_step', 1));
        $subpasoInicial = $tieneMembresiaConPagoPendiente ? 2 : 1; // Si tiene membresía con pago pendiente, ir al subpaso 2 (pago)
    @endphp
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-data="{ 
                step: {{ $pasoInicial }},
                showSuccessModal: false,
                showErrorModal: false,
                modalMessage: '',
                errorMessage: '',
                formData: {},
                tieneMembresiaActiva: {{ $tieneMembresiaActiva ? 'true' : 'false' }},
                tieneMembresiaConPagoPendiente: {{ $tieneMembresiaConPagoPendiente ? 'true' : 'false' }},
                totalPasos: {{ $totalPasos }},
                subpaso: {{ $subpasoInicial }},
                
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
                    
                    if (!this.tieneMembresiaActiva) {
                        // Flujo sin membresía activa: pasos: 1 = Membresía, 2 = Pago, 3 = Info Personal, 4 = Medidas, 5 = Objetivos
                        if (currentStep === 1) {
                            if (!formElement.id_tipo_membresia.value) camposFaltantes.push('Tipo de Membresía');
                            if (!formElement.fecha_compra.value.trim()) camposFaltantes.push('Fecha de Compra');
                            if (!formElement.fecha_vencimiento.value.trim()) camposFaltantes.push('Fecha de Vencimiento');
                            
                            // Asegurar que el saldo pendiente sea igual al precio total
                            const precioTotal = document.getElementById('precio_total').value;
                            
                            // MODIFICACION IMPORTANTE: Necesitamos enviar el saldo_pendiente como parte del precio_total
                            // porque el controlador solo usa precio_total pero no saldo_pendiente
                            const precioDecimal = parseFloat(precioTotal).toFixed(2);
                            
                            // Actualizar el campo oculto y establecer saldo_pendiente como parte de la petición
                            document.getElementById('saldo_pendiente').value = precioDecimal;
                            document.getElementById('precio_total').value = precioDecimal;
                            
                            // Enviar ambos valores para asegurar que se reciban correctamente
                            formData.set('precio_total', precioDecimal);
                            
                            // Aunque el controlador no procesa este campo directamente, lo enviamos para DEBUG
                            formData.set('saldo_pendiente', precioDecimal);
                            
                            console.log('Precio total enviado:', precioDecimal);
                            console.log('Saldo pendiente enviado:', precioDecimal);
                        } else if (currentStep === 2) {
                            if (!formElement.monto_pago.value.trim()) camposFaltantes.push('Monto del Pago');
                            if (!formElement.id_metodo_pago.value) camposFaltantes.push('Método de Pago');
                            if (!formElement.fecha_pago.value.trim()) camposFaltantes.push('Fecha de Pago');
                            // Agregar estado pendiente por defecto
                            formData.append('estado', 'pendiente');
                        } else if (currentStep === 3) {
                            if (!formElement.fecha_nacimiento.value.trim()) camposFaltantes.push('Fecha de Nacimiento');
                            if (!formElement.telefono.value.trim()) camposFaltantes.push('Teléfono');
                            if (!formElement.genero.value) camposFaltantes.push('Género');
                            if (!formElement.ocupacion.value.trim()) camposFaltantes.push('Ocupación');
                            if (!formElement.direccion.value.trim()) camposFaltantes.push('Dirección');
                        } else if (currentStep === 4) {
                            if (!formElement.peso.value.trim()) camposFaltantes.push('Peso');
                            if (!formElement.altura.value.trim()) camposFaltantes.push('Altura');
                        } else if (currentStep === 5) {
                            if (!formElement.objetivo_principal.value) camposFaltantes.push('Objetivo Principal');
                            if (!formElement.nivel_experiencia.value) camposFaltantes.push('Nivel de Experiencia');
                            if (!formElement.dias_entrenamiento.value) camposFaltantes.push('Días de Entrenamiento');
                        }
                    } else {
                        // Flujo con membresía activa: pasos: 1 = Información Personal, 2 = Medidas Corporales, 3 = Objetivos Fitness
                        if (currentStep === 1) {
                            if (!formElement.fecha_nacimiento.value.trim()) camposFaltantes.push('Fecha de Nacimiento');
                            if (!formElement.telefono.value.trim()) camposFaltantes.push('Teléfono');
                            if (!formElement.genero.value) camposFaltantes.push('Género');
                            if (!formElement.ocupacion.value.trim()) camposFaltantes.push('Ocupación');
                            if (!formElement.direccion.value.trim()) camposFaltantes.push('Dirección');
                        } else if (currentStep === 2) {
                            if (!formElement.peso.value.trim()) camposFaltantes.push('Peso');
                            if (!formElement.altura.value.trim()) camposFaltantes.push('Altura');
                        } else if (currentStep === 3) {
                            if (!formElement.objetivo_principal.value) camposFaltantes.push('Objetivo Principal');
                            if (!formElement.nivel_experiencia.value) camposFaltantes.push('Nivel de Experiencia');
                            if (!formElement.dias_entrenamiento.value) camposFaltantes.push('Días de Entrenamiento');
                        }
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
                                if (this.tieneMembresiaActiva && currentStep === 3) {
                                    // Es el último paso cuando tiene membresía
                                    window.location.href = '{{ route('dashboard') }}';
                                } else if (!this.tieneMembresiaActiva) {
                                    // Avanzar al siguiente paso cuando no tiene membresía
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
                },
                
                calcularVencimiento() {
                    const selectTipo = document.getElementById('id_tipo_membresia');
                    const fechaCompra = document.getElementById('fecha_compra').value;
                    const fechaVencimiento = document.getElementById('fecha_vencimiento');
                    
                    if (selectTipo && fechaCompra) {
                        const option = selectTipo.options[selectTipo.selectedIndex];
                        
                        if (option) {
                            // Obtener el precio desde el atributo data
                            const precio = parseFloat(option.dataset.precio || 0).toFixed(2);
                            
                            // Actualizar los campos de precio y saldo pendiente
                            this.precioTotal = precio;
                            document.getElementById('precio_total').value = precio;
                            document.getElementById('saldo_pendiente').value = precio;
                            
                            console.log('Precio establecido a:', precio);
                            console.log('Saldo pendiente establecido a:', document.getElementById('saldo_pendiente').value);
                            
                            const duracion = option.dataset.duracion || 0;
                            this.nombreTipo = option.textContent || '';
                            
                            const tieneVisitas = parseInt(option.dataset.visitas) > 0;
                            const nombreContieneVisita = this.nombreTipo.toLowerCase().includes('visita');
                            this.showVisitasFields = tieneVisitas || nombreContieneVisita;
                            
                            if (this.showVisitasFields && option.dataset.visitas) {
                                document.getElementById('visitas_permitidas').value = option.dataset.visitas;
                            }
                            
                            if (fechaCompra && duracion) {
                                const date = new Date(fechaCompra);
                                date.setDate(date.getDate() + parseInt(duracion));
                                
                                const mes = (date.getMonth() + 1).toString().padStart(2, '0');
                                const dia = date.getDate().toString().padStart(2, '0');
                                fechaVencimiento.value = `${date.getFullYear()}-${mes}-${dia}`;
                            }
                        }
                    }
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
                                <template x-if="!tieneMembresiaActiva">
                                    <div class="flex items-center">
                                        <div x-bind:class="{ 'bg-emerald-500': step >= 1, 'bg-gray-300': step < 1 }" class="flex items-center justify-center w-8 h-8 rounded-full">
                                            <span class="text-white font-bold text-sm">1</span>
                                        </div>
                                        <div class="ml-2 flex-1">
                                            <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 1, 'text-gray-500': step < 1 }">Membresía</p>
                                            <div x-bind:class="{ 'bg-emerald-300': step > 1, 'bg-gray-200': step <= 1 }" class="h-1 w-full mt-2"></div>
                                        </div>
                                    </div>
                                </template>
                                <div class="flex items-center">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= (tieneMembresiaActiva ? 1 : 2), 'bg-gray-300': step < (tieneMembresiaActiva ? 1 : 2) }" class="flex items-center justify-center w-8 h-8 rounded-full">
                                        <span class="text-white font-bold text-sm" x-text="tieneMembresiaActiva ? '1' : '2'"></span>
                                    </div>
                                    <div class="ml-2 flex-1">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= (tieneMembresiaActiva ? 1 : 2), 'text-gray-500': step < (tieneMembresiaActiva ? 1 : 2) }">Información Personal</p>
                                        <div x-bind:class="{ 'bg-emerald-300': step > (tieneMembresiaActiva ? 1 : 2), 'bg-gray-200': step <= (tieneMembresiaActiva ? 1 : 2) }" class="h-1 w-full mt-2"></div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= (tieneMembresiaActiva ? 2 : 3), 'bg-gray-300': step < (tieneMembresiaActiva ? 2 : 3) }" class="flex items-center justify-center w-8 h-8 rounded-full">
                                        <span class="text-white font-bold text-sm" x-text="tieneMembresiaActiva ? '2' : '3'"></span>
                                    </div>
                                    <div class="ml-2 flex-1">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= (tieneMembresiaActiva ? 2 : 3), 'text-gray-500': step < (tieneMembresiaActiva ? 2 : 3) }">Medidas Corporales</p>
                                        <div x-bind:class="{ 'bg-emerald-300': step > (tieneMembresiaActiva ? 2 : 3), 'bg-gray-200': step <= (tieneMembresiaActiva ? 2 : 3) }" class="h-1 w-full mt-2"></div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= (tieneMembresiaActiva ? 3 : 4), 'bg-gray-300': step < (tieneMembresiaActiva ? 3 : 4) }" class="flex items-center justify-center w-8 h-8 rounded-full">
                                        <span class="text-white font-bold text-sm" x-text="tieneMembresiaActiva ? '3' : '4'"></span>
                                    </div>
                                    <div class="ml-2 flex-1">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= (tieneMembresiaActiva ? 3 : 4), 'text-gray-500': step < (tieneMembresiaActiva ? 3 : 4) }">Objetivos Fitness</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Indicador de progreso para desktop (horizontal) -->
                        <div class="hidden sm:block">
                            <div class="flex items-center">
                                <template x-if="!tieneMembresiaActiva">
                                    <div class="flex items-center relative">
                                        <div x-bind:class="{ 'bg-emerald-500': step >= 1, 'bg-gray-300': step < 1 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                                            <span class="text-white font-bold">1</span>
                                        </div>
                                        <div class="ml-2 mr-8">
                                            <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 1, 'text-gray-500': step < 1 }">Membresía</p>
                                        </div>
                                        <div x-bind:class="{ 'bg-emerald-300': step > 1, 'bg-gray-200': step <= 1 }" class="flex-1 h-1"></div>
                                    </div>
                                </template>
                                
                                <div class="flex items-center relative">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= (tieneMembresiaActiva ? 1 : 2), 'bg-gray-300': step < (tieneMembresiaActiva ? 1 : 2) }" class="flex items-center justify-center w-10 h-10 rounded-full">
                                        <span class="text-white font-bold" x-text="tieneMembresiaActiva ? '1' : '2'"></span>
                                    </div>
                                    <div class="ml-2 mr-8">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= (tieneMembresiaActiva ? 1 : 2), 'text-gray-500': step < (tieneMembresiaActiva ? 1 : 2) }">Información Personal</p>
                                    </div>
                                    <div x-bind:class="{ 'bg-emerald-300': step > (tieneMembresiaActiva ? 1 : 2), 'bg-gray-200': step <= (tieneMembresiaActiva ? 1 : 2) }" class="flex-1 h-1"></div>
                                </div>
                                
                                <div class="flex items-center relative">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= (tieneMembresiaActiva ? 2 : 3), 'bg-gray-300': step < (tieneMembresiaActiva ? 2 : 3) }" class="flex items-center justify-center w-10 h-10 rounded-full">
                                        <span class="text-white font-bold" x-text="tieneMembresiaActiva ? '2' : '3'"></span>
                                    </div>
                                    <div class="ml-2 mr-8">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= (tieneMembresiaActiva ? 2 : 3), 'text-gray-500': step < (tieneMembresiaActiva ? 2 : 3) }">Medidas Corporales</p>
                                    </div>
                                    <div x-bind:class="{ 'bg-emerald-300': step > (tieneMembresiaActiva ? 2 : 3), 'bg-gray-200': step <= (tieneMembresiaActiva ? 2 : 3) }" class="flex-1 h-1"></div>
                                </div>
                                
                                <div class="flex items-center relative">
                                    <div x-bind:class="{ 'bg-emerald-500': step >= (tieneMembresiaActiva ? 3 : 4), 'bg-gray-300': step < (tieneMembresiaActiva ? 3 : 4) }" class="flex items-center justify-center w-10 h-10 rounded-full">
                                        <span class="text-white font-bold" x-text="tieneMembresiaActiva ? '3' : '4'"></span>
                                    </div>
                                    <div class="ml-2">
                                        <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= (tieneMembresiaActiva ? 3 : 4), 'text-gray-500': step < (tieneMembresiaActiva ? 3 : 4) }">Objetivos Fitness</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('completar.registro.cliente') }}" enctype="multipart/form-data" x-ref="form" @submit.prevent="
                        const formData = new FormData($event.target);
                        
                        // Validar campos requeridos antes de enviar
                        let camposFaltantes = [];
                        
                        if (step === 4 && !tieneMembresiaActiva) {
                            if (!$event.target.id_tipo_membresia.value) camposFaltantes.push('Tipo de Membresía');
                            if (!$event.target.fecha_compra.value) camposFaltantes.push('Fecha de Compra');
                        }
                        
                        if (camposFaltantes.length > 0) {
                            errorMessage = `Por favor, complete los siguientes campos obligatorios: ${camposFaltantes.join(', ')}`;
                            showErrorModal = true;
                            return;
                        }
                        
                        // Si tiene membresía activa y está en el paso 3, ya está completando el registro
                        if (tieneMembresiaActiva && step === 3) {
                            formData.append('completion', 'true');
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
                        <input type="hidden" name="tiene_membresia" value="{{ $tieneMembresiaActiva ? '1' : '0' }}">
                        
                        <!-- Paso 1: Membresía (solo si no tiene membresía activa) -->
                        <div x-show="!tieneMembresiaActiva && step === 1" style="display: none;">
                            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Nueva Membresía</h2>
                            
                            <div class="mb-6">
                                <!-- Indicador de pasos para la membresía -->
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full" 
                                         :class="{'bg-emerald-500': subpaso === 1, 'bg-gray-300': subpaso === 2}">
                                        <span class="text-white font-bold text-sm">1</span>
                                    </div>
                                    <div class="ml-2" :class="{'text-emerald-500 font-medium': subpaso === 1, 'text-gray-500': subpaso === 2}">MEMBRESÍA</div>
                                    <div class="mx-4 h-1 w-24" :class="{'bg-emerald-500': subpaso === 2, 'bg-gray-200': subpaso === 1}"></div>
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full" 
                                         :class="{'bg-emerald-500': subpaso === 2, 'bg-gray-300': subpaso === 1}">
                                        <span class="text-white font-bold text-sm">2</span>
                                    </div>
                                    <div class="ml-2" :class="{'text-emerald-500 font-medium': subpaso === 2, 'text-gray-500': subpaso === 1}">PAGO</div>
                                </div>
                            </div>
                            
                            <!-- Subpaso 1: Selección de membresía -->
                            <div x-show="subpaso === 1 && !tieneMembresiaConPagoPendiente" x-transition style="display: none;">
                                <div class="grid grid-cols-1 gap-6" x-data="{
                                    precioTotal: 0,
                                    nombreTipo: '',
                                    showVisitasFields: false,
                                    
                                    calcularVencimiento() {
                                        const selectTipo = document.getElementById('id_tipo_membresia');
                                        const fechaCompra = document.getElementById('fecha_compra').value;
                                        const fechaVencimiento = document.getElementById('fecha_vencimiento');
                                        
                                        if (selectTipo && fechaCompra) {
                                            const option = selectTipo.options[selectTipo.selectedIndex];
                                            
                                            if (option) {
                                                // Obtener el precio desde el atributo data
                                                const precio = parseFloat(option.dataset.precio || 0).toFixed(2);
                                                
                                                // Actualizar los campos de precio y saldo pendiente
                                                this.precioTotal = precio;
                                                document.getElementById('precio_total').value = precio;
                                                document.getElementById('saldo_pendiente').value = precio;
                                                
                                                console.log('Precio establecido a:', precio);
                                                console.log('Saldo pendiente establecido a:', document.getElementById('saldo_pendiente').value);
                                                
                                                const duracion = option.dataset.duracion || 0;
                                                this.nombreTipo = option.textContent || '';
                                                
                                                const tieneVisitas = parseInt(option.dataset.visitas) > 0;
                                                const nombreContieneVisita = this.nombreTipo.toLowerCase().includes('visita');
                                                this.showVisitasFields = tieneVisitas || nombreContieneVisita;
                                                
                                                if (this.showVisitasFields && option.dataset.visitas) {
                                                    document.getElementById('visitas_permitidas').value = option.dataset.visitas;
                                                }
                                                
                                                if (fechaCompra && duracion) {
                                                    const date = new Date(fechaCompra);
                                                    date.setDate(date.getDate() + parseInt(duracion));
                                                    
                                                    const mes = (date.getMonth() + 1).toString().padStart(2, '0');
                                                    const dia = date.getDate().toString().padStart(2, '0');
                                                    fechaVencimiento.value = `${date.getFullYear()}-${mes}-${dia}`;
                                                }
                                            }
                                        }
                                    }
                                }">
                                    <!-- Contenido del paso de membresía -->
                                    <input type="hidden" name="id_usuario" value="{{ auth()->id() }}">
                                    <input type="hidden" id="saldo_pendiente" name="saldo_pendiente" value="0">
                                    
                                    <div>
                                        <x-input-label for="id_tipo_membresia" :value="__('Tipo de Membresía')" class="mb-1 text-sm font-medium text-gray-700" />
                                        <select id="id_tipo_membresia" name="id_tipo_membresia" required @change="calcularVencimiento" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="" selected disabled>Selecciona un tipo</option>
                                            @foreach(\App\Models\TipoMembresia::all() as $tipo)
                                                <option value="{{ $tipo->id_tipo_membresia }}" 
                                                        data-precio="{{ $tipo->precio }}"
                                                        data-duracion="{{ $tipo->duracion_dias }}"
                                                        data-visitas="{{ $tipo->numero_visitas }}"
                                                        {{ old('id_tipo_membresia') == $tipo->id_tipo_membresia ? 'selected' : '' }}>
                                                    {{ $tipo->nombre }} - ${{ number_format($tipo->precio, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('id_tipo_membresia')" class="mt-1" />
                                    </div>
                                    
                                    <!-- Precio Total -->
                                    <div>
                                        <x-input-label for="precio_total" :value="__('Precio Total')" class="mb-1 text-sm font-medium text-gray-700" />
                                        <x-text-input id="precio_total" class="block w-full bg-gray-100" type="number" step="0.01" name="precio_total" value="0" readonly />
                                        <x-input-error :messages="$errors->get('precio_total')" class="mt-1" />
                                    </div>
                                    
                                    <!-- Fecha de Compra -->
                                    <div>
                                        <x-input-label for="fecha_compra" :value="__('Fecha de Compra')" class="mb-1 text-sm font-medium text-gray-700" />
                                        <x-text-input id="fecha_compra" class="block w-full" type="date" name="fecha_compra" :value="old('fecha_compra', date('Y-m-d'))" @change="calcularVencimiento" required />
                                        <x-input-error :messages="$errors->get('fecha_compra')" class="mt-1" />
                                    </div>
                                    
                                    <!-- Fecha de Vencimiento -->
                                    <div>
                                        <x-input-label for="fecha_vencimiento" :value="__('Fecha de Vencimiento')" class="mb-1 text-sm font-medium text-gray-700" />
                                        <x-text-input id="fecha_vencimiento" class="block w-full" type="date" name="fecha_vencimiento" :value="old('fecha_vencimiento', date('Y-m-d'))" required />
                                        <x-input-error :messages="$errors->get('fecha_vencimiento')" class="mt-1" />
                                    </div>
                                    
                                    <!-- Visitas Permitidas - Solo visible para membresías por visitas -->
                                    <div x-show="showVisitasFields">
                                        <x-input-label for="visitas_permitidas" :value="__('Visitas Permitidas')" class="mb-1 text-sm font-medium text-gray-700" />
                                        <x-text-input id="visitas_permitidas" class="block w-full" type="number" name="visitas_permitidas" :value="old('visitas_permitidas')" min="1" />
                                        <x-input-error :messages="$errors->get('visitas_permitidas')" class="mt-1" />
                                    </div>
                                    
                                    <!-- Renovación -->
                                    <div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="renovacion" name="renovacion" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" {{ old('renovacion') ? 'checked' : '' }} checked>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="renovacion" class="font-medium text-gray-700">Renovación</label>
                                                <p class="text-gray-500">Marcar si es una renovación de membresía</p>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('renovacion')" class="mt-1" />
                                    </div>
                                </div>
                                
                                <div class="flex justify-between mt-8">
                                    <button type="button" @click="subpaso = 1" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium" 
                                            x-show="!tieneMembresiaConPagoPendiente">
                                        Anterior
                                    </button>
                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md opacity-50 cursor-not-allowed" 
                                            x-show="tieneMembresiaConPagoPendiente" disabled>
                                        Anterior
                                    </button>
                                    <button type="button" x-on:click="saveStep(1)" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
                                        Guardar y Continuar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Paso 2: Pago (solo si no tiene membresía activa) -->
                        <div x-show="(!tieneMembresiaActiva && step === 2) || (tieneMembresiaConPagoPendiente && step === 1)" style="display: none;" x-data="{
                            montoPendiente: '',
                            
                            init() {
                                // Establecer el monto pendiente igual al precio total
                                this.montoPendiente = document.getElementById('precio_total')?.value || '0';
                                if (document.getElementById('monto_pago')) {
                                    document.getElementById('monto_pago').value = this.montoPendiente;
                                }
                            }
                        }">
                            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Pago de Membresía</h2>
                            
                            <div x-show="tieneMembresiaConPagoPendiente" class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Tienes una membresía pendiente de pago</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Se ha detectado que tienes una membresía sin pagos registrados. Por favor, completa el proceso de pago para continuar.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <!-- Indicador de pasos para la membresía -->
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500">
                                        <span class="text-white font-bold text-sm">1</span>
                                    </div>
                                    <div class="ml-2 text-emerald-500 font-medium">MEMBRESÍA</div>
                                    <div class="mx-4 h-1 w-24 bg-emerald-500"></div>
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500">
                                        <span class="text-white font-bold text-sm">2</span>
                                    </div>
                                    <div class="ml-2 text-emerald-500">PAGO</div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Campo oculto para estado -->
                                <input type="hidden" name="estado" value="pendiente">
                                
                                <!-- Monto del Pago -->
                                <div>
                                    <x-input-label for="monto_pago" :value="__('Monto del Pago *')" class="mb-1 text-sm font-medium text-gray-700" />
                                    <x-text-input id="monto_pago" class="block w-full" type="number" step="0.01" name="monto_pago" :value="old('monto_pago')" required x-model="montoPendiente" />
                                    <p class="mt-1 text-sm text-gray-500">Este es el monto pendiente de la membresía seleccionada.</p>
                                    <x-input-error :messages="$errors->get('monto_pago')" class="mt-1" />
                                </div>

                                <!-- Método de Pago -->
                                <div>
                                    <x-input-label for="metodo_pago" :value="__('Método de Pago *')" class="mb-1 text-sm font-medium text-gray-700" />
                                    <select id="metodo_pago" name="id_metodo_pago" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                        <option value="" selected disabled>Selecciona un método de pago</option>
                                        <option value="1">Efectivo</option>
                                        <option value="2">Tarjeta de Crédito/Débito</option>
                                        <option value="3">Transferencia Bancaria</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('id_metodo_pago')" class="mt-1" />
                                </div>

                                <!-- Fecha de Pago -->
                                <div>
                                    <x-input-label for="fecha_pago" :value="__('Fecha de Pago *')" class="mb-1 text-sm font-medium text-gray-700" />
                                    <x-text-input id="fecha_pago" class="block w-full" type="date" name="fecha_pago" :value="old('fecha_pago', date('Y-m-d'))" required />
                                    <x-input-error :messages="$errors->get('fecha_pago')" class="mt-1" />
                                </div>
                                
                                <!-- Comprobante de Pago -->
                                <div>
                                    <x-input-label for="comprobante" :value="__('Comprobante de Pago (opcional)')" class="mb-1 text-sm font-medium text-gray-700" />
                                    <input type="file" 
                                          id="comprobante" 
                                          name="comprobante" 
                                          class="mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" 
                                          accept="image/*,.pdf">
                                    <p class="mt-1 text-sm text-gray-500">Puede subir una imagen o PDF del comprobante de pago (opcional).</p>
                                    <x-input-error :messages="$errors->get('comprobante')" class="mt-1" />
                                </div>

                                <!-- Observaciones -->
                                <div>
                                    <x-input-label for="notas" :value="__('Observaciones')" class="mb-1 text-sm font-medium text-gray-700" />
                                    <textarea id="notas" name="notas" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Observaciones adicionales sobre el pago">{{ old('notas') }}</textarea>
                                    <x-input-error :messages="$errors->get('notas')" class="mt-1" />
                                </div>
                            </div>
                            
                            <div class="flex justify-between mt-8">
                                <button type="button" @click="step = 1" x-show="!tieneMembresiaConPagoPendiente" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium">
                                    Anterior
                                </button>
                                <button type="button" disabled x-show="tieneMembresiaConPagoPendiente" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md opacity-50 cursor-not-allowed">
                                    Anterior
                                </button>
                                <button type="button" x-on:click="saveStep(2)" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 font-medium">
                                    Guardar y Continuar
                                </button>
                            </div>
                        </div>
                        
                        <!-- Paso 3/2: Medidas Corporales -->
                        <div x-show="step === (tieneMembresiaActiva ? 2 : 3)" style="display: none;">
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
                                            x-on:click="saveStep(3)" 
                                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Guardar y Continuar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Paso 4/3: Objetivos Fitness -->
                        <div x-show="step === (tieneMembresiaActiva ? 3 : 4)" style="display: none;">
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
                                <button type="button" x-on:click="saveStep(4)" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <span x-show="!tieneMembresiaActiva">Guardar y Continuar</span>
                                    <span x-show="tieneMembresiaActiva">Completar Registro</span>
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