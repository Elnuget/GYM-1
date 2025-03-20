<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <div x-data="pagoData">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Tarjeta de Estadísticas de Pagos -->
                <div class="mb-2 space-y-2">
                    <!-- Primera fila de tarjetas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        <!-- Total de Pagos -->
                        <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg shadow-sm border border-emerald-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-500 to-green-500 px-2 py-1">
                                <h3 class="text-white font-medium text-base flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Total de Pagos
                                </h3>
                            </div>
                            <div class="p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-xs mb-1">Monto total de pagos</p>
                                        <p class="text-2xl font-bold text-gray-800">${{ $montoTotalFormateado }}</p>
                                    </div>
                                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-2 text-center">
                                    <a href="{{ route('pagos.index', ['mostrar_todos' => 1]) }}" class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 hover:text-green-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver todos los pagos
                                    </a>
                                    <a href="{{ route('pagos.index', ['mes' => now()->format('m'), 'anio' => now()->format('Y')]) }}" class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 hover:text-green-900 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Ver pagos de este mes
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Pagos con Tarjeta de Crédito -->
                        <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg shadow-sm border border-emerald-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 px-2 py-1">
                                <h3 class="text-white font-medium text-base flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    Tarjeta de Crédito
                                </h3>
                            </div>
                            <div class="p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-xs mb-1">Monto pagado</p>
                                        <p class="text-2xl font-bold text-gray-800">${{ $pagosTarjeta }}</p>
                                    </div>
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-2 text-center">
                                    <button @click="filtrarPorMetodo('tarjeta_credito')" class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 hover:text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver pagos con tarjeta
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pagos en Efectivo -->
                        <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg shadow-sm border border-emerald-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-yellow-500 to-amber-500 px-2 py-1">
                                <h3 class="text-white font-medium text-base flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                    Efectivo
                                </h3>
                            </div>
                            <div class="p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-xs mb-1">Monto pagado</p>
                                        <p class="text-2xl font-bold text-gray-800">${{ $pagosEfectivo }}</p>
                                    </div>
                                    <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-2 text-center">
                                    <button @click="filtrarPorMetodo('efectivo')" class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-700 hover:text-yellow-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver pagos en efectivo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Segunda fila de tarjetas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        <!-- Pagos por Transferencia Bancaria -->
                        <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg shadow-sm border border-emerald-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-2 py-1">
                                <h3 class="text-white font-medium text-base flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                    </svg>
                                    Transferencia
                                </h3>
                            </div>
                            <div class="p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-xs mb-1">Monto pagado</p>
                                        <p class="text-2xl font-bold text-gray-800">${{ $pagosTransferencia }}</p>
                                    </div>
                                    <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-2 text-center">
                                    <button @click="filtrarPorMetodo('transferencia_bancaria')" class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-700 hover:text-purple-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver pagos por transferencia
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pagos Aprobados -->
                        <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg shadow-sm border border-emerald-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-2 py-1">
                                <h3 class="text-white font-medium text-base flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pagos Aprobados
                                </h3>
                            </div>
                            <div class="p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-xs mb-1">Total de pagos aprobados</p>
                                        <p class="text-2xl font-bold text-gray-800">{{ $totalPagosAprobados }}</p>
                                    </div>
                                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-2 text-center">
                                    <button @click="filtrarPorEstado('aprobado')" class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 hover:text-green-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver pagos aprobados
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pagos Pendientes -->
                        <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg shadow-sm border border-emerald-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-yellow-500 to-amber-500 px-2 py-1">
                                <h3 class="text-white font-medium text-base flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pagos Pendientes
                                </h3>
                            </div>
                            <div class="p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 text-xs mb-1">Total de pagos pendientes</p>
                                        <p class="text-2xl font-bold text-gray-800">{{ $totalPagosPendientes }}</p>
                                    </div>
                                    <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-2 text-center">
                                    <button @click="filtrarPorEstado('pendiente')" class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-700 hover:text-yellow-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver pagos pendientes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                    Selecciona un cliente para ver sus pagos. Se aplicará automáticamente.
                                </p>
                                <form action="{{ route('pagos.index') }}" method="GET">
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

                            <!-- Filtro por fecha de pago -->
                            <div class="bg-white p-4 rounded-lg shadow border border-emerald-100">
                                <h4 class="text-base font-medium text-emerald-700 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Filtrar por Fecha de Pago
                                </h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Filtra los pagos según el mes y año de la fecha de pago.
                                </p>
                                <form action="{{ route('pagos.index') }}" method="GET" class="space-y-4">
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
                                        <a href="{{ route('pagos.index', ['mostrar_todos' => 1]) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 flex justify-center items-center w-full md:w-auto">
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
                        @if($idUsuario && $usuarios->contains($idUsuario))
                            Pagos de {{ $usuarios->firstWhere('id', $idUsuario)->name }}
                        @elseif(request()->has('mostrar_todos'))
                            Todos los Pagos
                        @else
                            Pagos realizados en {{ $meses[$mes] }} {{ $anio }}
                        @endif
                    </h2>
                    <button @click="toggleModal()" 
                            class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nuevo Pago
                    </button>
                </div>

                <!-- Tabla con nuevo diseño -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Usuario
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Membresía
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Método de Pago
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha de Pago
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($pagos as $pago)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $pago->usuario->name ?? 'Usuario no asignado' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $pago->membresia->tipoMembresia->nombre ?? 'Membresía no asignada' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                ${{ number_format($pago->monto, 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
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
                                                        Método no definido
                                                @endswitch
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $pago->fecha_pago->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button type="button" @click="toggleDetallesModal({{ $pago->id_pago }})"
                                                    class="text-blue-600 hover:text-blue-900 mr-2" title="Ver Detalles">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                            @if($pago->estado === 'pendiente')
                                                <form action="{{ route('pagos.aprobar', $pago->id_pago) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-2" title="Aprobar Pago">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <button type="button" @click="toggleEditModal({{ $pago->id_pago }})"
                                                    class="text-emerald-600 hover:text-emerald-900 mr-2" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <form class="inline-block" action="{{ route('pagos.destroy', $pago) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900" 
                                                        title="Eliminar"
                                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este pago?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginación con estilo mejorado -->
                <div class="mt-4">
                    <!-- Eliminada la paginación ya que ahora mostramos todos los registros -->
                </div>

                <!-- Modal de Detalles del Pago -->
                <div x-show="isDetallesModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     x-cloak
                     @keydown.escape.window="toggleDetallesModal()"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4" @click.away="toggleDetallesModal()">
                        <div class="relative bg-white rounded-xl max-w-2xl w-full shadow-xl">
                            <div class="absolute top-0 right-0 pt-4 pr-4">
                                <button type="button" @click="toggleDetallesModal()"
                                        class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Detalles del Pago
                                </h2>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Usuario</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="currentPago?.usuario?.name || 'No asignado'"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Membresía</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="currentPago?.membresia?.tipoMembresia?.nombre || 'No asignada'"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Monto del Pago</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="'$' + (currentPago?.monto || 0)"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Saldo Pendiente Membresía</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="'$' + (currentPago?.membresia?.saldo_pendiente || 0)"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                        <p class="mt-1 text-sm text-gray-900" x-html="formatMetodoPago(currentPago?.metodoPago?.nombre_metodo)"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="formatFecha(currentPago?.fecha_pago) || ''"></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                                        <p class="mt-1">
                                            <span x-bind:class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800': currentPago?.estado === 'aprobado',
                                                'bg-yellow-100 text-yellow-800': currentPago?.estado === 'pendiente',
                                                'bg-red-100 text-red-800': currentPago?.estado === 'rechazado'
                                            }" x-text="currentPago?.estado"></span>
                                        </p>
                                    </div>

                                    <div x-show="currentPago?.fecha_aprobacion">
                                        <label class="block text-sm font-medium text-gray-700">Fecha Aprobación</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="formatFecha(currentPago?.fecha_aprobacion) || ''"></p>
                                    </div>

                                    <div class="col-span-2" x-show="currentPago?.comprobante_url">
                                        <label class="block text-sm font-medium text-gray-700">Comprobante</label>
                                        <!-- Mostrar imagen directamente si es un archivo de imagen -->
                                        <template x-if="isImage(currentPago?.comprobante_url)">
                                            <div class="mt-2">
                                                <img :src="'{{ asset('storage') }}/' + currentPago?.comprobante_url" 
                                                     class="max-w-sm rounded-lg shadow-md border border-gray-200 max-h-64 object-contain" 
                                                     alt="Comprobante de pago">
                                                <a :href="'{{ asset('storage') }}/' + currentPago?.comprobante_url" 
                                                   target="_blank"
                                                   class="mt-1 text-sm text-emerald-600 hover:text-emerald-900 inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                    Ver imagen completa
                                                </a>
                                            </div>
                                        </template>
                                        <!-- Enlace para otros tipos de archivos -->
                                        <template x-if="!isImage(currentPago?.comprobante_url)">
                                            <a :href="'{{ asset('storage') }}/' + currentPago?.comprobante_url" 
                                               target="_blank"
                                               class="mt-2 text-sm text-emerald-600 hover:text-emerald-900 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Ver documento
                                            </a>
                                        </template>
                                    </div>

                                    <div class="col-span-2" x-show="currentPago?.notas">
                                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                                        <p class="mt-1 text-sm text-gray-900" x-text="currentPago?.notas"></p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" 
                                            @click="toggleDetallesModal()"
                                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Cerrar
                                    </button>
                                    <template x-if="currentPago?.estado === 'pendiente'">
                                        <form :action="'/pagos/' + currentPago?.id_pago + '/aprobar'" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Aprobar Pago
                                            </button>
                                        </form>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Nuevo Pago -->
                <div x-show="isModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     x-cloak
                     @keydown.escape.window="toggleModal()"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4" @click.away="toggleModal()">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="absolute top-0 right-0 pt-4 pr-4">
                                <button type="button" @click="toggleModal()"
                                        class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Nuevo Pago
                                </h2>
                                <form action="{{ route('pagos.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Usuario</label>
                                        <select name="id_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Membresía</label>
                                        <select name="id_membresia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($membresias as $membresia)
                                                <option value="{{ $membresia->id_membresia }}">
                                                    {{ $membresia->tipoMembresia->nombre ?? 'No asignada' }} - {{ $membresia->usuario->name ?? 'Usuario no asignado' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Monto</label>
                                        <input type="number" step="0.01" name="monto" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Estado del Pago</label>
                                        <select name="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="pendiente">Pendiente</option>
                                            <option value="aprobado">Aprobado</option>
                                            <option value="rechazado">Rechazado</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Comprobante</label>
                                        <input type="file" name="comprobante" 
                                               class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                        <p class="mt-1 text-xs text-gray-500">
                                            Formatos permitidos: JPG, JPEG, PNG, PDF. Tamaño máximo: 5MB.
                                        </p>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                                        <textarea name="notas" rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                        <select name="id_metodo_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($metodosPago as $metodo)
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
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                                        <input type="date" name="fecha_pago" value="{{ date('Y-m-d') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" 
                                                @click="toggleModal()"
                                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Crear Pago
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Edición -->
                <div x-show="isEditModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     x-cloak
                     @keydown.escape.window="toggleEditModal()"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4" @click.away="toggleEditModal()">
                        <div class="relative bg-white rounded-xl max-w-2xl w-full shadow-xl">
                            <div class="absolute top-0 right-0 pt-4 pr-4">
                                <button type="button" @click="toggleEditModal()"
                                        class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                    Editar Pago
                                </h2>
                                <form :action="'/pagos/' + currentPago?.id_pago" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Usuario</label>
                                        <select name="id_usuario" id="edit_id_usuario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Membresía</label>
                                        <select name="id_membresia" id="edit_id_membresia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($membresias as $membresia)
                                                <option value="{{ $membresia->id_membresia }}">
                                                    {{ $membresia->tipoMembresia->nombre ?? 'No asignada' }} - {{ $membresia->usuario->name ?? 'Usuario no asignado' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Monto</label>
                                        <input type="number" step="0.01" name="monto" id="edit_monto" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                                        <input type="date" name="fecha_pago" id="edit_fecha_pago" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                                        <select name="estado" id="edit_estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="pendiente">Pendiente</option>
                                            <option value="aprobado">Aprobado</option>
                                            <option value="rechazado">Rechazado</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                        <select name="id_metodo_pago" id="edit_id_metodo_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($metodosPago as $metodo)
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
                                        <label class="block text-sm font-medium text-gray-700">Comprobante Actual</label>
                                        <div x-show="currentPago?.comprobante_url" class="mt-2">
                                            <!-- Mostrar imagen directamente si es un archivo de imagen -->
                                            <template x-if="isImage(currentPago?.comprobante_url)">
                                                <div class="mt-2">
                                                    <img :src="'{{ asset('storage') }}/' + currentPago?.comprobante_url" 
                                                         class="max-w-xs rounded-lg shadow-md border border-gray-200 max-h-48 object-contain mb-2" 
                                                         alt="Comprobante de pago">
                                                    <a :href="'{{ asset('storage') }}/' + currentPago?.comprobante_url" 
                                                       target="_blank"
                                                       class="mt-1 text-sm text-emerald-600 hover:text-emerald-900 inline-flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                        </svg>
                                                        Ver imagen completa
                                                    </a>
                                                </div>
                                            </template>
                                            <!-- Enlace para otros tipos de archivos -->
                                            <template x-if="!isImage(currentPago?.comprobante_url)">
                                                <a :href="'{{ asset('storage') }}/' + currentPago?.comprobante_url" 
                                                   target="_blank"
                                                   class="mt-2 text-sm text-emerald-600 hover:text-emerald-900 inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Ver documento
                                                </a>
                                            </template>
                                        </div>
                                        <div class="mt-2">
                                            <label class="block text-sm font-medium text-gray-700">Actualizar Comprobante</label>
                                            <input type="file" name="comprobante" 
                                                   class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                            <p class="mt-1 text-xs text-gray-500">
                                                Deja esto en blanco para mantener el comprobante actual.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                                        <textarea name="notas" id="edit_notas" rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" 
                                                @click="toggleEditModal()"
                                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Guardar Cambios
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
</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pagoData', () => ({
            isModalOpen: false,
            isEditModalOpen: false,
            isDetallesModalOpen: false,
            currentPago: null,
            pagos: @json($pagos),
            pagosFiltrados: @json($pagos),
            
            filtrarPorMetodo(metodo) {
                if (metodo === null) {
                    // Mostrar todos los pagos
                    this.pagosFiltrados = this.pagos;
                } else {
                    // Filtrar pagos por método de pago
                    this.pagosFiltrados = this.pagos.filter(pago => 
                        pago.metodo_pago?.nombre_metodo === metodo
                    );
                }
                
                // Actualizar la tabla
                this.actualizarTabla();
            },
            
            filtrarPorEstado(estado) {
                if (estado === null) {
                    // Mostrar todos los pagos
                    this.pagosFiltrados = this.pagos;
                } else {
                    // Filtrar pagos por estado
                    this.pagosFiltrados = this.pagos.filter(pago => 
                        pago.estado === estado
                    );
                }
                
                // Actualizar la tabla
                this.actualizarTabla();
            },
            
            actualizarTabla() {
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = '';
                
                this.pagosFiltrados.forEach(pago => {
                    const tr = document.createElement('tr');
                    
                    // Usuario
                    tr.innerHTML += `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                ${pago.usuario?.name || 'Usuario no asignado'}
                            </div>
                        </td>`;
                    
                    // Membresía
                    tr.innerHTML += `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                ${pago.membresia?.tipo_membresia?.nombre || 'Membresía no asignada'}
                            </div>
                        </td>`;
                    
                    // Monto
                    tr.innerHTML += `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                $${parseFloat(pago.monto).toFixed(2)}
                            </div>
                        </td>`;
                    
                    // Método de pago
                    const metodoPago = this.formatMetodoPago(pago.metodo_pago?.nombre_metodo);
                    tr.innerHTML += `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                ${metodoPago}
                            </div>
                        </td>`;
                    
                    // Estado
                    const estadoClase = {
                        'aprobado': 'bg-green-100 text-green-800',
                        'pendiente': 'bg-yellow-100 text-yellow-800',
                        'rechazado': 'bg-red-100 text-red-800'
                    }[pago.estado] || 'bg-gray-100 text-gray-800';
                    
                    tr.innerHTML += `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${estadoClase}">
                                ${pago.estado.charAt(0).toUpperCase() + pago.estado.slice(1)}
                            </span>
                        </td>`;
                    
                    // Fecha de pago
                    tr.innerHTML += `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                ${this.formatFecha(pago.fecha_pago)}
                            </div>
                        </td>`;
                    
                    // Acciones
                    tr.innerHTML += `
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button type="button" @click="toggleDetallesModal(${pago.id_pago})"
                                    class="text-blue-600 hover:text-blue-900 mr-2" title="Ver Detalles">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            @if($pago->estado === 'pendiente')
                                <form action="{{ route('pagos.aprobar', $pago->id_pago) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-2" title="Aprobar Pago">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                            <button type="button" @click="toggleEditModal(${pago.id_pago})"
                                    class="text-emerald-600 hover:text-emerald-900 mr-2" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <form class="inline-block" action="/pagos/${pago.id_pago}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900" 
                                        title="Eliminar"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este pago?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>`;
                    
                    tbody.appendChild(tr);
                });
            },
            
            toggleModal() {
                this.isModalOpen = !this.isModalOpen;
                console.log('Modal estado:', this.isModalOpen);
            },
            
            toggleEditModal(pagoId = null) {
                if (pagoId === null) {
                    // Si no se proporciona ID, solo alternamos el estado del modal
                    this.isEditModalOpen = !this.isEditModalOpen;
                    console.log('Modal edición estado:', this.isEditModalOpen);
                    return;
                }
                
                // Busca el pago en el array de pagos
                const pago = this.pagos.find(p => p.id_pago === pagoId);
                console.log('Pago encontrado para edición:', pago);
                
                if (pago) {
                    this.currentPago = pago;
                    this.isEditModalOpen = true;
                    
                    this.$nextTick(() => {
                        // Asegurarse de que los elementos existen antes de intentar asignar valores
                        if (document.getElementById('edit_id_membresia')) {
                            document.getElementById('edit_id_membresia').value = pago.id_membresia;
                        }
                        if (document.getElementById('edit_id_usuario')) {
                            document.getElementById('edit_id_usuario').value = pago.id_usuario;
                        }
                        if (document.getElementById('edit_monto')) {
                            document.getElementById('edit_monto').value = pago.monto;
                        }
                        if (document.getElementById('edit_fecha_pago')) {
                            document.getElementById('edit_fecha_pago').value = pago.fecha_pago;
                        }
                        if (document.getElementById('edit_estado')) {
                            document.getElementById('edit_estado').value = pago.estado;
                        }
                        if (document.getElementById('edit_id_metodo_pago')) {
                            document.getElementById('edit_id_metodo_pago').value = pago.id_metodo_pago;
                        }
                        if (document.getElementById('edit_notas')) {
                            document.getElementById('edit_notas').value = pago.notas || '';
                        }
                    });
                }
            },
            
            toggleDetallesModal(pagoId = null) {
                if (pagoId === null) {
                    // Si no se proporciona ID, solo alternamos el estado del modal
                    this.isDetallesModalOpen = !this.isDetallesModalOpen;
                    console.log('Modal detalles estado:', this.isDetallesModalOpen);
                    return;
                }
                
                // Busca el pago en el array de pagos
                const pago = this.pagos.find(p => p.id_pago === pagoId);
                console.log('Pago encontrado para detalles:', pago);
                
                if (pago) {
                    // Asegurarse de que tengamos todas las relaciones cargadas
                    if (!pago._loaded) {
                        console.log('Cargando información completa del pago...');
                        
                        // Crear una copia profunda del pago para no afectar el array original
                        this.currentPago = JSON.parse(JSON.stringify(pago));
                        
                        // Marcar como cargado para no volver a procesar
                        this.currentPago._loaded = true;
                        
                        // Asegurarnos de que los objetos relacionados estén presentes
                        if (this.currentPago.metodoPago && typeof this.currentPago.metodoPago === 'object') {
                            console.log('Método de pago cargado:', this.currentPago.metodoPago);
                        } else {
                            console.warn('Método de pago no disponible o no es un objeto');
                        }
                        
                        if (this.currentPago.membresia && typeof this.currentPago.membresia === 'object') {
                            console.log('Membresía cargada:', this.currentPago.membresia);
                            
                            if (this.currentPago.membresia.tipoMembresia && typeof this.currentPago.membresia.tipoMembresia === 'object') {
                                console.log('Tipo de membresía cargado:', this.currentPago.membresia.tipoMembresia);
                            } else {
                                console.warn('Tipo de membresía no disponible o no es un objeto');
                            }
                        } else {
                            console.warn('Membresía no disponible o no es un objeto');
                        }
                    } else {
                        this.currentPago = pago;
                    }
                    
                    this.isDetallesModalOpen = true;
                    
                    // Imprimir la URL del comprobante para depuración
                    if (this.currentPago.comprobante_url) {
                        console.log('URL del comprobante:', this.currentPago.comprobante_url);
                    }
                }
            },
            
            isImage(url) {
                if (!url) return false;
                // Verificar si la URL termina con una extensión de imagen
                const extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                const extension = url.split('.').pop().toLowerCase();
                return extensions.includes(extension);
            },
            
            formatMetodoPago(metodo) {
                if (!metodo) return 'No definido';
                switch(metodo) {
                    case 'tarjeta_credito':
                        return 'Tarjeta de Crédito';
                    case 'efectivo':
                        return 'Efectivo';
                    case 'transferencia_bancaria':
                        return 'Transferencia Bancaria';
                    default:
                        return metodo;
                }
            },
            
            formatFecha(fecha) {
                if (!fecha) return '';
                
                try {
                    // Intentar convertir a objeto Date
                    const date = new Date(fecha);
                    
                    // Formatear como dd/mm/yyyy
                    return date.getDate().toString().padStart(2, '0') + '/' + 
                           (date.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                           date.getFullYear();
                } catch (e) {
                    console.error('Error al formatear fecha:', e);
                    return fecha; // Devolver la fecha original si hay error
                }
            }
        }));
    });
</script> 