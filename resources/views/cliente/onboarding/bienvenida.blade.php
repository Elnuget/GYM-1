<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Barra de Progreso -->
            <div class="mb-8">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full" style="width: {{ $progreso }}%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Progreso del tutorial: {{ $progreso }}%</p>
            </div>

            <!-- Contenido de Bienvenida -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">¡Bienvenido a GymFlow!</h2>
                <p class="mb-4">Para ayudarte a comenzar, te guiaremos a través de algunos pasos importantes:</p>

                <!-- Lista de Pasos -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center">
                            <span class="text-emerald-600 font-semibold">1</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold">Completa tu Perfil</h3>
                            <p class="text-sm text-gray-600">Información básica para personalizar tu experiencia</p>
                        </div>
                    </div>
                    <!-- Más pasos aquí... -->
                </div>

                <!-- Botón para Comenzar -->
                <div class="mt-8">
                    <a href="{{ route('onboarding.perfil') }}" 
                       class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                        Comenzar Tutorial
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 