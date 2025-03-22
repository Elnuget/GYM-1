{{-- Componente principal del formulario de registro --}}
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
        console.log('Intentando guardar el paso:', currentStep);
        
        const formElement = this.$refs.form;
        const formData = new FormData(formElement);
        formData.append('step', currentStep);
        
        // Validar campos obligatorios
        let camposFaltantes = [];
        
        // Validación por paso, independientemente de si tiene membresía activa
        if (currentStep === 1) {
            // Paso 1: Membresía
            if (!this.tieneMembresiaActiva) {
                if (!formElement.id_tipo_membresia.value) camposFaltantes.push('Tipo de Membresía');
                if (!formElement.fecha_compra.value.trim()) camposFaltantes.push('Fecha de Compra');
                if (!formElement.fecha_vencimiento.value.trim()) camposFaltantes.push('Fecha de Vencimiento');
                
                // Asegurar que el saldo pendiente sea igual al precio total
                const precioTotal = document.getElementById('precio_total').value;
                const precioDecimal = parseFloat(precioTotal).toFixed(2);
                
                // Actualizar el campo oculto y establecer saldo_pendiente como parte de la petición
                document.getElementById('saldo_pendiente').value = precioDecimal;
                document.getElementById('precio_total').value = precioDecimal;
                
                // Enviar ambos valores para asegurar que se reciban correctamente
                formData.set('precio_total', precioDecimal);
                formData.set('saldo_pendiente', precioDecimal);
            }
        } else if (currentStep === 2) {
            // Paso 2: Pago
            console.log('Validando campos del paso 2 (pago)');
            
            if (!this.tieneMembresiaActiva || this.tieneMembresiaConPagoPendiente) {
                console.log('Monto del pago:', formElement.monto_pago?.value);
                console.log('Método de pago:', formElement.id_metodo_pago?.value);
                console.log('Fecha de pago:', formElement.fecha_pago?.value);
                console.log('ID de membresía:', document.getElementById('id_membresia')?.value);
                
                if (!formElement.monto_pago?.value?.trim()) camposFaltantes.push('Monto del Pago');
                if (!formElement.id_metodo_pago?.value) camposFaltantes.push('Método de Pago');
                if (!formElement.fecha_pago?.value?.trim()) camposFaltantes.push('Fecha de Pago');
                if (!document.getElementById('id_membresia')?.value) camposFaltantes.push('ID de Membresía');
                
                // Agregar estado pendiente por defecto
                formData.append('estado', 'pendiente');
                
                // Crear un nuevo FormData solo con los campos necesarios para el paso 2 (pago)
                const pagoFormData = new FormData();
                pagoFormData.append('step', currentStep);
                pagoFormData.append('_token', '{{ csrf_token() }}');
                
                // Asegurarse de que los campos existen antes de intentar acceder a sus valores
                if (formElement.monto_pago) {
                    pagoFormData.append('monto_pago', formElement.monto_pago.value.trim());
                }
                
                if (formElement.id_metodo_pago) {
                    pagoFormData.append('id_metodo_pago', formElement.id_metodo_pago.value);
                }
                
                if (formElement.fecha_pago) {
                    pagoFormData.append('fecha_pago', formElement.fecha_pago.value.trim());
                }
                
                pagoFormData.append('estado', 'pendiente');
                
                const idMembresia = document.getElementById('id_membresia')?.value;
                if (idMembresia) {
                    pagoFormData.append('id_membresia', idMembresia);
                    console.log('ID de membresía añadido al FormData:', idMembresia);
                } else {
                    console.error('No se encontró el ID de membresía');
                }
                
                if (formElement.notas) {
                    pagoFormData.append('notas', formElement.notas.value);
                }
                
                if (formElement.comprobante && formElement.comprobante.files[0]) {
                    pagoFormData.append('comprobante', formElement.comprobante.files[0]);
                }
                
                // Usar este FormData específico para el paso 2
                formData = pagoFormData;
                console.log('FormData creado para el paso 2:', formData);
                
                // Mostrar todas las entradas en el FormData para depuración
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
            }
        } else if (currentStep === 3) {
            // Paso 3: Información Personal
            if (!formElement.fecha_nacimiento.value.trim()) camposFaltantes.push('Fecha de Nacimiento');
            if (!formElement.telefono.value.trim()) camposFaltantes.push('Teléfono');
            if (!formElement.genero.value) camposFaltantes.push('Género');
            if (!formElement.ocupacion.value.trim()) camposFaltantes.push('Ocupación');
            if (!formElement.direccion.value.trim()) camposFaltantes.push('Dirección');
        } else if (currentStep === 4) {
            // Paso 4: Medidas Corporales
            if (!formElement.peso.value.trim()) camposFaltantes.push('Peso');
            if (!formElement.altura.value.trim()) camposFaltantes.push('Altura');
        } else if (currentStep === 5) {
            // Paso 5: Objetivos Fitness
            if (!formElement.objetivo_principal.value) camposFaltantes.push('Objetivo Principal');
            if (!formElement.nivel_experiencia.value) camposFaltantes.push('Nivel de Experiencia');
            if (!formElement.dias_entrenamiento.value) camposFaltantes.push('Días de Entrenamiento');
        }
        
        if (camposFaltantes.length > 0) {
            this.errorMessage = 'Por favor, complete los campos obligatorios: ' + camposFaltantes.join(', ');
            this.showErrorModal = true;
            return;
        }
        
        console.log('Enviando datos para el paso ' + currentStep + ':', Object.fromEntries(formData));
        
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
                    
                    // Para el paso 1 (membresía), recargar la página y redireccionar al paso 2
                    if (currentStep === 1) {
                        // Guardar el paso 2 en la sesión y recargar la página
                        window.location.href = '{{ route('completar.registro.cliente.form') }}?paso=2';
                    } else if (currentStep === 5) {
                        // Es el último paso, redirigir al dashboard
                        window.location.href = '{{ route('dashboard') }}';
                    } else {
                        // Avanzar al siguiente paso normalmente sin recargar
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
            console.error('Error en la solicitud:', error);
        });
    }
}" 
@mostrar-success="showSuccessModal = true; modalMessage = $event.detail.mensaje"
@mostrar-error="showErrorModal = true; errorMessage = $event.detail.mensaje"
@avanzar-paso.window="step = $event.detail.paso"
class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
        
        <!-- Incluir el indicador de progreso -->
        @include('components.registro-cliente.progress-indicator')
        
        <form method="POST" action="{{ route('completar.registro.cliente') }}" enctype="multipart/form-data" x-ref="form" @submit.prevent="
            const formData = new FormData($event.target);
            
            // Validar campos requeridos antes de enviar
            let camposFaltantes = [];
            
            // Validación final antes de enviar
            if (!tieneMembresiaActiva) {
                if (!$event.target.id_tipo_membresia.value) camposFaltantes.push('Tipo de Membresía');
                if (!$event.target.fecha_compra.value) camposFaltantes.push('Fecha de Compra');
            }
            
            if (camposFaltantes.length > 0) {
                errorMessage = `Por favor, complete los siguientes campos obligatorios: ${camposFaltantes.join(', ')}`;
                showErrorModal = true;
                return;
            }
            
            // Indicar que es la finalización del registro
            formData.append('completion', 'true');
            
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
            @if($tieneMembresiaActiva && $tieneMembresiaConPagoPendiente)
                <input type="hidden" id="saldo_pendiente" value="{{ $membresia->saldo_pendiente }}">
            @endif
            
            <!-- Incluir los componentes de pasos -->
            @include('components.registro-cliente.steps.membresia')
            @include('components.registro-cliente.steps.pago')
            @include('components.registro-cliente.steps.informacion-personal')
            @include('components.registro-cliente.steps.medidas-corporales')
            @include('components.registro-cliente.steps.objetivos-fitness')
        </form>
        
        <!-- Incluir los modales -->
        @include('components.registro-cliente.modals.modals')
    </div>
</div> 