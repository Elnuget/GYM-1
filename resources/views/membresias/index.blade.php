<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Membresías') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <button onclick="openCreateModal()" 
                                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                            Nueva Membresía
                        </button>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Cliente
                                </th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Tipo
                                </th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Vencimiento
                                </th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Visitas
                                </th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($membresias as $membresia)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $membresia->usuario->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ ucfirst($membresia->tipo_membresia) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $membresia->fecha_vencimiento->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($membresia->tipo_membresia === 'por_visitas')
                                            {{ $membresia->visitas_restantes }}/{{ $membresia->visitas_permitidas }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <button onclick="openEditModal({{ $membresia->id }})" 
                                                    class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                                Editar
                                            </button>
                                            
                                            @if($membresia->tipo_membresia === 'por_visitas')
                                                <form action="{{ route('membresias.registrar-visita', $membresia) }}" 
                                                      method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="px-3 py-1 text-white bg-green-500 rounded hover:bg-green-600">
                                                        Registrar Visita
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('membresias.destroy', $membresia) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('¿Estás seguro?')" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $membresias->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Creación -->
    <div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            
            <div class="relative bg-white rounded-lg w-full max-w-2xl">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Nueva Membresía</h3>
                    <form id="createForm" action="{{ route('membresias.store') }}" method="POST">
                        @csrf
                        <!-- Contenido del formulario de creación -->
                        <!-- Se cargará dinámicamente -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            
            <div class="relative bg-white rounded-lg w-full max-w-2xl">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Editar Membresía</h3>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Contenido del formulario de edición -->
                        <!-- Se cargará dinámicamente -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            fetch('{{ route("membresias.create") }}')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('createForm').innerHTML = html;
                    document.getElementById('createModal').classList.remove('hidden');
                });
        }

        function openEditModal(id) {
            fetch(`/membresias/${id}/edit`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editForm').innerHTML = html;
                    document.getElementById('editForm').action = `/membresias/${id}`;
                    document.getElementById('editModal').classList.remove('hidden');
                });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Cerrar modales al hacer clic fuera de ellos
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('fixed inset-0');
            for (const modal of modals) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            }
        }
    </script>
</x-app-layout> 