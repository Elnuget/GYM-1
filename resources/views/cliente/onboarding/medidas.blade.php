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
                <h2 class="text-2xl font-bold mb-6">Medidas Corporales</h2>

                <form method="POST" action="{{ route('onboarding.medidas.store') }}">
                    @csrf

                    <!-- Medidas Básicas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="peso" class="block text-sm font-medium text-gray-700 mb-1">
                                Peso (kg)
                            </label>
                            <input type="number" step="0.1" name="peso" id="peso" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                   placeholder="Ej: 70.5" required>
                        </div>

                        <div>
                            <label for="altura" class="block text-sm font-medium text-gray-700 mb-1">
                                Altura (cm)
                            </label>
                            <input type="number" name="altura" id="altura" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                   placeholder="Ej: 170" required>
                        </div>
                    </div>

                    <!-- Medidas Detalladas (Acordeón) -->
                    <div x-data="{ open: false }" class="mb-8">
                        <button type="button" 
                                @click="open = !open"
                                class="flex items-center justify-between w-full px-4 py-2 bg-gray-50 rounded-lg hover:bg-gray-100 focus:outline-none">
                            <span class="text-lg font-medium">Medidas del Cuerpo (Opcional)</span>
                            <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-200" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-4 rounded-lg border">
                            
                            <!-- Cuello -->
                            <div>
                                <label for="cuello" class="block text-sm font-medium text-gray-700 mb-1">
                                    Cuello (cm)
                                    <span class="text-xs text-gray-500 block">Se mide por debajo de la nuez</span>
                                </label>
                                <input type="number" step="0.1" name="cuello" id="cuello" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="Ej: 35">
                            </div>

                            <!-- Hombros -->
                            <div>
                                <label for="hombros" class="block text-sm font-medium text-gray-700 mb-1">
                                    Hombros (cm)
                                    <span class="text-xs text-gray-500 block">Medir horizontalmente por debajo de la clavícula</span>
                                </label>
                                <input type="number" step="0.1" name="hombros" id="hombros" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="Ej: 110">
                            </div>

                            <!-- Pecho -->
                            <div>
                                <label for="pecho" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pecho (cm)
                                    <span class="text-xs text-gray-500 block">Medir a la altura de los pezones, paralelo al suelo</span>
                                </label>
                                <input type="number" step="0.1" name="pecho" id="pecho" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="Ej: 95">
                            </div>

                            <!-- Cintura -->
                            <div>
                                <label for="cintura" class="block text-sm font-medium text-gray-700 mb-1">
                                    Cintura (cm)
                                    <span class="text-xs text-gray-500 block">Medir la porción más pequeña cerca del ombligo</span>
                                </label>
                                <input type="number" step="0.1" name="cintura" id="cintura" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="Ej: 80">
                            </div>

                            <!-- Cadera -->
                            <div>
                                <label for="cadera" class="block text-sm font-medium text-gray-700 mb-1">
                                    Cadera (cm)
                                    <span class="text-xs text-gray-500 block">Medir la parte más ancha de las caderas/glúteos</span>
                                </label>
                                <input type="number" step="0.1" name="cadera" id="cadera" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="Ej: 90">
                            </div>

                            <!-- Bíceps -->
                            <div>
                                <label for="biceps" class="block text-sm font-medium text-gray-700 mb-1">
                                    Bíceps (cm)
                                    <span class="text-xs text-gray-500 block">Medir con el brazo contraído, en la parte más ancha</span>
                                </label>
                                <input type="number" step="0.1" name="biceps" id="biceps" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="Ej: 32">
                            </div>

                            <!-- Antebrazos -->
                            <div>
                                <label for="antebrazos" class="block text-sm font-medium text-gray-700 mb-1">
                                    Antebrazos (cm)
                                    <span class="text-xs text-gray-500 block">Medir en la parte más gruesa por debajo del codo</span>
                                </label>
                                <input type="number" step="0.1" name="antebrazos" id="antebrazos" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="Ej: 28">
                            </div>

                            <!-- Muslos -->
                            <div>
                                <label for="muslos" class="block text-sm font-medium text-gray-700 mb-1">
                                    Muslos (cm)
                                    <span class="text-xs text-gray-500 block">Medir la parte más gruesa bajo la nalga</span>
                                </label>
                                <input type="number" step="0.1" name="muslos" id="muslos" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="Ej: 55">
                            </div>

                            <!-- Pantorrillas -->
                            <div>
                                <label for="pantorrillas" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pantorrillas (cm)
                                    <span class="text-xs text-gray-500 block">Medir en la parte más gruesa de la pantorrilla</span>
                                </label>
                                <input type="number" step="0.1" name="pantorrillas" id="pantorrillas" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="Ej: 38">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-8">
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