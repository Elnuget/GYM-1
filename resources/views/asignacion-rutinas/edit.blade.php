<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Editar Asignación de Rutina') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('asignacion-rutinas.update', $asignacionRutina) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-bold text-gray-700">
                                Cliente
                            </label>
                            <select name="id_usuario" 
                                    class="w-full px-3 py-2 border rounded-lg @error('id_usuario') border-red-500 @enderror">
                                <option value="">Seleccione un cliente</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id_usuario }}" 
                                            {{ old('id_usuario', $asignacionRutina->id_usuario) == $usuario->id_usuario ? 'selected' : '' }}>
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_usuario')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-bold text-gray-700">
                                Rutina
                            </label>
                            <select name="id_rutina" 
                                    class="w-full px-3 py-2 border rounded-lg @error('id_rutina') border-red-500 @enderror">
                                <option value="">Seleccione una rutina</option>
                                @foreach($rutinas as $rutina)
                                    <option value="{{ $rutina->id_rutina }}" 
                                            {{ old('id_rutina', $asignacionRutina->id_rutina) == $rutina->id_rutina ? 'selected' : '' }}>
                                        {{ $rutina->nombre_rutina }} - {{ ucfirst($rutina->objetivo) }}
                                        ({{ $rutina->entrenador->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_rutina')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-bold text-gray-700">
                                Fecha de Asignación
                            </label>
                            <input type="date" 
                                   name="fecha_asignacion" 
                                   class="w-full px-3 py-2 border rounded-lg @error('fecha_asignacion') border-red-500 @enderror"
                                   value="{{ old('fecha_asignacion', $asignacionRutina->fecha_asignacion->format('Y-m-d')) }}">
                            @error('fecha_asignacion')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-bold text-gray-700">
                                Día de la Semana
                            </label>
                            <select name="dia_semana" 
                                    class="w-full px-3 py-2 border rounded-lg @error('dia_semana') border-red-500 @enderror">
                                <option value="">Seleccione un día (opcional)</option>
                                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                    <option value="{{ $dia }}" 
                                            {{ old('dia_semana', $asignacionRutina->dia_semana) == $dia ? 'selected' : '' }}>
                                        {{ $dia }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dia_semana')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('asignacion-rutinas.index') }}" 
                               class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                Actualizar Asignación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 