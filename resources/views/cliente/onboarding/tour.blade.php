<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Barra de Progreso -->
            <div class="mb-8">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full" style="width: 100%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Paso 4 de 4: Tour de la Plataforma</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6">¡Bienvenido a GymFlow!</h2>
                
                <div class="space-y-8">
                    <!-- Sección de Características -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dashboard -->
                        <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start">
                                <div class="bg-emerald-100 rounded-full p-3">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold">Dashboard Personal</h3>
                                    <p class="text-gray-600 mt-1">Visualiza tu progreso, próximas sesiones y estadísticas en un solo lugar.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rutinas -->
                        <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start">
                                <div class="bg-emerald-100 rounded-full p-3">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold">Rutinas Personalizadas</h3>
                                    <p class="text-gray-600 mt-1">Accede a tus rutinas de entrenamiento y registra tu progreso diario.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nutrición -->
                        <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start">
                                <div class="bg-emerald-100 rounded-full p-3">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold">Plan Nutricional</h3>
                                    <p class="text-gray-600 mt-1">Consulta recomendaciones nutricionales y lleva un registro de tu alimentación.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Asistencias -->
                        <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start">
                                <div class="bg-emerald-100 rounded-full p-3">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold">Control de Asistencias</h3>
                                    <p class="text-gray-600 mt-1">Registra tus entradas y salidas, y mantén un historial de asistencia.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje Final -->
                    <div class="bg-emerald-50 rounded-lg p-6 mt-8">
                        <h3 class="text-lg font-semibold text-emerald-800 mb-2">¡Todo listo para comenzar!</h3>
                        <p class="text-emerald-600">
                            Has completado tu registro exitosamente. Ahora puedes comenzar a utilizar todas las funcionalidades 
                            de la plataforma para alcanzar tus objetivos fitness.
                        </p>
                    </div>

                    <!-- Botones de Navegación -->
                    <div class="flex justify-between space-x-4 mt-8">
                        <a href="{{ route('onboarding.objetivos') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            Atrás
                        </a>
                        <form action="{{ route('onboarding.tour.complete') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-6 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                                Comenzar a Entrenar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 