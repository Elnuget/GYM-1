<x-app-layout>
    <div x-data="{ 
        isModalOpen: false,
        isEditModalOpen: false,
        isViewModalOpen: false,
        currentCliente: null,
        showNotificationModal: false,
        notificationType: '',
        notificationMessage: '',
        tablaMembresiaAbierta: false,
        tablaPagosAbierta: false,
        tablaAsistenciasAbierta: false,
        searchTerm: '',
        filteredClientes: [],
        activeFilterLabel: '',
        
        formatearFecha(fecha) {
            if (!fecha) return 'N/A';
            try {
                // Intentar crear objeto Date a partir de la fecha
                const fechaObj = new Date(fecha);
                // Verificar si la fecha es válida
                if (!isNaN(fechaObj.getTime())) {
                    // Formatear a DD/MM/YYYY
                    return fechaObj.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                }
                return fecha;
            } catch (e) {
                console.error('Error al formatear fecha:', e);
                return fecha;
            }
        },
        
        init() {
            // Inicializar la lista filtrada con todos los clientes al cargar
            this.filteredClientes = [...document.querySelectorAll('tr[data-cliente-id]')];
            
            // Observador para filtrar clientes en tiempo real al escribir
            this.$watch('searchTerm', (value) => {
                if (this.activeFilterLabel) {
                    // Si hay un filtro activo, no hacer nada - el usuario debe quitar el filtro primero
                    return;
                }
                
                if (value.trim() === '') {
                    // Si el término de búsqueda está vacío, mostrar todos los clientes
                    this.filteredClientes = [...document.querySelectorAll('tr[data-cliente-id]')];
                    this.filteredClientes.forEach(row => {
                        row.classList.remove('hidden');
                    });
                } else {
                    // Filtrar clientes según el término de búsqueda (case insensitive)
                    const searchTermLower = value.toLowerCase();
                    const allRows = [...document.querySelectorAll('tr[data-cliente-id]')];
                    
                    allRows.forEach(row => {
                        const clienteNombre = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const clienteTelefono = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                        const clienteGimnasio = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                        
                        // Buscar en nombre, teléfono y gimnasio
                        if (clienteNombre.includes(searchTermLower) || 
                            clienteTelefono.includes(searchTermLower) || 
                            clienteGimnasio.includes(searchTermLower)) {
                            row.classList.remove('hidden');
                        } else {
                            row.classList.add('hidden');
                        }
                    });
                    
                    // Actualizar la lista filtrada
                    this.filteredClientes = allRows.filter(row => !row.classList.contains('hidden'));
                }
            });
        },
        
        // Métodos para aplicar filtros especiales
        applyFilter(filterType) {
            // Limpiar la búsqueda por texto
            this.searchTerm = '';
            
            const allRows = [...document.querySelectorAll('tr[data-cliente-id]')];
            const mesActual = new Date().getMonth(); // 0-11
            const añoActual = new Date().getFullYear();
            
            allRows.forEach(row => {
                // Ocultar todas las filas por defecto
                row.classList.add('hidden');
                
                if (filterType === 'nuevos') {
                    // Clientes Nuevos del Mes
                    const fechaRegistro = row.getAttribute('data-fecha-registro');
                    if (fechaRegistro) {
                        const fecha = new Date(fechaRegistro);
                        if (fecha.getMonth() === mesActual && fecha.getFullYear() === añoActual) {
                            row.classList.remove('hidden');
                        }
                    }
                    this.activeFilterLabel = 'Clientes Nuevos del Mes';
                    
                } else if (filterType === 'instalaciones') {
                    // Clientes en Instalaciones (con entrada pero sin salida)
                    if (row.getAttribute('data-en-instalaciones') === 'true') {
                        row.classList.remove('hidden');
                    }
                    this.activeFilterLabel = 'Clientes Actualmente en Instalaciones';
                    
                } else if (filterType === 'vencidas') {
                    // Clientes con Membresías Vencidas
                    if (row.getAttribute('data-membresia-vencida') === 'true') {
                        row.classList.remove('hidden');
                    }
                    this.activeFilterLabel = 'Clientes con Membresía Vencida';
                    
                } else if (filterType === 'pendientes') {
                    // Clientes con Pagos Pendientes
                    if (row.getAttribute('data-pagos-pendientes') === 'true') {
                        row.classList.remove('hidden');
                    }
                    this.activeFilterLabel = 'Clientes con Pagos Pendientes';
                }
            });
            
            // Actualizar la lista filtrada
            this.filteredClientes = allRows.filter(row => !row.classList.contains('hidden'));
        },
        
        clearFilter() {
            // Quitar filtro activo y mostrar todos los clientes
            this.activeFilterLabel = '';
            this.searchTerm = '';
            
            const allRows = [...document.querySelectorAll('tr[data-cliente-id]')];
            allRows.forEach(row => {
                row.classList.remove('hidden');
            });
            this.filteredClientes = allRows;
        },
        
        toggleModal() {
            this.isModalOpen = !this.isModalOpen;
        },
        toggleEditModal(cliente = null) {
            this.isEditModalOpen = !this.isEditModalOpen;
            this.currentCliente = cliente;
            if(cliente) {
                console.log('Fecha de nacimiento original:', cliente.fecha_nacimiento);
                
                this.$nextTick(() => {
                    document.getElementById('edit_gimnasio_id').value = cliente.gimnasio_id;
                    document.getElementById('edit_nombre').value = cliente.nombre;
                    document.getElementById('edit_email').value = cliente.email;
                    document.getElementById('edit_telefono').value = cliente.telefono || '';
                    
                    // Formatear correctamente la fecha de nacimiento
                    const fechaNacimientoInput = document.getElementById('edit_fecha_nacimiento');
                    if (cliente.fecha_nacimiento) {
                        let fechaNacimiento = cliente.fecha_nacimiento;
                        console.log('Procesando fecha:', fechaNacimiento);
                        
                        try {
                            // Convertir a objeto Date para normalizar el formato
                            let fechaObj;
                            
                            // Determinar el formato de fecha de entrada
                            if (fechaNacimiento.includes('/')) {
                                const [dia, mes, anio] = fechaNacimiento.split('/');
                                fechaObj = new Date(`${anio}-${mes}-${dia}`);
                            } else if (fechaNacimiento.includes('-')) {
                                // Podría ser YYYY-MM-DD o DD-MM-YYYY
                                const partes = fechaNacimiento.split('-');
                                if (partes.length === 3) {
                                    if (partes[0].length === 4) {
                                        // Ya está en formato YYYY-MM-DD
                                        fechaObj = new Date(fechaNacimiento);
                                    } else {
                                        // Formato DD-MM-YYYY
                                        fechaObj = new Date(`${partes[2]}-${partes[1]}-${partes[0]}`);
                                    }
                                }
                            } else {
                                // Intentar parsear como timestamp o cualquier otro formato
                                fechaObj = new Date(fechaNacimiento);
                            }
                            
                            // Verificar si la fecha es válida
                            if (!isNaN(fechaObj.getTime())) {
                                // Formatear a YYYY-MM-DD para el input date
                                const fechaFormateada = fechaObj.toISOString().split('T')[0];
                                console.log('Fecha formateada:', fechaFormateada);
                                
                                // Cambiar temporalmente a tipo date para establecer el valor
                                fechaNacimientoInput.type = 'date';
                                fechaNacimientoInput.value = fechaFormateada;
                            } else {
                                console.log('Fecha inválida:', fechaNacimiento);
                                fechaNacimientoInput.value = '';
                            }
                        } catch (e) {
                            console.error('Error al procesar la fecha:', e);
                            fechaNacimientoInput.value = '';
                        }
                    } else {
                        console.log('No hay fecha de nacimiento');
                        fechaNacimientoInput.value = '';
                    }
                    
                    document.getElementById('edit_genero').value = cliente.genero || '';
                    document.getElementById('edit_direccion').value = cliente.direccion || '';
                });
            }
        },
        toggleViewModal(cliente = null) {
            this.isViewModalOpen = !this.isViewModalOpen;
            this.currentCliente = cliente;
        },
        deleteCliente(clienteId, clienteNombre) {
            if (confirm('¿Está seguro de eliminar a ' + clienteNombre + '?')) {
                const csrfToken = document.querySelector('meta[name=csrf-token]').content;
                
                fetch(`/clientes/${clienteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    this.showNotificationModal = true;
                    if (data.success) {
                        this.notificationType = 'success';
                        this.notificationMessage = data.message || 'Cliente eliminado exitosamente';
                        
                        // Remover la fila de la tabla
                        document.querySelector(`tr[data-cliente-id='${clienteId}']`).remove();
                    } else {
                        this.notificationType = 'error';
                        this.notificationMessage = data.message || 'Error al eliminar el cliente';
                    }
                })
                .catch(error => {
                    this.showNotificationModal = true;
                    this.notificationType = 'error';
                    this.notificationMessage = 'Error de conexión: ' + error.message;
                });
            }
        }
    }">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Tarjetas informativas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Clientes Nuevos del Mes -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-emerald-500">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-emerald-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Clientes Nuevos del Mes
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            @php
                                                $mesActual = \Carbon\Carbon::now()->month;
                                                $añoActual = \Carbon\Carbon::now()->year;
                                                $clientesNuevosMes = $clientes->filter(function($cliente) use ($mesActual, $añoActual) {
                                                    $fechaCreacion = \Carbon\Carbon::parse($cliente->created_at);
                                                    return $fechaCreacion->month === $mesActual && $fechaCreacion->year === $añoActual;
                                                })->count();
                                            @endphp
                                            {{ $clientesNuevosMes }}
                                        </div>
                                    </dd>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button @click="applyFilter('nuevos')" class="w-full bg-emerald-100 hover:bg-emerald-200 text-emerald-800 text-sm font-medium py-2 px-4 rounded-md transition-colors duration-150">
                                    Ver todos
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Clientes en Instalaciones -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-500">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Clientes en Instalaciones
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            @php
                                                $clientesEnInstalaciones = App\Models\Asistencia::whereDate('fecha', \Carbon\Carbon::today())
                                                    ->whereNull('hora_salida')
                                                    ->count();
                                            @endphp
                                            {{ $clientesEnInstalaciones }}
                                        </div>
                                    </dd>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button @click="applyFilter('instalaciones')" class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium py-2 px-4 rounded-md transition-colors duration-150">
                                    Ver todos
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Membresías Vencidas -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-amber-500">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-amber-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Membresías Vencidas
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            @php
                                                $membresiasVencidas = 0;
                                                if (isset($todasLasMembresias)) {
                                                    $membresiasVencidas = $todasLasMembresias->filter(function($membresia) {
                                                        $hoy = \Carbon\Carbon::now();
                                                        return isset($membresia->fecha_vencimiento) && 
                                                            \Carbon\Carbon::parse($membresia->fecha_vencimiento)->lt($hoy);
                                                    })->groupBy('usuario_id')->count();
                                                }
                                            @endphp
                                            {{ $membresiasVencidas }}
                                        </div>
                                    </dd>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button @click="applyFilter('vencidas')" class="w-full bg-amber-100 hover:bg-amber-200 text-amber-800 text-sm font-medium py-2 px-4 rounded-md transition-colors duration-150">
                                    Ver todos
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pagos Pendientes -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-red-500">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Pagos Pendientes
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            @php
                                                $clientesConPagosPendientes = 0;
                                                if (isset($todasLasMembresias)) {
                                                    $clientesConPagosPendientes = $todasLasMembresias->filter(function($membresia) {
                                                        return $membresia->saldo_pendiente > 0;
                                                    })->groupBy('usuario_id')->count();
                                                }
                                            @endphp
                                            {{ $clientesConPagosPendientes }}
                                        </div>
                                    </dd>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button @click="applyFilter('pendientes')" class="w-full bg-red-100 hover:bg-red-200 text-red-800 text-sm font-medium py-2 px-4 rounded-md transition-colors duration-150">
                                    Ver todos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cuadro de búsqueda -->
                <div class="mb-6">
                    <!-- Indicador de filtro activo -->
                    <div x-show="activeFilterLabel" class="mb-4 flex items-center justify-between bg-blue-50 border border-blue-200 p-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="font-medium text-blue-800" x-text="'Filtro activo: ' + activeFilterLabel"></span>
                        </div>
                        <button @click="clearFilter()" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Quitar filtro
                        </button>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input 
                            type="search" 
                            x-model="searchTerm"
                            :disabled="activeFilterLabel !== ''"
                            class="block w-full p-3 pl-10 pr-4 text-sm text-gray-900 border border-emerald-200 rounded-lg bg-white focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-50 disabled:text-gray-500"
                            placeholder="Buscar clientes por nombre, teléfono o gimnasio..."
                        >
                    </div>
                    <p class="mt-2 text-sm text-emerald-600" x-text="searchTerm.trim() !== '' ? `Mostrando ${filteredClientes.length} resultado(s) para '${searchTerm}'` : ''"></p>
                </div>

                <!-- Header con gradiente -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div class="flex items-center">
                            <h2 class="text-2xl font-semibold text-white mr-2" x-text="activeFilterLabel || 'Clientes'">
                                Clientes
                            </h2>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($gimnasios as $gimnasio)
                                <span class="px-3 py-1 text-sm bg-white bg-opacity-20 rounded-full text-white">
                                    {{ $gimnasio->nombre }}: {{ $clientes->where('gimnasio_id', $gimnasio->id_gimnasio)->count() }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <button @click.stop="toggleModal()" 
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="whitespace-nowrap">Añadir Cliente</span>
                    </button>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                

                <!-- Tabla -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100 mb-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-emerald-200">
                            <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Foto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Teléfono</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Gimnasio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Membresías</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Asistencias</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-emerald-100">
                                @foreach($clientes->sortByDesc('id_cliente') as $cliente)
                                    @php
                                        // Determinar si el cliente está actualmente en las instalaciones
                                        $enInstalaciones = App\Models\Asistencia::where('cliente_id', $cliente->id_cliente)
                                            ->whereDate('fecha', \Carbon\Carbon::today())
                                            ->whereNull('hora_salida')
                                            ->exists();
                                        
                                        // Determinar si el cliente tiene membresía vencida
                                        $tieneMembresiasVencidas = false;
                                        $tienePagosPendientes = false;
                                        
                                        if (isset($todasLasMembresias)) {
                                            $membresiasCliente = $todasLasMembresias->filter(function($membresia) use ($cliente) {
                                                return isset($membresia->usuario) && 
                                                      (strtolower($membresia->usuario->name) == strtolower($cliente->nombre) || 
                                                       (isset($cliente->user) && isset($cliente->user->id) && 
                                                        isset($membresia->usuario->id) && 
                                                        $membresia->usuario->id == $cliente->user->id));
                                            });
                                            
                                            $tieneMembresiasVencidas = $membresiasCliente->filter(function($membresia) {
                                                $hoy = \Carbon\Carbon::now();
                                                return isset($membresia->fecha_vencimiento) && 
                                                      \Carbon\Carbon::parse($membresia->fecha_vencimiento)->lt($hoy);
                                            })->count() > 0;
                                            
                                            $tienePagosPendientes = $membresiasCliente->sum('saldo_pendiente') > 0;
                                        }
                                    @endphp
                                    <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150"
                                        data-cliente-id="{{ $cliente->id_cliente }}"
                                        data-fecha-registro="{{ $cliente->created_at }}"
                                        data-en-instalaciones="{{ $enInstalaciones ? 'true' : 'false' }}"
                                        data-membresia-vencida="{{ $tieneMembresiasVencidas ? 'true' : 'false' }}"
                                        data-pagos-pendientes="{{ $tienePagosPendientes ? 'true' : 'false' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                @if($cliente->user && $cliente->user->foto_perfil && file_exists(public_path($cliente->user->foto_perfil)))
                                                    <img src="{{ asset($cliente->user->foto_perfil) }}" alt="{{ $cliente->nombre }}" 
                                                         class="h-10 w-10 rounded-full object-cover border-2 border-emerald-200">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-emerald-600 flex items-center justify-center">
                                                        <span class="text-sm font-bold text-white">
                                                            {{ substr($cliente->nombre, 0, 1) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->telefono ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->gimnasio->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                // Verificar si existe la colección de membresías
                                                if (!isset($todasLasMembresias)) {
                                                    $todasLasMembresias = collect([]);
                                                }
                                                
                                                // Intentar obtener las membresías por nombre del cliente
                                                $membresiasCliente = $todasLasMembresias->filter(function($membresia) use ($cliente) {
                                                    // Verificar si el nombre del usuario coincide con el nombre del cliente
                                                    return isset($membresia->usuario) && 
                                                           (strtolower($membresia->usuario->name) == strtolower($cliente->nombre) || 
                                                            (isset($cliente->user) && isset($cliente->user->id) && 
                                                             isset($membresia->usuario->id) && 
                                                             $membresia->usuario->id == $cliente->user->id));
                                                });
                                                
                                                $totalMembresias = $membresiasCliente->count();
                                                $membresiasActivas = $membresiasCliente->filter(function($membresia) {
                                                    $hoy = \Carbon\Carbon::now();
                                                    return isset($membresia->fecha_vencimiento) && 
                                                           \Carbon\Carbon::parse($membresia->fecha_vencimiento)->gte($hoy);
                                                })->count();
                                                
                                                $membresiasVencidas = $membresiasCliente->filter(function($membresia) {
                                                    $hoy = \Carbon\Carbon::now();
                                                    return isset($membresia->fecha_vencimiento) && 
                                                           \Carbon\Carbon::parse($membresia->fecha_vencimiento)->lt($hoy);
                                                })->count();
                                                
                                                $saldosPendientes = $membresiasCliente->sum('saldo_pendiente');
                                            @endphp
                                            
                                            <div class="flex flex-col space-y-2">
                                                <div class="bg-emerald-600 text-white text-xs font-bold py-1 px-2 rounded-t-md">
                                                    MEMBRESÍAS
                                                </div>
                                                
                                                @if($totalMembresias > 0)
                                                    <div class="flex flex-col space-y-1">
                                                        @if($membresiasActivas > 0)
                                                            <a href="{{ route('membresias.activas', ['id_usuario' => $cliente->user->id ?? 0]) }}" 
                                                               class="flex items-center bg-green-500 text-white text-xs font-semibold py-1 px-2 rounded-md hover:bg-green-600 transition-colors">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                                {{ $membresiasActivas }} Activas
                                                            </a>
                                                        @endif
                                                        
                                                        @if($membresiasVencidas > 0)
                                                            <a href="{{ route('membresias.vencidas', ['id_usuario' => $cliente->user->id ?? 0]) }}" 
                                                               class="flex items-center bg-red-500 text-white text-xs font-semibold py-1 px-2 rounded-md hover:bg-red-600 transition-colors">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                {{ $membresiasVencidas }} Vencidas
                                                            </a>
                                                        @endif
                                                        
                                                        @if($saldosPendientes > 0)
                                                            <a href="{{ route('membresias.saldos-pendientes', ['id_usuario' => $cliente->user->id ?? 0]) }}" 
                                                               class="flex items-center bg-amber-500 text-white text-xs font-semibold py-1 px-2 rounded-md hover:bg-amber-600 transition-colors">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                ${{ number_format($saldosPendientes, 2) }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="text-xs text-gray-500 border-t border-gray-200 pt-1">
                                                        Total: {{ $totalMembresias }} membresía(s)
                                                    </div>
                                                @else
                                                    <div class="text-gray-500 text-xs py-1">
                                                        Sin membresías
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $totalAsistencias = App\Models\Asistencia::where('cliente_id', $cliente->id_cliente)->count();
                                                $ultimaAsistencia = App\Models\Asistencia::where('cliente_id', $cliente->id_cliente)
                                                    ->orderBy('fecha', 'desc')
                                                    ->orderBy('hora_entrada', 'desc')
                                                    ->first();
                                            @endphp
                                            
                                            <div class="flex flex-col space-y-2">
                                                <div class="bg-emerald-600 text-white text-xs font-bold py-1 px-2 rounded-t-md">
                                                    ASISTENCIAS: {{ $totalAsistencias }}
                                                </div>
                                                
                                                @if($ultimaAsistencia)
                                                    <div class="text-xs space-y-1">
                                                        <div class="font-semibold text-gray-600">Última visita:</div>
                                                        <div class="bg-gray-50 p-2 rounded-md">
                                                            <div class="text-emerald-600">
                                                                {{ \Carbon\Carbon::parse($ultimaAsistencia->fecha)->format('d/m/Y') }}
                                                            </div>
                                                            <div class="flex items-center justify-between text-gray-500">
                                                                <span>Entrada: {{ \Carbon\Carbon::parse($ultimaAsistencia->hora_entrada)->format('H:i') }}</span>
                                                                @if($ultimaAsistencia->hora_salida)
                                                                    <span>Salida: {{ \Carbon\Carbon::parse($ultimaAsistencia->hora_salida)->format('H:i') }}</span>
                                                                @else
                                                                    <span class="text-orange-500">En curso</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-xs text-gray-500 py-1">
                                                        Sin asistencias registradas
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <button @click="toggleViewModal({{ $cliente->toJson() }})" 
                                                        class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-100 transition-colors duration-150"
                                                        title="Ver detalles">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button @click="toggleEditModal({{ $cliente->toJson() }})" 
                                                        class="text-teal-600 hover:text-teal-900 p-1 rounded-full hover:bg-teal-100 transition-colors duration-150"
                                                        title="Editar cliente">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('membresias.renovar', ['id_usuario' => $cliente->user->id ?? 0]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-purple-600 hover:text-purple-900 p-1 rounded-full hover:bg-purple-100 transition-colors duration-150"
                                                            title="Renovar última membresía"
                                                            onclick="return confirm('¿Está seguro de renovar la última membresía de {{ $cliente->nombre }}?')">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <button @click="deleteCliente({{ $cliente->id_cliente }}, '{{ $cliente->nombre }}')" 
                                                        class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100 transition-colors duration-150"
                                                        title="Eliminar cliente">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                                
                                                @php
                                                    $asistenciaActiva = App\Models\Asistencia::where('cliente_id', $cliente->id_cliente)
                                                        ->whereDate('fecha', \Carbon\Carbon::today())
                                                        ->whereNull('hora_salida')
                                                        ->first();
                                                @endphp

                                                @if($asistenciaActiva)
                                                    <form action="{{ route('asistencias.registrar-salida', $asistenciaActiva->id_asistencia) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          x-data="{ showNotification: false, message: '' }"
                                                          @submit.prevent="
                                                            fetch($el.action, {
                                                                method: 'POST',
                                                                body: new FormData($el),
                                                                headers: {
                                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                                    'Accept': 'application/json'
                                                                }
                                                            })
                                                            .then(response => response.json())
                                                            .then(data => {
                                                                showNotificationModal = true;
                                                                if (data.success) {
                                                                    notificationType = 'success';
                                                                    notificationMessage = 'Salida registrada correctamente';
                                                                    setTimeout(() => window.location.reload(), 1500);
                                                                } else {
                                                                    notificationType = 'error';
                                                                    notificationMessage = data.message || 'Error al registrar salida';
                                                                }
                                                            })
                                                            .catch(error => {
                                                                showNotificationModal = true;
                                                                notificationType = 'error';
                                                                notificationMessage = 'Error al procesar la solicitud';
                                                            })">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-orange-600 hover:text-orange-900 p-1 rounded-full hover:bg-orange-100 transition-colors duration-150"
                                                                title="Registrar salida">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('asistencias.entrada') }}" method="POST" class="inline"
                                                          x-data="{ showNotification: false, message: '' }"
                                                          @submit.prevent="
                                                            fetch($el.action, {
                                                                method: 'POST',
                                                                headers: {
                                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                                    'Accept': 'application/json',
                                                                    'Content-Type': 'application/json'
                                                                },
                                                                body: JSON.stringify({
                                                                    cliente_id: $el.querySelector('input[name=cliente_id]').value
                                                                })
                                                            })
                                                            .then(response => response.json())
                                                            .then(data => {
                                                                showNotificationModal = true;
                                                                if (data.success) {
                                                                    notificationType = 'success';
                                                                    notificationMessage = data.message;
                                                                    setTimeout(() => window.location.reload(), 1500);
                                                                } else {
                                                                    notificationType = 'error';
                                                                    notificationMessage = data.message;
                                                                }
                                                            })
                                                            .catch(error => {
                                                                console.error('Error:', error);
                                                                showNotificationModal = true;
                                                                notificationType = 'error';
                                                                notificationMessage = 'Error al procesar la solicitud';
                                                            })">
                                                        @csrf
                                                        <input type="hidden" name="cliente_id" value="{{ $cliente->id_cliente }}">
                                                        <button type="submit" 
                                                                class="text-green-600 hover:text-green-900 p-1 rounded-full hover:bg-green-100 transition-colors duration-150"
                                                                title="Registrar entrada">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal Crear -->
                <div x-show="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Crear Nuevo Cliente
                                </h2>
                                <form id="createClientForm" @submit.prevent="
                                    const formData = new FormData($event.target);
                                    fetch($event.target.action, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            if (response.status === 422) {
                                                return response.json().then(data => {
                                                    throw { type: 'validation', errors: data.errors };
                                                });
                                            } else if (response.status === 500) {
                                                return response.json().then(data => {
                                                    throw { type: 'server', message: data.message || 'Error interno del servidor' };
                                                });
                                            }
                                            throw { type: 'http', status: response.status };
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        showNotificationModal = true;
                                        if (data.success) {
                                            notificationType = 'success';
                                            notificationMessage = data.message || '¡Cliente creado exitosamente!';
                                            setTimeout(() => {
                                                // Redirigir a la página de membresías con el cliente seleccionado y modal abierto
                                                const nuevoClienteId = data.cliente_id;
                                                window.location.href = '/membresias?id_usuario=' + nuevoClienteId + '&open_modal=true';
                                            }, 2000);
                                        } else {
                                            notificationType = 'error';
                                            notificationMessage = data.message || 'Error al crear el cliente';
                                        }
                                    })
                                    .catch(error => {
                                        showNotificationModal = true;
                                        notificationType = 'error';
                                        if (error.type === 'validation') {
                                            const errorMessages = Object.values(error.errors).flat();
                                            notificationMessage = errorMessages.join('\n');
                                        } else if (error.type === 'server') {
                                            notificationMessage = 'Error del servidor: ' + error.message;
                                        } else {
                                            notificationMessage = 'Error al procesar la solicitud. Por favor, intente nuevamente.';
                                        }
                                    })"
                                    action="{{ route('clientes.store') }}">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Gimnasio</label>
                                            <select name="gimnasio_id" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="">Seleccione un gimnasio</option>
                                                @foreach($gimnasios as $gimnasio)
                                                    <option value="{{ $gimnasio->id_gimnasio }}">{{ $gimnasio->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nombre</label>
                                            <input type="text" name="nombre" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Email</label>
                                            <input type="email" name="email" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Teléfono</label>
                                            <input type="text" name="telefono"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Fecha de Nacimiento</label>
                                            <input type="date" name="fecha_nacimiento"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Género</label>
                                            <select name="genero" 
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="">Seleccione un género</option>
                                                <option value="masculino">Masculino</option>
                                                <option value="femenino">Femenino</option>
                                                <option value="otro">Otro</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Dirección</label>
                                            <input type="text" name="direccion"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <!-- Campo oculto para la contraseña predeterminada -->
                                        <input type="hidden" name="password" value="gymflow2025">
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" @click="toggleModal()"
                                                class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-md">
                                            Crear Cliente
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Notificación -->
                <div x-show="showNotificationModal" 
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4"
                         :class="{ 'border-l-4 border-green-500': notificationType === 'success', 'border-l-4 border-red-500': notificationType === 'error' }">
                        <div class="flex items-start">
                            <!-- Icono de éxito -->
                            <template x-if="notificationType === 'success'">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </template>
                            <!-- Icono de error -->
                            <template x-if="notificationType === 'error'">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </template>
                            
                            <div class="ml-3 w-full">
                                <h3 class="text-lg font-medium" :class="{ 'text-green-700': notificationType === 'success', 'text-red-700': notificationType === 'error' }">
                                    <span x-text="notificationType === 'success' ? 'Operación Exitosa' : 'Error'"></span>
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-700 whitespace-pre-line" x-text="notificationMessage"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex justify-end">
                            <button @click="showNotificationModal = false" 
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Editar -->
                <div x-show="isEditModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Editar Cliente
                                </h2>
                                <form @submit.prevent="
                                    const formData = new FormData($event.target);
                                    fetch('/clientes/' + currentCliente.id_cliente, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            if (response.status === 422) {
                                                return response.json().then(data => {
                                                    throw { type: 'validation', errors: data.errors };
                                                });
                                            } else if (response.status === 500) {
                                                return response.json().then(data => {
                                                    throw { type: 'server', message: data.message || 'Error interno del servidor' };
                                                });
                                            }
                                            throw { type: 'http', status: response.status };
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        showNotificationModal = true;
                                        if (data.success) {
                                            notificationType = 'success';
                                            notificationMessage = data.message || '¡Cliente actualizado exitosamente!';
                                            setTimeout(() => {
                                                window.location.reload();
                                            }, 2000);
                                        } else {
                                            notificationType = 'error';
                                            notificationMessage = data.message || 'Error al actualizar el cliente';
                                        }
                                    })
                                    .catch(error => {
                                        showNotificationModal = true;
                                        notificationType = 'error';
                                        if (error.type === 'validation') {
                                            const errorMessages = Object.values(error.errors).flat();
                                            notificationMessage = errorMessages.join('\n');
                                        } else if (error.type === 'server') {
                                            notificationMessage = 'Error del servidor: ' + error.message;
                                        } else {
                                            notificationMessage = 'Error al procesar la solicitud. Por favor, intente nuevamente.';
                                        }
                                    })">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Gimnasio</label>
                                            <select id="edit_gimnasio_id" name="gimnasio_id" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                @foreach($gimnasios as $gimnasio)
                                                    <option value="{{ $gimnasio->id_gimnasio }}">{{ $gimnasio->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nombre</label>
                                            <input type="text" id="edit_nombre" name="nombre" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Email</label>
                                            <input type="email" id="edit_email" name="email" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Teléfono</label>
                                            <input type="text" id="edit_telefono" name="telefono"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Fecha de Nacimiento</label>
                                            <input type="text" id="edit_fecha_nacimiento" name="fecha_nacimiento"
                                                   placeholder="dd/mm/aaaa" 
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                   onfocus="(this.type='date')" onblur="if(!this.value) this.type='text'">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Género</label>
                                            <select id="edit_genero" name="genero" 
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="">Seleccione un género</option>
                                                <option value="masculino">Masculino</option>
                                                <option value="femenino">Femenino</option>
                                                <option value="otro">Otro</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Dirección</label>
                                            <input type="text" id="edit_direccion" name="direccion"
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" @click="toggleEditModal()"
                                                class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-md">
                                            Actualizar Cliente
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Ver -->
                <div x-show="isViewModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Detalles del Cliente
                                </h2>
                                <div class="space-y-4">
                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Nombre</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.nombre"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Email</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.email"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Teléfono</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.telefono || 'N/A'"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Fecha de Nacimiento</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.fecha_nacimiento ? formatearFecha(currentCliente?.fecha_nacimiento) : 'N/A'"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Gimnasio</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.gimnasio.nombre"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Fecha de Registro</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.created_at ? formatearFecha(currentCliente?.created_at) : 'N/A'"></p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-emerald-700">Última Actualización</h5>
                                        <p class="mt-1 text-gray-900" x-text="currentCliente?.updated_at ? formatearFecha(currentCliente?.updated_at) : 'N/A'"></p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="button" @click="toggleViewModal()"
                                            class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Membresías - Plegable -->
                <div class="mt-8">
                    <div @click="tablaMembresiaAbierta = !tablaMembresiaAbierta" 
                         class="flex flex-col sm:flex-row justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg cursor-pointer hover:from-emerald-700 hover:to-teal-700 transition-colors duration-200">
                        <div class="flex items-center">
                            <h2 class="text-xl font-semibold text-white mr-2">Todas las Membresías</h2>
                            <svg class="w-5 h-5 transform transition-transform text-white" 
                                 :class="{'rotate-180': tablaMembresiaAbierta}"
                                 fill="none" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div x-show="tablaMembresiaAbierta" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4"
                         class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-emerald-200">
                                <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Cliente</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Tipo</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Precio</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Saldo Pendiente</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Vencimiento</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Visitas</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-emerald-100">
                                    @foreach($todasLasMembresias ?? [] as $membresia)
                                        <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $membresia->usuario->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $membresia->tipoMembresia->nombre }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($membresia->precio_total, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $membresia->saldo_pendiente > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    ${{ number_format($membresia->saldo_pendiente, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $membresia->fecha_vencimiento ? $membresia->fecha_vencimiento->format('d/m/Y') : 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($membresia->visitas_permitidas)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                        {{ $membresia->visitas_restantes }}/{{ $membresia->visitas_permitidas }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('membresias.edit', $membresia->id_membresia) }}" 
                                                       class="text-teal-600 hover:text-teal-900 font-medium">
                                                        Editar
                                                    </a>
                                                    <a href="{{ route('membresias.pagos', $membresia->id_membresia) }}"
                                                       class="text-blue-600 hover:text-blue-900 font-medium">
                                                        Pagos
                                                    </a>
                                                    @if($membresia->tipo_membresia === 'por_visitas')
                                                        <form action="{{ route('membresias.registrar-visita', $membresia->id_membresia) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                                Registrar Visita
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Pagos - Plegable -->
                <div class="mt-8">
                    <div @click="tablaPagosAbierta = !tablaPagosAbierta" 
                         class="flex flex-col sm:flex-row justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg cursor-pointer hover:from-emerald-700 hover:to-teal-700 transition-colors duration-200">
                        <div class="flex items-center">
                            <h2 class="text-xl font-semibold text-white mr-2">Todos los Pagos</h2>
                            <svg class="w-5 h-5 transform transition-transform text-white" 
                                 :class="{'rotate-180': tablaPagosAbierta}"
                                 fill="none" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div x-show="tablaPagosAbierta" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4"
                         class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-emerald-200">
                                <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Usuario</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Membresía</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Monto</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Método de Pago</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha de Pago</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-emerald-100">
                                    @foreach($todosLosPagos ?? [] as $pago)
                                        <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->usuario->name ?? 'Usuario no asignado' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->membresia->tipoMembresia->nombre ?? 'Membresía no asignada' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($pago->monto, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @switch($pago->metodoPago->nombre_metodo ?? '')
                                                    @case('tarjeta_credito')
                                                        Tarjeta de Crédito
                                                        @break
                                                    @case('efectivo')
                                                        Efectivo
                                                        @break
                                                    @case('transferencia_bancaria')
                                                        Transferencia Bancaria
                                                        @break
                                                    @default
                                                        {{ $pago->metodoPago->nombre_metodo ?? 'Método no definido' }}
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    @switch($pago->estado)
                                                        @case('aprobado')
                                                            bg-green-100 text-green-800
                                                            @break
                                                        @case('pendiente')
                                                            bg-yellow-100 text-yellow-800
                                                            @break
                                                        @case('rechazado')
                                                            bg-red-100 text-red-800
                                                            @break
                                                        @default
                                                            bg-gray-100 text-gray-800
                                                    @endswitch
                                                ">
                                                    {{ ucfirst($pago->estado) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('pagos.edit', $pago->id_pago) }}" 
                                                       class="text-teal-600 hover:text-teal-900 font-medium">
                                                        Editar
                                                    </a>
                                                    @if($pago->estado === 'pendiente')
                                                        <form action="{{ route('pagos.aprobar', $pago->id_pago) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="text-green-600 hover:text-green-900 font-medium">
                                                                Aprobar
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('pagos.destroy', $pago->id_pago) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 font-medium"
                                                                onclick="return confirm('¿Está seguro de eliminar este pago?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Asistencias - Plegable -->
                <div class="mt-8">
                    <div @click="tablaAsistenciasAbierta = !tablaAsistenciasAbierta" 
                         class="flex flex-col sm:flex-row justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg cursor-pointer hover:from-emerald-700 hover:to-teal-700 transition-colors duration-200">
                        <div class="flex items-center">
                            <h2 class="text-xl font-semibold text-white mr-2">Todas las Asistencias</h2>
                            <svg class="w-5 h-5 transform transition-transform text-white" 
                                 :class="{'rotate-180': tablaAsistenciasAbierta}"
                                 fill="none" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div x-show="tablaAsistenciasAbierta" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4"
                         class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-emerald-200">
                                <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Cliente</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Hora Entrada</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Hora Salida</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Duración</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-emerald-100">
                                    @foreach ($todasLasAsistencias ?? [] as $asistencia)
                                        <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $asistencia->cliente->nombre }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($asistencia->fecha)->timezone('America/Guayaquil')->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($asistencia->hora_entrada)->timezone('America/Guayaquil')->format('H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($asistencia->hora_salida)
                                                    {{ \Carbon\Carbon::parse($asistencia->hora_salida)->timezone('America/Guayaquil')->format('H:i') }}
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pendiente
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($asistencia->hora_salida)
                                                    {{ $asistencia->duracion_formateada }}
                                                @else
                                                    <span class="text-sm text-gray-500">En curso</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $asistencia->estado == 'activa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($asistencia->estado) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('asistencias.edit', $asistencia->id_asistencia) }}" 
                                                       class="text-teal-600 hover:text-teal-900 font-medium">
                                                        Editar
                                                    </a>
                                                    @if(!$asistencia->hora_salida)
                                                        <form action="{{ route('asistencias.registrar-salida', $asistencia->id_asistencia) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                                Registrar Salida
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('asistencias.destroy', $asistencia->id_asistencia) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 font-medium"
                                                                onclick="return confirm('¿Está seguro de eliminar esta asistencia?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 