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

            <!-- Accesos Rápidos -->
            <div class="bg-white rounded-lg shadow-md border border-emerald-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">ENTRENAMIENTO</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Rutinas Predefinidas -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-orange-700">Rutinas Predefinidas</h4>
                                <svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-orange-600 mb-4">Gestionar rutinas de entrenamiento</p>
                            <a href="{{ route('rutinas-predefinidas.index') }}" class="inline-flex items-center text-orange-700 hover:text-orange-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Asignación de Rutinas -->
                        <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-pink-700">Asignación de Rutinas</h4>
                                <svg class="h-8 w-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <p class="text-pink-600 mb-4">Asignar rutinas a clientes</p>
                            <a href="{{ route('asignacion-rutinas.index') }}" class="inline-flex items-center text-pink-700 hover:text-pink-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">GESTIÓN</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Roles y Permisos -->
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-indigo-700">Roles y Permisos</h4>
                                <svg class="h-8 w-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <p class="text-indigo-600 mb-4">Gestionar roles y permisos</p>
                            <a href="{{ route('roles.index') }}" class="inline-flex items-center text-indigo-700 hover:text-indigo-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Usuarios -->
                        <div class="bg-gradient-to-br from-violet-50 to-violet-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-violet-700">Usuarios</h4>
                                <svg class="h-8 w-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <p class="text-violet-600 mb-4">Administrar usuarios del sistema</p>
                            <a href="{{ route('users.index') }}" class="inline-flex items-center text-violet-700 hover:text-violet-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Dueños de Gimnasios -->
                        <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-cyan-700">Dueños de Gimnasios</h4>
                                <svg class="h-8 w-8 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <p class="text-cyan-600 mb-4">Gestionar dueños de gimnasios</p>
                            <a href="{{ route('duenos-gimnasio.index') }}" class="inline-flex items-center text-cyan-700 hover:text-cyan-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Gimnasios -->
                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-emerald-700">Gimnasios</h4>
                                <svg class="h-8 w-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <p class="text-emerald-600 mb-4">Administrar gimnasios</p>
                            <a href="{{ route('gimnasios.index') }}" class="inline-flex items-center text-emerald-700 hover:text-emerald-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Pagos de Gimnasios -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-green-700">Pagos de Gimnasios</h4>
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-green-600 mb-4">Gestionar pagos de gimnasios</p>
                            <a href="{{ route('pagos-gimnasios.index') }}" class="inline-flex items-center text-green-700 hover:text-green-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Clientes -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-blue-700">Clientes</h4>
                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-blue-600 mb-4">Gestionar clientes</p>
                            <a href="{{ route('clientes.index') }}" class="inline-flex items-center text-blue-700 hover:text-blue-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">OTROS</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Control de Asistencias -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-purple-700">Control de Asistencias</h4>
                                <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-purple-600 mb-4">Registrar entradas y salidas</p>
                            <a href="{{ route('asistencias.index') }}" class="inline-flex items-center text-purple-700 hover:text-purple-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Nutrición -->
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-yellow-700">Nutrición</h4>
                                <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                                </svg>
                            </div>
                            <p class="text-yellow-600 mb-4">Gestionar planes nutricionales</p>
                            <a href="{{ route('nutricion.index') }}" class="inline-flex items-center text-yellow-700 hover:text-yellow-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Implementos -->
                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg p-6 transform transition-all hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-teal-700">Implementos</h4>
                                <svg class="h-8 w-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <p class="text-teal-600 mb-4">Gestionar implementos</p>
                            <a href="{{ route('implementos.index') }}" class="inline-flex items-center text-teal-700 hover:text-teal-900">
                                Acceder <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
