<x-guest-layout>
    <div x-data="{ userType: 'cliente' }" class="max-w-md mx-auto">
        <!-- Selector de tipo de usuario -->
        <div class="mb-6 flex justify-center space-x-4">
            <button @click="userType = 'cliente'" 
                    :class="{'bg-emerald-600 text-white': userType === 'cliente', 'bg-white text-emerald-600': userType !== 'cliente'}"
                    class="px-4 py-2 rounded-lg border border-emerald-600 transition-colors duration-200">
                Registrarse como Cliente
            </button>
            <button @click="userType = 'dueno'" 
                    :class="{'bg-emerald-600 text-white': userType === 'dueno', 'bg-white text-emerald-600': userType !== 'dueno'}"
                    class="px-4 py-2 rounded-lg border border-emerald-600 transition-colors duration-200">
                Registrarse como Dueño
            </button>
        </div>

        <!-- Formulario para Clientes -->
        <form x-show="userType === 'cliente'" method="POST" action="{{ route('register.cliente') }}" class="bg-white p-6 rounded-lg shadow-md">
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
                <x-input-label for="telefono" :value="__('Teléfono')" />
                <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" placeholder="Ej: +34 612345678" />
                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" />
                <x-text-input id="fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento')" />
                <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
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
        <form x-show="userType === 'dueno'" method="POST" action="{{ route('register.dueno') }}" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <input type="hidden" name="role" value="dueno">

            <div>
                <x-input-label for="name" :value="__('Nombre Completo')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="nombre_comercial" :value="__('Nombre Comercial del Gimnasio')" />
                <x-text-input id="nombre_comercial" class="block mt-1 w-full" type="text" name="nombre_comercial" :value="old('nombre_comercial')" required />
                <x-input-error :messages="$errors->get('nombre_comercial')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="telefono" :value="__('Teléfono')" />
                <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" required placeholder="Ej: +34 612345678" />
                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="direccion" :value="__('Dirección del Gimnasio')" />
                <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" required placeholder="Calle, número, ciudad, código postal" />
                <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
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
    </div>
</x-guest-layout>
