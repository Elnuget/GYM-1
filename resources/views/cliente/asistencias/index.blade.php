<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">Control de Asistencias</h2>

                    <!-- Estado Actual -->
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-6 mb-8">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-emerald-800">
                                    Registro de Hoy
                                </h3>
                                <p class="text-emerald-600 mt-1">
                                    {{ now()->format('d/m/Y') }}
                                </p>
                            </div>
                            
                            @if($asistenciaActual)
                                <span class="px-4 py-2 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium">
                                    Sesión Activa
                                </span>
                            @endif
                        </div>

                        <div class="mt-6">
                            @if($asistenciaActual)
                                <div class="bg-white rounded-lg p-4 shadow-sm border border-emerald-100">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-500">Hora de entrada</p>
                                            <p class="font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($asistenciaActual->hora_entrada)->format('H:i') }}
                                            </p>
                                        </div>
                                        <form action="{{ route('cliente.asistencias.salida', $asistenciaActual->id_asistencia) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                                Registrar Salida
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('cliente.asistencias.entrada') }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                        </svg>
                                        Registrar Entrada
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Historial de Asistencias -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Historial de Asistencias</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Entrada
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Salida
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Duración
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($asistencias as $asistencia)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($asistencia->hora_entrada)->format('H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $asistencia->hora_salida ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $asistencia->duracion_formateada }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No hay registros de asistencias previas
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-4">
                            {{ $asistencias->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</x-cliente-layout> 