<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">
                    Métodos de Pago
                </h2>
                <button 
                    type="button"
                    @click="$dispatch('open-modal', 'create-metodo-pago')"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nuevo Método de Pago</span>
                </button>
            </div>

            <!-- Mensajes de éxito/error -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tabla -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($metodosPago as $metodo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        @switch($metodo->nombre_metodo)
                                            @case('tarjeta_credito')
                                                Tarjeta de Crédito
                                                @break
                                            @case('efectivo')
                                                Efectivo
                                                @break
                                            @case('transferencia_bancaria')
                                                Transferencia Bancaria
                                                @break
                                            @default
                                                {{ $metodo->nombre_metodo }}
                                        @endswitch
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $metodo->descripcion ?? 'Sin descripción' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $metodo->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $metodo->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button 
                                            type="button"
                                            @click="$dispatch('open-modal', 'edit-metodo-pago-{{ $metodo->id_metodo_pago }}')"
                                            class="text-emerald-600 hover:text-emerald-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <form action="{{ route('metodos-pago.destroy', $metodo) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('¿Estás seguro?')" 
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $metodosPago->links() }}
            </div>
        </div>
    </div>

    <!-- Modal para Crear -->
    <x-modal name="create-metodo-pago">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Crear Nuevo Método de Pago
            </h2>

            <form method="POST" action="{{ route('metodos-pago.store') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <x-input-label for="nombre_metodo" value="Nombre del Método" />
                        <select name="nombre_metodo" id="nombre_metodo" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="tarjeta_credito">Tarjeta de Crédito</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia_bancaria">Transferencia Bancaria</option>
                        </select>
                        <x-input-error :messages="$errors->get('nombre_metodo')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="descripcion" value="Descripción" />
                        <x-text-input id="descripcion" name="descripcion" type="text" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                    </div>

                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="activo" value="1" checked
                                   class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-600">Activo</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button type="button" @click="show = false">
                        Cancelar
                    </x-secondary-button>
                    <x-primary-button type="submit">
                        Guardar
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modales para Editar -->
    @foreach ($metodosPago as $metodo)
        <x-modal name="edit-metodo-pago-{{ $metodo->id_metodo_pago }}">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Editar Método de Pago
                </h2>

                <form method="POST" action="{{ route('metodos-pago.update', $metodo) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <x-input-label for="nombre_metodo" value="Nombre del Método" />
                            <select name="nombre_metodo" id="nombre_metodo" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="tarjeta_credito" {{ $metodo->nombre_metodo == 'tarjeta_credito' ? 'selected' : '' }}>
                                    Tarjeta de Crédito
                                </option>
                                <option value="efectivo" {{ $metodo->nombre_metodo == 'efectivo' ? 'selected' : '' }}>
                                    Efectivo
                                </option>
                                <option value="transferencia_bancaria" {{ $metodo->nombre_metodo == 'transferencia_bancaria' ? 'selected' : '' }}>
                                    Transferencia Bancaria
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('nombre_metodo')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="descripcion" value="Descripción" />
                            <x-text-input id="descripcion" name="descripcion" type="text" 
                                class="mt-1 block w-full" :value="old('descripcion', $metodo->descripcion)" />
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="activo" value="1" {{ $metodo->activo ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-600">Activo</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <x-secondary-button type="button" @click="show = false">
                            Cancelar
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            Actualizar
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endforeach
</x-app-layout> 