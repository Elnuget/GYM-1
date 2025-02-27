<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Detalles del Pago</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Membresía</dt>
                                    <dd class="mt-1 text-lg text-gray-900">{{ $pago->membresia->nombre }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Pago</dt>
                                    <dd class="mt-1 text-lg text-gray-900">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Monto</dt>
                                    <dd class="mt-1 text-lg text-gray-900">${{ number_format($pago->monto, 2) }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Método de Pago</dt>
                                    <dd class="mt-1 text-lg text-gray-900">{{ ucfirst($pago->metodo_pago) }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-sm font-semibold rounded-full 
                                            {{ $pago->estado === 'aprobado' ? 'bg-green-100 text-green-800' : 
                                               ($pago->estado === 'rechazado' ? 'bg-red-100 text-red-800' : 
                                               'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($pago->estado) }}
                                        </span>
                                    </dd>
                                </div>

                                @if($pago->fecha_aprobacion)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Aprobación</dt>
                                    <dd class="mt-1 text-lg text-gray-900">{{ $pago->fecha_aprobacion->format('d/m/Y H:i') }}</dd>
                                </div>
                                @endif

                                @if($pago->notas)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Notas</dt>
                                    <dd class="mt-1 text-lg text-gray-900">{{ $pago->notas }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        @if($pago->comprobante_url)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Comprobante de Pago</h3>
                            <img src="{{ Storage::url($pago->comprobante_url) }}" 
                                 alt="Comprobante de pago"
                                 class="max-w-full h-auto rounded-lg shadow-lg">
                        </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('cliente.pagos.index') }}" 
                           class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                            Volver a Pagos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-cliente-layout> 