<x-guest-layout>
    <div class="max-w-md w-full mx-auto">
        <!-- Título -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-700">Bienvenido de nuevo</h2>
            <p class="text-gray-500 mt-2">Ingresa a tu cuenta para continuar</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="bg-white p-8 rounded-xl shadow-sm border">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
                <x-text-input id="email" 
                             class="block mt-2 w-full px-4 py-2 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" 
                             type="email" 
                             name="email" 
                             :value="old('email')" 
                             required 
                             autofocus 
                             autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-6">
                <x-input-label for="password" :value="__('Contraseña')" class="text-gray-700" />
                <x-text-input id="password" 
                             class="block mt-2 w-full px-4 py-2 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                             type="password"
                             name="password"
                             required 
                             autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="mt-6 flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" 
                           type="checkbox" 
                           class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" 
                           name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-emerald-600 hover:text-emerald-700" href="{{ route('password.request') }}">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-emerald-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors duration-200">
                    {{ __('Iniciar Sesión') }}
                </button>
            </div>

            <!-- Registro Link -->
            <div class="mt-6 text-center">
                <span class="text-gray-600 text-sm">¿No tienes una cuenta?</span>
                <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700 text-sm font-semibold ml-2">
                    Regístrate aquí
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
