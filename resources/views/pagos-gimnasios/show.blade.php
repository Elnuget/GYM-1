<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Pago') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="space-y-6">
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Dueño del Gimnasio</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ $pago->dueno->nombre_comercial }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Monto</h5>
                            <p class="mt-1 text-lg text-gray-900">${{ number_format($pago->monto, 2) }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Fecha de Pago</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ $pago->fecha_pago->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Estado</h5>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $pago->estado === 'pagado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($pago->estado) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Método de Pago</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ str_replace('_', ' ', ucfirst($pago->metodo_pago)) }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Fecha de Registro</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ $pago->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Última Actualización</h5>
                            <p class="mt-1 text-lg text-gray-900">{{ $pago->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div class="flex justify-between pt-6">
                            <a href="{{ route('pagos-gimnasios.index') }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                Volver
                            </a>
                            <div class="space-x-2">
                                <a href="{{ route('pagos-gimnasios.edit', $pago) }}" 
                                   class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                    Editar
                                </a>
                                <form action="{{ route('pagos-gimnasios.destroy', $pago) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200"
                                            onclick="return confirm('¿Está seguro de eliminar este pago?')">
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