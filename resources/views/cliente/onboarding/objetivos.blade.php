<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Barra de Progreso -->
            <div class="mb-8">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full" style="width: 75%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Paso 3 de 4: Objetivos de Entrenamiento</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Objetivos de Entrenamiento</h2>
                
                <form action="{{ route('onboarding.objetivos.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Objetivo Principal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">¿Cuál es tu objetivo principal?</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="relative border rounded-lg p-4 cursor-pointer hover:bg-emerald-50">
                                <input type="radio" name="objetivo_principal" value="perdida_peso" class="absolute h-4 w-4 top-4 right-4">
                                <div class="flex flex-col items-center text-center">
                                    <svg class="w-8 h-8 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                    <span class="font-medium">Pérdida de Peso</span>
                                </div>
                            </label>

                            <label class="relative border rounded-lg p-4 cursor-pointer hover:bg-emerald-50">
                                <input type="radio" name="objetivo_principal" value="ganancia_muscular" class="absolute h-4 w-4 top-4 right-4">
                                <div class="flex flex-col items-center text-center">
                                    <svg class="w-8 h-8 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span class="font-medium">Ganancia Muscular</span>
                                </div>
                            </label>

                            <label class="relative border rounded-lg p-4 cursor-pointer hover:bg-emerald-50">
                                <input type="radio" name="objetivo_principal" value="mantenimiento" class="absolute h-4 w-4 top-4 right-4">
                                <div class="flex flex-col items-center text-center">
                                    <svg class="w-8 h-8 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="font-medium">Mantenimiento</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Nivel de Experiencia -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Nivel de Experiencia en Entrenamiento</label>
                        <select name="nivel_experiencia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="principiante">Principiante - Nuevo en el entrenamiento</option>
                            <option value="intermedio">Intermedio - 1-2 años de experiencia</option>
                            <option value="avanzado">Avanzado - Más de 2 años de experiencia</option>
                        </select>
                    </div>

                    <!-- Días de Entrenamiento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">¿Cuántos días a la semana planeas entrenar?</label>
                        <select name="dias_entrenamiento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="2-3">2-3 días por semana</option>
                            <option value="3-4">3-4 días por semana</option>
                            <option value="4-5">4-5 días por semana</option>
                            <option value="6+">6 o más días por semana</option>
                        </select>
                    </div>

                    <!-- Condiciones Médicas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">¿Tienes alguna condición médica o lesión que debamos tener en cuenta?</label>
                        <textarea name="condiciones_medicas" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Describe cualquier lesión, condición médica o limitación física que debamos considerar (opcional)"></textarea>
                    </div>

                    <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-blue-700">
                            <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Esta información nos ayudará a crear un plan de entrenamiento personalizado que se ajuste a tus objetivos y necesidades.
                        </p>
                    </div>

                    <!-- Botones de Navegación -->
                    <div class="flex justify-between space-x-4 mt-6">
                        <a href="{{ route('onboarding.medidas') }}" 
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