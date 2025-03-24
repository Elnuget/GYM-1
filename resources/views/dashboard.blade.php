<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header con gradiente -->
            <div class="mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-white">Panel de Control</h2>
            </div>

            <!-- Mensaje de Bienvenida -->
            <div class="mb-6 bg-white p-6 rounded-lg shadow-md border border-emerald-100">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">¡Bienvenido al Sistema!</h3>
                        <p class="text-gray-600">Gestiona tu gimnasio de manera eficiente con GymFlow</p>
                    </div>
                </div>
            </div>

            <!-- Tarjetas Informativas -->
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
                                            $usuarioActual = auth()->user();
                                            $clientesNuevosMes = 0;
                                            
                                            if ($usuarioActual->hasRole('dueño')) {
                                                $duenoGimnasio = \App\Models\DuenoGimnasio::where('user_id', $usuarioActual->id)->first();
                                                if ($duenoGimnasio) {
                                                    $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                                                    $clientesNuevosMes = \App\Models\Cliente::whereIn('gimnasio_id', $gimnasiosIds)
                                                        ->whereMonth('created_at', $mesActual)
                                                        ->whereYear('created_at', $añoActual)
                                                        ->count();
                                                }
                                            } else {
                                                $clientesNuevosMes = \App\Models\Cliente::whereMonth('created_at', $mesActual)
                                                    ->whereYear('created_at', $añoActual)
                                                    ->count();
                                            }
                                        @endphp
                                        {{ $clientesNuevosMes }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('clientes.index') }}" class="w-full bg-emerald-100 hover:bg-emerald-200 text-emerald-800 text-sm font-medium py-2 px-4 rounded-md transition-colors duration-150 inline-block text-center">
                                Ver todos
                            </a>
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
                                            $usuarioActual = auth()->user();
                                            $clientesEnInstalaciones = 0;
                                            
                                            if ($usuarioActual->hasRole('dueño')) {
                                                $duenoGimnasio = \App\Models\DuenoGimnasio::where('user_id', $usuarioActual->id)->first();
                                                if ($duenoGimnasio) {
                                                    $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                                                    $clientesEnInstalaciones = \App\Models\Asistencia::whereHas('cliente', function($q) use ($gimnasiosIds) {
                                                        $q->whereIn('gimnasio_id', $gimnasiosIds);
                                                    })
                                                    ->whereDate('fecha', \Carbon\Carbon::today())
                                                    ->whereNull('hora_salida')
                                                    ->count();
                                                }
                                            } else {
                                                $clientesEnInstalaciones = \App\Models\Asistencia::whereDate('fecha', \Carbon\Carbon::today())
                                                    ->whereNull('hora_salida')
                                                    ->count();
                                            }
                                        @endphp
                                        {{ $clientesEnInstalaciones }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('clientes.index') }}" class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium py-2 px-4 rounded-md transition-colors duration-150 inline-block text-center">
                                Ver todos
                            </a>
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
                                            $usuarioActual = auth()->user();
                                            $membresiasVencidas = 0;
                                            
                                            if ($usuarioActual->hasRole('dueño')) {
                                                $duenoGimnasio = \App\Models\DuenoGimnasio::where('user_id', $usuarioActual->id)->first();
                                                if ($duenoGimnasio) {
                                                    $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                                                    $membresiasVencidas = \App\Models\Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                                                        $q->whereIn('gimnasio_id', $gimnasiosIds);
                                                    })
                                                    ->where('fecha_vencimiento', '<', \Carbon\Carbon::now())
                                                    ->count();
                                                }
                                            } else {
                                                $membresiasVencidas = \App\Models\Membresia::where('fecha_vencimiento', '<', \Carbon\Carbon::now())->count();
                                            }
                                        @endphp
                                        {{ $membresiasVencidas }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('clientes.index') }}" class="w-full bg-amber-100 hover:bg-amber-200 text-amber-800 text-sm font-medium py-2 px-4 rounded-md transition-colors duration-150 inline-block text-center">
                                Ver todos
                            </a>
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
                                            $usuarioActual = auth()->user();
                                            $pagosPendientes = 0;
                                            
                                            if ($usuarioActual->hasRole('dueño')) {
                                                $duenoGimnasio = \App\Models\DuenoGimnasio::where('user_id', $usuarioActual->id)->first();
                                                if ($duenoGimnasio) {
                                                    $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                                                    $pagosPendientes = \App\Models\Pago::whereHas('membresia.tipoMembresia', function($q) use ($gimnasiosIds) {
                                                        $q->whereIn('gimnasio_id', $gimnasiosIds);
                                                    })
                                                    ->where('estado', 'pendiente')
                                                    ->count();
                                                }
                                            } else {
                                                $pagosPendientes = \App\Models\Pago::where('estado', 'pendiente')->count();
                                            }
                                        @endphp
                                        {{ $pagosPendientes }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('clientes.index') }}" class="w-full bg-red-100 hover:bg-red-200 text-red-800 text-sm font-medium py-2 px-4 rounded-md transition-colors duration-150 inline-block text-center">
                                Ver todos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="bg-white rounded-lg shadow-md border border-emerald-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Accesos Rápidos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Clientes -->
                        <a href="{{ route('clientes.index') }}" class="flex items-center p-4 bg-white border rounded-lg shadow-sm hover:bg-emerald-50 transition-colors duration-150">
                            <div class="flex-shrink-0 bg-emerald-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-medium text-gray-900">Clientes</h4>
                                <p class="text-sm text-gray-500">Gestionar clientes</p>
                            </div>
                        </a>

                        <!-- Membresías -->
                        <a href="{{ route('membresias.index') }}" class="flex items-center p-4 bg-white border rounded-lg shadow-sm hover:bg-emerald-50 transition-colors duration-150">
                            <div class="flex-shrink-0 bg-emerald-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-medium text-gray-900">Membresías</h4>
                                <p class="text-sm text-gray-500">Control de membresías</p>
                            </div>
                        </a>

                        <!-- Pagos -->
                        <a href="{{ route('pagos.index') }}" class="flex items-center p-4 bg-white border rounded-lg shadow-sm hover:bg-emerald-50 transition-colors duration-150">
                            <div class="flex-shrink-0 bg-emerald-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-medium text-gray-900">Pagos</h4>
                                <p class="text-sm text-gray-500">Gestión de pagos</p>
                            </div>
                        </a>

                        <!-- Asistencias -->
                        <a href="{{ route('asistencias.index') }}" class="flex items-center p-4 bg-white border rounded-lg shadow-sm hover:bg-emerald-50 transition-colors duration-150">
                            <div class="flex-shrink-0 bg-emerald-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-medium text-gray-900">Asistencias</h4>
                                <p class="text-sm text-gray-500">Control de asistencia</p>
                            </div>
                        </a>

                        <!-- Gimnasios -->
                        <a href="{{ route('gimnasios.index') }}" class="flex items-center p-4 bg-white border rounded-lg shadow-sm hover:bg-emerald-50 transition-colors duration-150">
                            <div class="flex-shrink-0 bg-emerald-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-medium text-gray-900">Gimnasios</h4>
                                <p class="text-sm text-gray-500">Gestión de gimnasios</p>
                            </div>
                        </a>

                        <!-- Tipos de Membresía -->
                        <a href="{{ route('tipos-membresia.index') }}" class="flex items-center p-4 bg-white border rounded-lg shadow-sm hover:bg-emerald-50 transition-colors duration-150">
                            <div class="flex-shrink-0 bg-emerald-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-medium text-gray-900">Tipos de Membresía</h4>
                                <p class="text-sm text-gray-500">Configurar membresías</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
