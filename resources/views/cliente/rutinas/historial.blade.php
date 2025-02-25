<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">Historial de Rutinas</h2>

                    @if($rutinas->isNotEmpty())
                        <div class="space-y-6">
                            @foreach($rutinas as $rutina)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium">{{ $rutina->rutina->nombre_rutina }}</h3>
                                            <p class="text-gray-600">{{ $rutina->rutina->objetivo }}</p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-sm
                                            @if($rutina->estado === 'completada') 
                                                bg-green-100 text-green-800
                                            @else 
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($rutina->estado) }}
                                        </span>
                                    </div>

                                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Fecha inicio:</span>
                                            <p class="font-medium">{{ $rutina->fecha_inicio->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Fecha fin:</span>
                                            <p class="font-medium">{{ $rutina->fecha_fin ? $rutina->fecha_fin->format('d/m/Y') : 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Progreso final:</span>
                                            <p class="font-medium">{{ $rutina->progreso }}%</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Nivel:</span>
                                            <p class="font-medium">{{ ucfirst($rutina->rutina->nivel) }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex justify-end">
                                        <a href="{{ route('cliente.rutinas.show', $rutina->id_rutina_cliente) }}" 
                                           class="text-emerald-600 hover:text-emerald-700">
                                            Ver detalles →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $rutinas->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500">No hay rutinas anteriores para mostrar.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-cliente-layout> 