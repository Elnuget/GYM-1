<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="gimnasio_id" class="block text-sm font-medium text-gray-700">Gimnasio</label>
                            <select id="gimnasio_id" name="gimnasio_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md @error('gimnasio_id') border-red-500 @enderror">
                                <option value="">Seleccione un gimnasio</option>
                                @foreach($gimnasios as $gimnasio)
                                    <option value="{{ $gimnasio->id_gimnasio }}" {{ (old('gimnasio_id', $cliente->gimnasio_id) == $gimnasio->id_gimnasio) ? 'selected' : '' }}>
                                        {{ $gimnasio->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gimnasio_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $cliente->nombre) }}" required
                                class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('nombre') border-red-500 @enderror">
                            @error('nombre')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $cliente->email) }}" required
                                class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                                class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('telefono') border-red-500 @enderror">
                            @error('telefono')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                                value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('Y-m-d') : '') }}"
                                class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('fecha_nacimiento') border-red-500 @enderror">
                            @error('fecha_nacimiento')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between pt-4">
                            <a href="{{ route('clientes.index') }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                Actualizar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 