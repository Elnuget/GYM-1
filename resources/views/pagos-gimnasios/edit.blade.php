<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Pago') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('pagos-gimnasios.update', $pago) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="dueno_id" class="block text-sm font-medium text-gray-700">Dueño del Gimnasio</label>
                            <select id="dueno_id" name="dueno_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md @error('dueno_id') border-red-500 @enderror">
                                <option value="">Seleccione un dueño</option>
                                @foreach($duenos as $dueno)
                                    <option value="{{ $dueno->id_dueno }}" {{ (old('dueno_id', $pago->dueno_id) == $dueno->id_dueno) ? 'selected' : '' }}>
                                        {{ $dueno->nombre_comercial }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dueno_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="monto" class="block text-sm font-medium text-gray-700">Monto</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="monto" id="monto" step="0.01" min="0" 
                                    value="{{ old('monto', $pago->monto) }}" required
                                    class="pl-7 mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('monto') border-red-500 @enderror">
                            </div>
                            @error('monto')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fecha_pago" class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                            <input type="date" name="fecha_pago" id="fecha_pago" 
                                value="{{ old('fecha_pago', $pago->fecha_pago->format('Y-m-d')) }}" required
                                class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('fecha_pago') border-red-500 @enderror">
                            @error('fecha_pago')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select id="estado" name="estado" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md @error('estado') border-red-500 @enderror">
                                <option value="pendiente" {{ old('estado', $pago->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="pagado" {{ old('estado', $pago->estado) == 'pagado' ? 'selected' : '' }}>Pagado</option>
                            </select>
                            @error('estado')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="metodo_pago" class="block text-sm font-medium text-gray-700">Método de Pago</label>
                            <select id="metodo_pago" name="metodo_pago" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md @error('metodo_pago') border-red-500 @enderror">
                                <option value="">Seleccione un método de pago</option>
                                @foreach($metodos_pago as $valor => $texto)
                                    <option value="{{ $valor }}" {{ old('metodo_pago', $pago->metodo_pago) == $valor ? 'selected' : '' }}>
                                        {{ $texto }}
                                    </option>
                                @endforeach
                            </select>
                            @error('metodo_pago')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between pt-4">
                            <a href="{{ route('pagos-gimnasios.index') }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                Actualizar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 