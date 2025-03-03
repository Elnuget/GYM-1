<x-app-layout>
    <div x-data="{ 
        isModalOpen: false,
        isEditModalOpen: false,
        currentRutina: null,
        toggleModal() {
            this.isModalOpen = !this.isModalOpen;
        },
        toggleEditModal(rutina = null) {
            this.isEditModalOpen = !this.isEditModalOpen;
            this.currentRutina = rutina;
            if(rutina) {
                this.$nextTick(() => {
                    document.getElementById('edit_nombre_rutina').value = rutina.nombre_rutina;
                    document.getElementById('edit_descripcion').value = rutina.descripcion;
                    document.getElementById('edit_objetivo').value = rutina.objetivo;
                    document.getElementById('edit_estado').value = rutina.estado;
                    document.getElementById('edit_gimnasio_id').value = rutina.gimnasio_id;
                });
            }
        }
    }">
        <div class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con gradiente -->
                <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 p-4 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-white">
                        Rutinas Predefinidas
                    </h2>
                    @if(auth()->check() && in_array(auth()->user()->rol, ['admin', 'entrenador', 'dueño']))
                        <button @click="toggleModal()" 
                                class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold rounded-lg transition duration-150 ease-in-out shadow-md backdrop-blur-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nueva Rutina
                        </button>
                    @endif
                </div>

                <!-- Justo después del header -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Tabla con nuevo diseño -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-emerald-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-emerald-200">
                            <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Gimnasio</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Objetivo</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-emerald-100">
                                @foreach ($rutinas as $rutina)
                                    <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $rutina->nombre_rutina }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $rutina->gimnasio->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ [
                                                'fuerza' => 'Fuerza',
                                                'resistencia' => 'Resistencia',
                                                'tonificacion' => 'Tonificación',
                                                'perdida_peso' => 'Pérdida de Peso',
                                                'ganancia_muscular' => 'Ganancia Muscular',
                                                'flexibilidad' => 'Flexibilidad',
                                                'rehabilitacion' => 'Rehabilitación',
                                                'mantenimiento' => 'Mantenimiento'
                                            ][$rutina->objetivo] ?? $rutina->objetivo }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $rutina->estado === 'activo' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($rutina->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex space-x-3">
                                                @if(auth()->user()->rol === 'admin' || auth()->id() === $rutina->id_usuario)
                                                    <button @click="toggleEditModal({{ $rutina }})" 
                                                            class="text-teal-600 hover:text-teal-900 font-medium">
                                                        Editar
                                                    </button>
                                                    <form action="{{ route('rutinas-predefinidas.destroy', $rutina) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('¿Estás seguro?')" 
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 font-medium">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $rutinas->links() }}
                </div>

                <!-- Modal de Nueva Rutina -->
                <div x-show="isModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Nueva Rutina
                                </h2>
                                <form action="{{ route('rutinas-predefinidas.store') }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nombre de la Rutina</label>
                                            <input type="text" name="nombre_rutina" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Descripción</label>
                                            <textarea name="descripcion" required rows="3"
                                                      class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Gimnasio</label>
                                            <select name="gimnasio_id" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="">Seleccione un gimnasio</option>
                                                @foreach($gimnasios ?? [] as $gimnasio)
                                                    <option value="{{ $gimnasio->id_gimnasio }}">{{ $gimnasio->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Objetivo</label>
                                            <select name="objetivo" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="">Seleccione un objetivo</option>
                                                <option value="fuerza">Fuerza</option>
                                                <option value="resistencia">Resistencia</option>
                                                <option value="tonificacion">Tonificación</option>
                                                <option value="perdida_peso">Pérdida de Peso</option>
                                                <option value="ganancia_muscular">Ganancia Muscular</option>
                                                <option value="flexibilidad">Flexibilidad</option>
                                                <option value="rehabilitacion">Rehabilitación</option>
                                                <option value="mantenimiento">Mantenimiento</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Estado</label>
                                            <select name="estado" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="activo">Activo</option>
                                                <option value="inactivo">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" @click="toggleModal()"
                                                class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-md">
                                            Crear Rutina
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Editar Rutina -->
                <div x-show="isEditModalOpen" 
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative bg-gradient-to-br from-white to-emerald-50 rounded-xl max-w-md w-full shadow-xl">
                            <div class="p-6">
                                <div class="absolute inset-x-0 top-0 bg-gradient-to-r from-emerald-600 to-teal-600 h-2 rounded-t-xl"></div>
                                <h2 class="text-lg font-medium text-emerald-900 mb-6 mt-4">
                                    Editar Rutina
                                </h2>
                                <form x-bind:action="'/rutinas-predefinidas/' + currentRutina?.id_rutina" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Nombre de la Rutina</label>
                                            <input type="text" id="edit_nombre_rutina" name="nombre_rutina" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Descripción</label>
                                            <textarea id="edit_descripcion" name="descripcion" required rows="3"
                                                      class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Gimnasio</label>
                                            <select id="edit_gimnasio_id" name="gimnasio_id" required
                                                   class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                @foreach($gimnasios ?? [] as $gimnasio)
                                                    <option value="{{ $gimnasio->id_gimnasio }}">{{ $gimnasio->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Objetivo</label>
                                            <select id="edit_objetivo" name="objetivo" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="fuerza">Fuerza</option>
                                                <option value="resistencia">Resistencia</option>
                                                <option value="tonificacion">Tonificación</option>
                                                <option value="perdida_peso">Pérdida de Peso</option>
                                                <option value="ganancia_muscular">Ganancia Muscular</option>
                                                <option value="flexibilidad">Flexibilidad</option>
                                                <option value="rehabilitacion">Rehabilitación</option>
                                                <option value="mantenimiento">Mantenimiento</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700">Estado</label>
                                            <select id="edit_estado" name="estado" required
                                                    class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="activo">Activo</option>
                                                <option value="inactivo">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" @click="toggleEditModal()"
                                                class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-md">
                                            Actualizar Rutina
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('membresiaManager', () => ({
            isModalOpen: false,
            isEditModalOpen: false,
            currentRutina: null,
            toggleModal() {
                this.isModalOpen = !this.isModalOpen;
            },
            toggleEditModal(rutina = null) {
                this.isEditModalOpen = !this.isEditModalOpen;
                this.currentRutina = rutina;
                if(rutina) {
                    this.$nextTick(() => {
                        document.getElementById('edit_nombre_rutina').value = rutina.nombre_rutina;
                        document.getElementById('edit_descripcion').value = rutina.descripcion;
                        document.getElementById('edit_objetivo').value = rutina.objetivo;
                        document.getElementById('edit_estado').value = rutina.estado;
                        document.getElementById('edit_gimnasio_id').value = rutina.gimnasio_id;
                    });
                }
            }
        }));
    });
</script> 