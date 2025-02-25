<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">Mi Plan Nutricional</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Plan Actual -->
                        <div class="border rounded-lg p-6">
                            <h3 class="text-xl font-semibold mb-4">Plan Actual</h3>
                            <p class="text-gray-600 mb-4">
                                Aquí podrás ver y seguir tu plan nutricional actual.
                            </p>
                            <a href="{{ route('cliente.nutricion.plan-actual') }}" 
                               class="inline-flex items-center text-emerald-600 hover:text-emerald-700">
                                Ver plan actual →
                            </a>
                        </div>

                        <!-- Historial -->
                        <div class="border rounded-lg p-6">
                            <h3 class="text-xl font-semibold mb-4">Historial de Planes</h3>
                            <p class="text-gray-600 mb-4">
                                Revisa tus planes nutricionales anteriores y tu progreso.
                            </p>
                            <a href="{{ route('cliente.nutricion.historial') }}" 
                               class="inline-flex items-center text-emerald-600 hover:text-emerald-700">
                                Ver historial →
                            </a>
                        </div>
                    </div>

                    <!-- Contenido Temporal -->
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 text-center">
                            Próximamente: Seguimiento detallado de calorías, macronutrientes y más funcionalidades.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-cliente-layout> 