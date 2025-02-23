<x-guest-layout>
    <div x-data="{ 
        userType: 'cliente',
        showModal: false,
        modalType: '',
        modalMessage: ''
    }" class="flex gap-8">
        <!-- Columna de botones a la izquierda -->
        <div class="w-1/3 space-y-4">
            <!-- Botón para Dueños de Gimnasio -->
            <div @click="userType = 'dueno'" 
                 :class="{'ring-2 ring-emerald-500 bg-emerald-50': userType === 'dueno'}"
                 class="cursor-pointer rounded-xl border p-6 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-100 rounded-full p-3 w-14 h-14 flex-shrink-0">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Tengo un Gimnasio</h3>
                        <p class="text-sm text-gray-600">Registra tu gimnasio y gestiona tu negocio</p>
                    </div>
                </div>
            </div>

            <!-- Botón para Clientes -->
            <div @click="userType = 'cliente'"
                 :class="{'ring-2 ring-emerald-500 bg-emerald-50': userType === 'cliente'}"
                 class="cursor-pointer rounded-xl border p-6 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-100 rounded-full p-3 w-14 h-14 flex-shrink-0">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Quiero Inscribirme</h3>
                        <p class="text-sm text-gray-600">Únete como miembro y comienza tu entrenamiento</p>
                    </div>
                </div>
            </div>

            <!-- Botón para Empleados -->
            <div @click="userType = 'empleado'"
                 :class="{'ring-2 ring-emerald-500 bg-emerald-50': userType === 'empleado'}"
                 class="cursor-pointer rounded-xl border p-6 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-100 rounded-full p-3 w-14 h-14 flex-shrink-0">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Trabajo en un Gimnasio</h3>
                        <p class="text-sm text-gray-600">Regístrate como empleado del gimnasio</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor de formularios a la derecha -->
        <div class="w-2/3">
            <!-- Formulario para Clientes -->
            <form x-show="userType === 'cliente'" method="POST" action="{{ route('register.cliente') }}" class="bg-white p-8 rounded-xl shadow-sm border max-w-2xl">
                @csrf
                <input type="hidden" name="role" value="cliente">

                <div>
                    <x-input-label for="nombre" :value="__('Nombre Completo')" />
                    <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus />
                    <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="gimnasio_id" :value="__('Selecciona tu Gimnasio')" />
                    <select id="gimnasio_id" name="gimnasio_id" required
                            class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Selecciona un gimnasio</option>
                        @foreach($gimnasios as $gimnasio)
                            <option value="{{ $gimnasio->id_gimnasio }}" {{ old('gimnasio_id') == $gimnasio->id_gimnasio ? 'selected' : '' }}>
                                {{ $gimnasio->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('gimnasio_id')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" href="{{ route('login') }}">
                        {{ __('¿Ya estás registrado?') }}
                    </a>

                    <x-primary-button class="ms-4 bg-emerald-600 hover:bg-emerald-700">
                        {{ __('Registrarse') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Formulario para Dueños de Gimnasio -->
            <form x-show="userType === 'dueno'" 
                  method="POST" 
                  action="{{ route('register.dueno') }}" 
                  class="bg-white p-8 rounded-xl shadow-sm border max-w-2xl"
                  @submit.prevent="
                    fetch($el.action, {
                        method: 'POST',
                        body: new FormData($el),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showModal = true;
                            modalType = 'success';
                            modalMessage = data.message;
                            setTimeout(() => {
                                window.location.href = '{{ route('dashboard') }}';
                            }, 2000);
                        } else {
                            showModal = true;
                            modalType = 'error';
                            modalMessage = data.message || 'Error al registrar. Por favor, intenta nuevamente.';
                        }
                    })
                    .catch(error => {
                        showModal = true;
                        modalType = 'error';
                        modalMessage = 'Error al procesar la solicitud. Por favor, intenta nuevamente.';
                    })">
                @csrf
                <input type="hidden" name="role" value="dueno">

                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-700 mb-2">Registro de Dueño de Gimnasio</h2>
                    <p class="text-gray-600">Ingresa tus datos personales para comenzar. Podrás registrar tu gimnasio una vez que accedas al sistema.</p>
                </div>

                <div>
                    <x-input-label for="name" :value="__('Nombre Completo')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="telefono" :value="__('Teléfono Personal')" />
                    <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" required placeholder="Ej: +34 612345678" />
                    <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Después de registrarte, podrás ingresar los datos de tu gimnasio desde tu panel de control.
                    </p>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" href="{{ route('login') }}">
                        {{ __('¿Ya estás registrado?') }}
                    </a>

                    <x-primary-button class="ms-4 bg-emerald-600 hover:bg-emerald-700">
                        {{ __('Registrarse') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Formulario para Empleados -->
            <form x-show="userType === 'empleado'" method="POST" action="{{ route('register.empleado') }}" class="bg-white p-8 rounded-xl shadow-sm border max-w-2xl">
                @csrf
                <input type="hidden" name="role" value="empleado">

                <div>
                    <x-input-label for="nombre_empleado" :value="__('Nombre Completo')" />
                    <x-text-input id="nombre_empleado" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus />
                    <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email_empleado" :value="__('Email')" />
                    <x-text-input id="email_empleado" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="telefono_empleado" :value="__('Teléfono')" />
                    <x-text-input id="telefono_empleado" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" required placeholder="Ej: +34 612345678" />
                    <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="gimnasio_id_empleado" :value="__('Selecciona el Gimnasio donde trabajas')" />
                    <select id="gimnasio_id_empleado" name="gimnasio_id" required
                            class="mt-1 block w-full rounded-lg border-emerald-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Selecciona un gimnasio</option>
                        @foreach($gimnasios as $gimnasio)
                            <option value="{{ $gimnasio->id_gimnasio }}" {{ old('gimnasio_id') == $gimnasio->id_gimnasio ? 'selected' : '' }}>
                                {{ $gimnasio->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('gimnasio_id')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="puesto" :value="__('Puesto de Trabajo')" />
                    <x-text-input id="puesto" class="block mt-1 w-full" type="text" name="puesto" :value="old('puesto')" required placeholder="Ej: Entrenador Personal, Recepcionista, etc." />
                    <x-input-error :messages="$errors->get('puesto')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_empleado" :value="__('Contraseña')" />
                    <x-text-input id="password_empleado" class="block mt-1 w-full" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation_empleado" :value="__('Confirmar Contraseña')" />
                    <x-text-input id="password_confirmation_empleado" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" href="{{ route('login') }}">
                        {{ __('¿Ya estás registrado?') }}
                    </a>

                    <x-primary-button class="ms-4 bg-emerald-600 hover:bg-emerald-700">
                        {{ __('Registrarse') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Modal de Notificación -->
        <div x-show="showModal" 
             class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-lg p-6 max-w-sm mx-auto"
                 :class="{ 'border-green-500': modalType === 'success', 'border-red-500': modalType === 'error' }">
                <div class="flex items-center justify-center mb-4">
                    <template x-if="modalType === 'success'">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </template>
                    <template x-if="modalType === 'error'">
                        <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </template>
                </div>
                <p class="text-center text-lg font-semibold mb-4" x-text="modalMessage"></p>
                <div class="flex justify-center">
                    <button @click="showModal = false" 
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
