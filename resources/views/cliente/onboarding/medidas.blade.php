<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Barra de Progreso -->
            <div class="mb-8">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full" style="width: 50%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Paso 2 de 4: Medidas Corporales</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Medidas Corporales</h2>
                
                <form action="{{ route('onboarding.medidas.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Peso (kg)</label>
                            <input type="number" step="0.1" name="peso" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                   placeholder="Ej: 70.5">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Altura (cm)</label>
                            <input type="number" name="altura" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                   placeholder="Ej: 170">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Circunferencia de Cintura (cm) <span class="text-gray-500 text-xs">(Opcional)</span></label>
                            <input type="number" name="cintura" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                   placeholder="Ej: 80">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Circunferencia de Cadera (cm) <span class="text-gray-500 text-xs">(Opcional)</span></label>
                            <input type="number" name="cadera" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                   placeholder="Ej: 90">
                        </div>
                    </div>

                    <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-blue-700">
                            <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Estas medidas nos ayudarán a personalizar mejor tu plan de entrenamiento y seguir tu progreso.
                        </p>
                    </div>

                    <!-- Botones de Navegación -->
                    <div class="flex justify-between space-x-4 mt-6">
                        <a href="{{ route('onboarding.perfil') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            Atrás
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                            Continuar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 