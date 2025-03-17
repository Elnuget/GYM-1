<x-app-layout>
    <div x-data="{ 
        isModalOpen: false,
        isPagoModalOpen: false,
        currentMembresia: null,
        showVisitasFields: false,
        toggleModal() {
            this.isModalOpen = !this.isModalOpen;
        },
        toggleVisitasFields() {
            this.showVisitasFields = document.getElementById('tipo_membresia').value === 'por_visitas';
        },
        togglePagoModal(membresia = null) {
            this.isPagoModalOpen = !this.isPagoModalOpen;
            
            // Si estamos cerrando el modal, no necesitamos cargar datos
            if (!this.isPagoModalOpen) {
                return;
            }
            
            // Si tenemos datos de la membresía
            if (membresia) {
                console.log('Membresía seleccionada:', membresia);
                
                // Asignamos los datos que ya tenemos primero
                this.currentMembresia = membresia;
                
                // No realizamos la petición AJAX si ya tenemos el tipo de membresía
                if (membresia.tipoMembresia && membresia.tipoMembresia.nombre) {
                    console.log('Usando datos existentes del tipo de membresía:', membresia.tipoMembresia.nombre);
                    return;
                }
                
                // Si el ID está presente y no tenemos el tipo de membresía completo, cargar datos
                if (membresia.id_membresia) {
                    const url = `/api/membresias/${membresia.id_membresia}`;
                    console.log('Cargando datos desde:', url);
                    
                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Error de servidor: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Datos cargados correctamente:', data);
                            
                            // Verificar si tenemos la información necesaria
                            if (!data.tipoMembresia || !data.tipoMembresia.nombre) {
                                console.warn('No se pudo cargar el tipo de membresía');
                            } else {
                                console.log('Tipo de membresía:', data.tipoMembresia.nombre);
                            }
                            
                            this.currentMembresia = data;
                        })
                        .catch(error => {
                            console.error('Error cargando datos de membresía:', error);
                            // No mostrar alerta, usar los datos que ya tenemos
                        });
                }
            }
        }
    }">

        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Panel de Filtros Colapsable -->
                <div x-data="{ open: false }" class="mb-6 bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <!-- Cabecera del panel de filtros -->
                    <div @click="open = !open" class="bg-gradient-to-r from-emerald-500 to-teal-500 p-4 cursor-pointer">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Opciones de Filtrado
                            </h3>
                            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Contenido de los filtros (colapsable) -->
                    <div x-show="open" class="p-4 bg-gradient-to-br from-white to-emerald-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Filtro por usuario -->
                            <div class="bg-white p-4 rounded-lg shadow border border-emerald-100">
                                <h4 class="text-base font-medium text-emerald-700 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Filtrar por Cliente
                                </h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Selecciona un cliente para ver todas sus membresías. Se aplicará automáticamente.
                                </p>
                                <form action="{{ route('membresias.index') }}" method="GET">
                                    <div>
                                        <select name="id_usuario" onchange="this.form.submit()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="">Seleccionar cliente...</option>
                                            @foreach($usuarios->sortBy('name') as $usuario)
                                            @php
                                            $gimnasioNombre = isset($usuario->cliente) && isset($usuario->cliente->gimnasio) ? $usuario->cliente->gimnasio->nombre : 'Sin gimnasio';
                                            @endphp
                                            <option value="{{ $usuario->id }}" {{ $idUsuario == $usuario->id ? 'selected' : '' }}>
                                                {{ $usuario->name }} - {{ $gimnasioNombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>

                            <!-- Filtro por fecha -->
                            <div class="bg-white p-4 rounded-lg shadow border border-emerald-100">
                                <h4 class="text-base font-medium text-emerald-700 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Filtrar por Fecha
                                </h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Filtra las membresías por fecha de creación o vencimiento.
                                </p>
                                <form action="{{ route('membresias.index') }}" method="GET" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Fecha</label>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="tipo_filtro" value="creacion" class="text-emerald-600 focus:ring-emerald-500" {{ $tipoFiltro === 'creacion' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Fecha de Creación</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="tipo_filtro" value="vencimiento" class="text-emerald-600 focus:ring-emerald-500" {{ $tipoFiltro === 'vencimiento' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Fecha de Vencimiento</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                                            <select name="mes" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                @foreach($meses as $valor => $nombre)
                                                <option value="{{ $valor }}" {{ $mes == $valor ? 'selected' : '' }}>
                                                    {{ $nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                                            <select name="anio" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                @foreach($anios as $anioOption)
                                                <option value="{{ $anioOption }}" {{ $anio == $anioOption ? 'selected' : '' }}>
                                                    {{ $anioOption }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex space-x-2">
                                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 w-full md:w-auto">
                                            <div class="flex justify-center items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                                </svg>
                                                Aplicar Filtro
                                            </div>
                                        </button>
                                        <a href="{{ route('membresias.index', ['mostrar_todos' => 1]) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 flex justify-center items-center w-full md:w-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                            </svg>
                                            Mostrar Todos
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Header con gradiente -->
                <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-white">
                        @if($idUsuario && $usuarioSeleccionado)
                        Membresías de {{ $usuarioSeleccionado->name }}
                        @elseif($mostrarTodos)
                        Todas las Membresías
                        @elseif(isset($mostrarVencidas) && $mostrarVencidas)
                        Membresías vencidas este mes
                        @elseif(isset($mostrarSinRenovar) && $mostrarSinRenovar)
                        Membresías sin renovar
                        @elseif(isset($mostrarActivas) && $mostrarActivas)
                        Membresías activas
                        @elseif(isset($mostrarSaldosPendientes) && $mostrarSaldosPendientes)
                        Membresías con saldo pendiente
                        @else
                        @if($tipoFiltro === 'vencimiento')
                        Membresías que vencen en {{ $meses[$mes] }} {{ $anio }}
                        @else
                        Membresías creadas en {{ $meses[$mes] }} {{ $anio }}
                        @endif
                        @endif
                    </h2>
                    <button @click="toggleModal()"
                        class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva Membresía
                    </button>
                </div>

                <!-- Mensajes de alerta -->
                @if(session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                    class="mb-6 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-md shadow-md transition-opacity duration-500">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                    class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-md transition-opacity duration-500">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                <!-- Tarjeta de Estadísticas de Membresías -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Membresías activas -->
                    <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg shadow-lg border border-emerald-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-green-500 px-4 py-3">
                            <h3 class="text-white font-medium text-lg flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Membresías Activas
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Vigentes actualmente</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $membresiasActivas ?? 0 }}</p>
                                </div>
                                <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('membresias.activas') }}" class="inline-block w-full py-2 px-4 bg-green-500 text-white text-center font-medium rounded-md hover:bg-green-600 transition-colors">
                                    Ver membresías activas
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Membresías vencidas en el mes actual -->
                    <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg shadow-lg border border-emerald-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-500 to-orange-500 px-4 py-3">
                            <h3 class="text-white font-medium text-lg flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Membresías Vencidas
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Vencidas este mes</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $membresiasVencidasMes ?? 0 }}</p>
                                </div>
                                <div class="h-16 w-16 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('membresias.vencidas') }}" class="inline-block w-full py-2 px-4 bg-red-500 text-white text-center font-medium rounded-md hover:bg-red-600 transition-colors">
                                    Ver membresías vencidas
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Membresías vencidas sin renovar -->
                    <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg shadow-lg border border-emerald-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 px-4 py-3">
                            <h3 class="text-white font-medium text-lg flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Sin Renovación
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Vencidas sin renovar</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $membresiasNoRenovadas ?? 0 }}</p>
                                </div>
                                <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('membresias.sin-renovar') }}" class="inline-block w-full py-2 px-4 bg-indigo-500 text-white text-center font-medium rounded-md hover:bg-indigo-600 transition-colors">
                                    Ver membresías sin renovar
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Membresías con saldo pendiente -->
                    <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg shadow-lg border border-emerald-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-yellow-500 px-4 py-3">
                            <h3 class="text-white font-medium text-lg flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Saldos Pendientes
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Total: ${{ number_format($totalSaldosPendientes ?? 0, 2) }}</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ $membresiasPendientesPago ?? 0 }}</p>
                                </div>
                                <div class="h-16 w-16 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('membresias.saldos-pendientes') }}" class="inline-block w-full py-2 px-4 bg-yellow-500 text-white text-center font-medium rounded-md hover:bg-yellow-600 transition-colors">
                                    Ver saldos pendientes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla con nuevo diseño -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-emerald-200">
                            <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Gimnasio</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Precio</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Saldo Pendiente</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Vencimiento</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Visitas</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-emerald-100">
                                @foreach ($membresias as $membresia)
                                <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $membresia->usuario->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $membresia->tipoMembresia->gimnasio->nombre }}</td>
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
                                            @if($membresia->tipo_membresia === 'por_visitas')
                                            <form action="{{ route('membresias.registrar-visita', $membresia) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                    Registrar Visita
                                                </button>
                                            </form>
                                            @endif
                                            <button @click="togglePagoModal({{ $membresia->toJson() }})"
                                                class="text-blue-600 hover:text-blue-900 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                </svg>
                                                Pagar
                                            </button>
                                            <form action="{{ route('membresias.destroy', $membresia) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
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

                <!-- Modal de Nueva Membresía -->
                <div x-show="isModalOpen"
                    class="fixed inset-0 z-50 overflow-y-auto"
                    style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Nueva Membresía
                                </h2>
                                <form action="{{ route('membresias.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Cliente</label>
                                        <select name="id_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($usuarios->sortByDesc('id') as $usuario)
                                            @php
                                            $gimnasioNombre = isset($usuario->cliente) && isset($usuario->cliente->gimnasio) ? $usuario->cliente->gimnasio->nombre : 'Sin gimnasio';
                                            @endphp
                                            <option value="{{ $usuario->id }}">{{ $usuario->name }} - {{ $gimnasioNombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Tipo de Membresía</label>
                                        <select name="id_tipo_membresia"
                                            id="tipo_membresia"
                                            @change="toggleVisitasFields()"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($tiposMembresia as $tipo)
                                            <option value="{{ $tipo->id_tipo_membresia }}"
                                                data-precio="{{ $tipo->precio }}"
                                                data-duracion="{{ $tipo->duracion_dias }}"
                                                data-tipo="{{ $tipo->tipo }}">
                                                {{ $tipo->nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Compra</label>
                                        <input type="date" name="fecha_compra" id="fecha_compra" value="{{ date('Y-m-d') }}"
                                            @change="calcularVencimiento()"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Vencimiento</label>
                                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Precio Total</label>
                                        <input type="number" step="0.01" name="precio_total" id="precio_total" required readonly
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Saldo Pendiente</label>
                                        <input type="number" step="0.01" name="saldo_pendiente" id="saldo_pendiente" required readonly
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4 visitas-fields" style="display: none;">
                                        <label class="block text-sm font-medium text-gray-700">Número de Visitas</label>
                                        <input type="number" name="visitas_permitidas"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="renovacion" value="1" checked
                                                class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <span class="ml-2 text-sm text-gray-600">¿Es Renovable?</span>
                                        </label>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button"
                                            @click="toggleModal()"
                                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                                            Crear Membresía
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Agregar Pago -->
                <div x-show="isPagoModalOpen"
                    class="fixed inset-0 z-50 overflow-y-auto"
                    style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="absolute top-0 right-0 pt-4 pr-4">
                                <button type="button" @click="togglePagoModal()"
                                    class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Nuevo Pago
                                </h2>

                                <!-- Información de la Membresía -->
                                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-4 rounded-lg mb-6">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600">Cliente:</p>
                                            <p class="font-medium" x-text="currentMembresia?.usuario?.name"></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Precio Total:</p>
                                            <p class="font-medium">$<span x-text="currentMembresia?.precio_total"></span></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Saldo Pendiente:</p>
                                            <p class="font-medium">$<span x-text="currentMembresia?.saldo_pendiente"></span></p>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('pagos.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id_membresia" x-bind:value="currentMembresia?.id_membresia">
                                    <input type="hidden" name="id_usuario" x-bind:value="currentMembresia?.id_usuario">

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Monto</label>
                                        <input type="number" step="0.01" name="monto" required
                                            x-bind:value="currentMembresia?.saldo_pendiente"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                        <select name="id_metodo_pago" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($metodosPago ?? [] as $metodo)
                                            <option value="{{ $metodo->id_metodo_pago }}">
                                                @switch($metodo->nombre_metodo)
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
                                                {{ $metodo->nombre_metodo }}
                                                @endswitch
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Estado de Pago</label>
                                        <select name="estado_pago" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="aprobado" selected>Aprobado</option>
                                            <option value="pendiente">Pendiente</option>
                                            <option value="rechazado">Rechazado</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Comprobante (opcional)</label>
                                        <input type="file" name="comprobante"
                                            class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                        <p class="mt-1 text-xs text-gray-500">
                                            Formatos permitidos: JPG, JPEG, PNG, PDF. Tamaño máximo: 5MB.
                                        </p>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                                        <textarea name="notas" rows="2"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                                        <input type="date" name="fecha_pago" value="{{ date('Y-m-d') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button"
                                            @click="togglePagoModal()"
                                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                                            Registrar Pago
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoMembresia = document.getElementById('tipo_membresia');
            const visitasFields = document.querySelector('.visitas-fields');
            const fechaVencimiento = document.getElementById('fecha_vencimiento');
            const precioTotal = document.getElementById('precio_total');
            const saldoPendiente = document.getElementById('saldo_pendiente');

            function calcularVencimiento() {
                const fechaCompra = new Date(document.getElementById('fecha_compra').value);
                const tipoSeleccionado = tipoMembresia.options[tipoMembresia.selectedIndex];
                const duracion = parseInt(tipoSeleccionado.dataset.duracion);
                const precio = parseFloat(tipoSeleccionado.dataset.precio);
                const tipo = tipoSeleccionado.dataset.tipo;

                // Calcular precio y saldo pendiente
                precioTotal.value = precio;
                saldoPendiente.value = precio;

                // Calcular fecha de vencimiento solo si no es por visitas
                if (tipo !== 'por_visitas') {
                    const fechaVenc = new Date(fechaCompra);
                    fechaVenc.setDate(fechaVenc.getDate() + duracion);
                    fechaVencimiento.value = fechaVenc.toISOString().split('T')[0];
                    fechaVencimiento.disabled = false;
                } else {
                    fechaVencimiento.value = '';
                    fechaVencimiento.disabled = true;
                }
            }

            tipoMembresia.addEventListener('change', function() {
                visitasFields.style.display = this.options[this.selectedIndex].dataset.tipo === 'por_visitas' ? 'block' : 'none';
                calcularVencimiento();
            });

            // Calcular valores iniciales
            calcularVencimiento();
        });
    </script>
</x-app-layout>