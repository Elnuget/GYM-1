<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Barra de Progreso -->
            <div class="mb-8">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full" style="width: 25%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Paso 1 de 4: Información Personal</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Completa tu Perfil</h2>
                
                <form action="{{ route('onboarding.perfil.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Datos Personales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="tel" name="telefono" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Género</label>
                            <select name="genero" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Selecciona...</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="O">Otro</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ocupación</label>
                            <input type="text" name="ocupacion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <!-- Botones de Navegación -->
                    <div class="flex justify-end space-x-4 mt-6">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                            Continuar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 