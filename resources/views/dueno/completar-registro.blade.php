<x-app-layout>
    <div x-data="{ 
        currentStep: 1,
        gimnasioForm: {
            nombre: '',
            direccion: '',
            telefono: '',
            email: '',
            descripcion: '',
            horario: ''
        },
        membresiasForm: {
            tipos: [
                {tipo: 'mensual', precio: '', descripcion: ''},
                {tipo: 'trimestral', precio: '', descripcion: ''},
                {tipo: 'anual', precio: '', descripcion: ''}
            ]
        },
        metodosPagoForm: {
            metodos: [
                {nombre: 'Efectivo', activo: true},
                {nombre: 'Tarjeta', activo: false},
                {nombre: 'Transferencia', activo: false}
            ]
        }
    }" class="py-6 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header con gradiente -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 rounded-xl shadow-lg mb-6">
                <h2 class="text-2xl font-bold text-white mb-2">Completar Registro de tu Gimnasio</h2>
                <p class="text-emerald-100">Configura la información necesaria para comenzar a operar tu gimnasio</p>
                
                <!-- Indicador de pasos -->
                <div class="mt-6 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div :class="{'bg-white text-emerald-600': currentStep === 1, 'bg-emerald-500 text-white': currentStep !== 1}"
                             class="w-8 h-8 rounded-full flex items-center justify-center font-semibold transition-colors duration-200">
                            1
                        </div>
                        <div :class="{'bg-white text-emerald-600': currentStep === 2, 'bg-emerald-500 text-white': currentStep !== 2}"
                             class="w-8 h-8 rounded-full flex items-center justify-center font-semibold transition-colors duration-200">
                            2
                        </div>
                        <div :class="{'bg-white text-emerald-600': currentStep === 3, 'bg-emerald-500 text-white': currentStep !== 3}"
                             class="w-8 h-8 rounded-full flex items-center justify-center font-semibold transition-colors duration-200">
                            3
                        </div>
                    </div>
                    <div class="text-white text-sm">
                        <span x-text="currentStep === 1 ? 'Información del Gimnasio' : currentStep === 2 ? 'Membresías' : 'Métodos de Pago'"></span>
                    </div>
                </div>
            </div>

            <!-- Formularios -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-emerald-100">
                <!-- Paso 1: Información del Gimnasio -->
                <div x-show="currentStep === 1">
                    <form @submit.prevent="$dispatch('submit-gimnasio', gimnasioForm)">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-emerald-700 mb-2">Nombre del Gimnasio</label>
                                <input type="text" x-model="gimnasioForm.nombre" required
                                       class="w-full rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-emerald-700 mb-2">Email</label>
                                <input type="email" x-model="gimnasioForm.email" required
                                       class="w-full rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-emerald-700 mb-2">Teléfono</label>
                                <input type="text" x-model="gimnasioForm.telefono" required
                                       class="w-full rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-emerald-700 mb-2">Horario</label>
                                <input type="text" x-model="gimnasioForm.horario" required
                                       class="w-full rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="Ej: Lun-Vie 6:00-22:00, Sáb 8:00-20:00">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-emerald-700 mb-2">Dirección</label>
                                <input type="text" x-model="gimnasioForm.direccion" required
                                       class="w-full rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-emerald-700 mb-2">Descripción</label>
                                <textarea x-model="gimnasioForm.descripcion" rows="3"
                                          class="w-full rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                    class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200">
                                Siguiente
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Paso 2: Membresías -->
                <div x-show="currentStep === 2">
                    <form @submit.prevent="$dispatch('submit-membresias', membresiasForm)">
                        <div class="space-y-6">
                            <template x-for="(membresia, index) in membresiasForm.tipos" :key="index">
                                <div class="bg-emerald-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700 mb-2" x-text="'Tipo ' + membresia.tipo"></label>
                                            <input type="text" x-model="membresia.tipo" disabled
                                                   class="w-full rounded-lg border-emerald-200 bg-emerald-100 cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700 mb-2">Precio</label>
                                            <input type="number" x-model="membresia.precio" required
                                                   class="w-full rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-emerald-700 mb-2">Descripción</label>
                                            <input type="text" x-model="membresia.descripcion" required
                                                   class="w-full rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <button type="button" @click="currentStep = 1"
                                    class="px-6 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50">
                                Anterior
                            </button>
                            <button type="submit"
                                    class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700">
                                Siguiente
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Paso 3: Métodos de Pago -->
                <div x-show="currentStep === 3">
                    <form @submit.prevent="$dispatch('submit-metodos-pago', metodosPagoForm)">
                        <div class="space-y-4">
                            <template x-for="(metodo, index) in metodosPagoForm.metodos" :key="index">
                                <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-lg">
                                    <div class="flex items-center">
                                        <input type="checkbox" x-model="metodo.activo"
                                               class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700" x-text="metodo.nombre"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <button type="button" @click="currentStep = 2"
                                    class="px-6 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-lg hover:bg-emerald-50">
                                Anterior
                            </button>
                            <button type="submit"
                                    class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700">
                                Finalizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('completarRegistro', () => ({
                // ... Alpine.js data aquí si es necesario
            }))
        })

        // Manejadores de eventos para los formularios
        document.addEventListener('submit-gimnasio', async (e) => {
            try {
                const response = await fetch('{{ route("dueno.guardar-gimnasio") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(e.detail)
                });
                
                const data = await response.json();
                if (data.success) {
                    Alpine.store('currentStep', 2);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        document.addEventListener('submit-membresias', async (e) => {
            try {
                const response = await fetch('{{ route("dueno.guardar-membresias") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(e.detail)
                });
                
                const data = await response.json();
                if (data.success) {
                    Alpine.store('currentStep', 3);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        document.addEventListener('submit-metodos-pago', async (e) => {
            try {
                const response = await fetch('{{ route("dueno.guardar-metodos-pago") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(e.detail)
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.href = '{{ route("dashboard") }}';
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
    @endpush
</x-app-layout> 