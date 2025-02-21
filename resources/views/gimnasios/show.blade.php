<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Gimnasio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="space-y-6">
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Nombre del Gimnasio</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ $gimnasio->nombre }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Dueño</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ $gimnasio->dueno->name }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Dirección</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ $gimnasio->direccion }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Teléfono</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ $gimnasio->telefono ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Fecha de Creación</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ $gimnasio->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Última Actualización</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ $gimnasio->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div class="flex justify-between pt-6">
                            <a href="{{ route('gimnasios.index') }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                Volver
                            </a>
                            <div class="space-x-2">
                                <a href="{{ route('gimnasios.edit', $gimnasio) }}" 
                                   class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                    Editar
                                </a>
                                <form action="{{ route('gimnasios.destroy', $gimnasio) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200"
                                            onclick="return confirm('¿Está seguro de eliminar este gimnasio?')">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 