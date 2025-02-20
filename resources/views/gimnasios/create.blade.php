<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Gimnasio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('gimnasios.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="dueno_id" class="block text-sm font-medium text-gray-700">Dueño del Gimnasio</label>
                            <select id="dueno_id" name="dueno_id" required 
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md @error('dueno_id') border-red-500 @enderror">
                                <option value="">Seleccione un dueño</option>
                                @foreach($duenos as $dueno)
                                    <option value="{{ $dueno->id }}" {{ old('dueno_id') == $dueno->id ? 'selected' : '' }}>
                                        {{ $dueno->name }} - {{ $dueno->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dueno_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Gimnasio</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                                class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('nombre') border-red-500 @enderror">
                            @error('nombre')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" required
                                class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('direccion') border-red-500 @enderror">
                            @error('direccion')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                                class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('telefono') border-red-500 @enderror">
                            @error('telefono')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('gimnasios.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                Crear Gimnasio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 