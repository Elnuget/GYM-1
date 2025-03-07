@php
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
@endphp

<!-- Mobile Menu Button (visible solo en móvil) -->
<div class="fixed top-0 left-0 m-4 z-50 lg:hidden">
    <button @click="mobileMenuOpen = !mobileMenuOpen"
        class="flex items-center p-2 rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition-colors duration-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<!-- Mobile Navigation Overlay -->
<div x-show="mobileMenuOpen"
    x-transition:enter="transition-opacity duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
    @click="mobileMenuOpen = false">
</div>

<!-- Mobile Navigation Menu -->
<nav x-show="mobileMenuOpen"
    x-transition:enter="transition-transform duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition-transform duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed left-0 top-0 bottom-0 w-64 bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800 z-50 lg:hidden flex flex-col">

    <!-- Logo Section -->
    <div class="sticky top-0 z-10 border-b border-teal-600/50 bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800">
        <a href="{{ route('dashboard') }}" class="flex items-center p-4">
            <img src="{{ asset('favicon.png') }}" alt="Logo" class="block h-8 w-auto" />
            <span class="ml-3 text-xl font-bold text-emerald-100">GymFlow</span>
        </a>
    </div>

    <!-- Scrollable Navigation Links Container -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-4 space-y-2">
            <!-- Alerta de Perfil Incompleto (Móvil) - Eliminada para evitar duplicación -->

            <!-- Navigation Links -->
            <div class="flex-1 space-y-1 px-2 py-4">
                <!-- Alerta de Perfil Incompleto -->
                @php
                $user = Auth::user();
                $perfilIncompleto = false;
                $rutaCompletarPerfil = '';
                $mensajeAlerta = '';

                if (Auth::check() && !$user->configuracion_completa) {
                if ($user->hasRole('cliente')) {
                $perfilIncompleto = true;
                $rutaCompletarPerfil = route('completar.registro.cliente.form');
                $mensajeAlerta = 'Para acceder a todas las funcionalidades, completa tu perfil personal.';
                } elseif ($user->hasRole('dueño')) {
                $perfilIncompleto = true;
                $rutaCompletarPerfil = route('completar.registro.dueno.form');
                $mensajeAlerta = 'Para acceder a todas las funcionalidades, registra tu gimnasio.';
                } elseif ($user->hasRole('entrenador') || $user->hasRole('empleado')) {
                $perfilIncompleto = true;
                $rutaCompletarPerfil = route('completar.registro.empleado.form');
                $mensajeAlerta = 'Para acceder a todas las funcionalidades, completa tu perfil profesional.';
                }
                }
                @endphp

                @if($perfilIncompleto)
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 rounded-md shadow-sm">
                    <h3 class="text-lg font-medium text-amber-800">{{ __('Tu perfil está incompleto') }}</h3>
                    <p class="mt-2 text-amber-700">{{ __($mensajeAlerta) }}</p>
                    <div class="mt-4">
                        <a href="{{ $rutaCompletarPerfil }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700">
                            {{ __('Completar mi perfil ahora') }}
                        </a>
                    </div>
                </div>
                @endif

                <!-- Panel para todos los roles -->
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                    class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>{{ __('Panel') }}</span>
                </x-nav-link>

                <!-- Menú Administrador -->
                @role('admin')
                <div x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('duenos-gimnasio.*') || request()->routeIs('gimnasios.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200"
                        :class="{'bg-emerald-600/30': open}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        <span>{{ __('Administración') }}</span>
                        <svg class="w-4 h-4 ml-auto" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pl-4 mt-1 space-y-1">
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>{{ __('Usuarios') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span>{{ __('Roles y Permisos') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('duenos-gimnasio.index')" :active="request()->routeIs('duenos-gimnasio.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ __('Dueños de Gimnasios') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('gimnasios.index')" :active="request()->routeIs('gimnasios.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>{{ __('Gimnasios') }}</span>
                        </x-nav-link>
                    </div>
                </div>
                @endrole

                <!-- Menú Dueño de Gimnasio -->
                @role('dueño')
                <div x-data="{ open: {{ request()->routeIs('gimnasios.*') || request()->routeIs('clientes.*') || request()->routeIs('membresias.*') || request()->routeIs('tipos-membresia.*') || request()->routeIs('rutinas-predefinidas.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200"
                        :class="{'bg-emerald-600/30': open}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>{{ __('Gestión de Gimnasio') }}</span>
                        <svg class="w-4 h-4 ml-auto" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pl-4 mt-1 space-y-1">
                        <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ __('Clientes') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('gimnasios.index')" :active="request()->routeIs('gimnasios.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>{{ __('Gimnasios') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('membresias.index')" :active="request()->routeIs('membresias.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            <span>{{ __('Membresías') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('tipos-membresia.index')" :active="request()->routeIs('tipos-membresia.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>{{ __('Tipos de Membresía') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('rutinas-predefinidas.index')" :active="request()->routeIs('rutinas-predefinidas.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>{{ __('Rutinas') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('pagos.index')" :active="request()->routeIs('pagos.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">

                            <!-- Ícono de tarjeta de pago (Heroicon: credit-card) -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 7.5h19.5M2.25 7.5A2.25 2.25 0 0 1 4.5 5.25h15A2.25 2.25 0 0 1 21.75 7.5m-19.5 0v9A2.25 2.25 0 0 0 4.5 18.75h15a2.25 2.25 0 0 0 2.25-2.25v-9M6.75 15h.008v.008H6.75v-.008zm3 0h.008v.008H9.75v-.008z" />
                            </svg>

                            <span>{{ __('Pagos') }}</span>
                        </x-nav-link>

                        <x-nav-link :href="route('asistencias.index')" :active="request()->routeIs('asistencias.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>{{ __('Asistencias') }}</span>
                        </x-nav-link>

                    </div>
                </div>
                @endrole

                <!-- Menú Cliente -->
                @role('cliente')
                <div x-data="{ open: {{ request()->routeIs('cliente.rutinas.*') || request()->routeIs('cliente.asistencias') || request()->routeIs('cliente.membresia') || request()->routeIs('cliente.nutricion.*') || request()->routeIs('cliente.pagos.*') || request()->routeIs('cliente.comunicacion.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200"
                        :class="{'bg-emerald-600/30': open}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ __('Mi Cuenta') }}</span>
                        <svg class="w-4 h-4 ml-auto" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pl-4 mt-1 space-y-1">
                        <x-nav-link :href="route('cliente.rutinas.actual')" :active="request()->routeIs('cliente.rutinas.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>{{ __('Mi Rutina') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('cliente.asistencias')" :active="request()->routeIs('cliente.asistencias')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>{{ __('Mis Asistencias') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('cliente.membresia')" :active="request()->routeIs('cliente.membresia')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            <span>{{ __('Mi Membresía') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('cliente.nutricion')" :active="request()->routeIs('cliente.nutricion')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                            <span>{{ __('Mi Plan Nutricional') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('cliente.pagos.index')" :active="request()->routeIs('cliente.pagos.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>{{ __('Mis Pagos') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('cliente.comunicacion.index')" :active="request()->routeIs('cliente.comunicacion.*')"
                            class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Comunicación') }}</span>
                        </x-nav-link>
                    </div>
                </div>
                @endrole

                <!-- Menú Entrenador -->
                @role('entrenador')
                <div x-data="{ open: {{ request()->routeIs('rutinas-predefinidas.*') || request()->routeIs('clientes.*') || request()->routeIs('nutricion.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200"
                        :class="{'bg-emerald-600/30': open}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>{{ __('Entrenamiento') }}</span>
                        <svg class="w-4 h-4 ml-auto" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pl-4 mt-1 space-y-1">
                        <x-nav-link :href="route('rutinas-predefinidas.index')" :active="request()->routeIs('rutinas-predefinidas.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>{{ __('Rutinas') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ __('Clientes') }}</span>
                        </x-nav-link>
                        <x-nav-link :href="route('nutricion.index')" :active="request()->routeIs('nutricion.*')"
                            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                            <span>{{ __('Nutrición') }}</span>
                        </x-nav-link>
                    </div>
                </div>
                @endrole
            </div>




            <!-- Perfil de Usuario y Cerrar Sesión -->
            <div class="mt-6 pt-6 border-t border-emerald-600/30">
                <div class="flex items-center p-3 rounded-lg bg-emerald-700/50">
                    <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center overflow-hidden">
                        @if(Auth::user()->foto_perfil && file_exists(public_path(Auth::user()->foto_perfil)))
                        <img src="{{ asset(Auth::user()->foto_perfil) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                        <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-emerald-100">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-emerald-300">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <!-- Configuración de Perfil (Móvil) -->
                <a href="{{ route('profile.edit') }}"
                    class="mt-2 w-full flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ __('Configuración de Perfil') }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center p-3 rounded-lg text-gray-100 hover:bg-orange-500/20 hover:text-orange-200 transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>{{ __('Cerrar Sesión') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Desktop Sidebar Navigation -->
<nav x-data="{ isExpanded: false }"
    @mouseenter="isExpanded = true"
    @mouseleave="isExpanded = false"
    :class="{'w-16': !isExpanded, 'w-64': isExpanded}"
    class="fixed left-0 top-0 h-screen bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800 text-white shadow-xl transition-all duration-300 ease-in-out z-40 hidden lg:block">

    <!-- Logo Section -->
    <div class="sticky top-0 z-10 border-b border-teal-600/50 bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center p-4">
            <img src="{{ asset('favicon.png') }}" alt="Logo" class="block h-8 w-auto" />
            <span x-cloak x-show="isExpanded" class="ml-3 text-xl font-bold tracking-wider text-emerald-100">GymFlow</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 space-y-1 px-2 py-4">
        <!-- Alerta de Perfil Incompleto (Escritorio) -->
        @php
        $user = Auth::user();
        $perfilIncompleto = false;
        $rutaCompletarPerfil = '';
        $mensajeAlerta = '';

        if (Auth::check() && !$user->configuracion_completa) {
        if ($user->hasRole('cliente')) {
        $perfilIncompleto = true;
        $rutaCompletarPerfil = route('completar.registro.cliente.form');
        $mensajeAlerta = 'Para acceder a todas las funcionalidades, completa tu perfil personal.';
        } elseif ($user->hasRole('dueño')) {
        $perfilIncompleto = true;
        $rutaCompletarPerfil = route('completar.registro.dueno.form');
        $mensajeAlerta = 'Para acceder a todas las funcionalidades, registra tu gimnasio.';
        } elseif ($user->hasRole('entrenador') || $user->hasRole('empleado')) {
        $perfilIncompleto = true;
        $rutaCompletarPerfil = route('completar.registro.empleado.form');
        $mensajeAlerta = 'Para acceder a todas las funcionalidades, completa tu perfil profesional.';
        }
        }
        @endphp

        @if($perfilIncompleto)
        <div x-show="isExpanded" class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 rounded-md shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-amber-800">{{ __('Tu perfil está incompleto') }}</h3>
                    <div class="mt-2 text-amber-700">
                        <p>{{ __($mensajeAlerta) }}</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ $rutaCompletarPerfil }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 focus:bg-amber-700 active:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Completar mi perfil ahora') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="!isExpanded" class="flex justify-center mb-4">
            <a href="{{ $rutaCompletarPerfil }}" class="relative group">
                <div class="p-1 bg-amber-500 rounded-full">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                    {{ __('Completar perfil') }}
                </span>
            </a>
        </div>
        @endif

        <!-- Panel para todos los roles -->
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
            class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Panel') }}</span>
        </x-nav-link>

        <!-- Menú Administrador -->
        @role('admin')
        <div x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('duenos-gimnasio.*') || request()->routeIs('gimnasios.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200"
                :class="{'bg-emerald-600/30': open}">
                <div class="flex items-center" :class="{'justify-center w-full': !isExpanded}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Administración') }}</span>
                    <svg x-cloak x-show="isExpanded" class="w-4 h-4 ml-auto" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </button>
            <div x-show="open" class="pl-4 mt-1 space-y-1">
                <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Usuarios') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Roles y Permisos') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('duenos-gimnasio.index')" :active="request()->routeIs('duenos-gimnasio.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Dueños de Gimnasios') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('gimnasios.index')" :active="request()->routeIs('gimnasios.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Gimnasios') }}</span>
                </x-nav-link>
            </div>
        </div>
        @endrole

        <!-- Menú Dueño de Gimnasio -->
        @role('dueño')
        <div x-data="{ open: {{ request()->routeIs('gimnasios.*') || request()->routeIs('clientes.*') || request()->routeIs('membresias.*') || request()->routeIs('tipos-membresia.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200"
                :class="{'bg-emerald-600/30': open}">
                <div class="flex items-center" :class="{'justify-center w-full': !isExpanded}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Gestión de Gimnasio') }}</span>
                    <svg x-cloak x-show="isExpanded" class="w-4 h-4 ml-auto" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </button>
            <div x-show="open" class="pl-4 mt-1 space-y-1">
                <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Clientes') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('gimnasios.index')" :active="request()->routeIs('gimnasios.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Gimnasios') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('membresias.index')" :active="request()->routeIs('membresias.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Membresías') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('tipos-membresia.index')" :active="request()->routeIs('tipos-membresia.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Tipos de Membresía') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('rutinas-predefinidas.index')" :active="request()->routeIs('rutinas-predefinidas.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Rutinas') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('pagos.index')" :active="request()->routeIs('pagos.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">

                    <!-- Ícono de tarjeta (similar a un pago) -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 7.5h19.5M2.25 7.5A2.25 2.25 0 0 1 4.5 5.25h15A2.25 2.25 0 0 1 21.75 7.5m-19.5 0v9A2.25 2.25 0 0 0 4.5 18.75h15a2.25 2.25 0 0 0 2.25-2.25v-9M6.75 15h.008v.008H6.75v-.008zm3 0h.008v.008H9.75v-.008z" />
                    </svg>

                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">
                        {{ __('Pagos') }}
                    </span>
                </x-nav-link>

                <x-nav-link :href="route('asistencias.index')" :active="request()->routeIs('asistencias.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Asistencias') }}</span>
                </x-nav-link>

            </div>
        </div>
        @endrole

        <!-- Menú Cliente -->
        @role('cliente')
        <div x-data="{ open: {{ request()->routeIs('cliente.rutinas.*') || request()->routeIs('cliente.asistencias') || request()->routeIs('cliente.membresia') || request()->routeIs('cliente.nutricion.*') || request()->routeIs('cliente.pagos.*') || request()->routeIs('cliente.comunicacion.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200"
                :class="{'bg-emerald-600/30': open}">
                <div class="flex items-center" :class="{'justify-center w-full': !isExpanded}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Mi Cuenta') }}</span>
                    <svg x-cloak x-show="isExpanded" class="w-4 h-4 ml-auto" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </button>
            <div x-show="open" class="pl-4 mt-1 space-y-1">
                <x-nav-link :href="route('cliente.rutinas.actual')" :active="request()->routeIs('cliente.rutinas.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Mi Rutina') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('cliente.asistencias')" :active="request()->routeIs('cliente.asistencias')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Mis Asistencias') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('cliente.membresia')" :active="request()->routeIs('cliente.membresia')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Mi Membresía') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('cliente.nutricion')" :active="request()->routeIs('cliente.nutricion')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Mi Plan Nutricional') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('cliente.pagos.index')" :active="request()->routeIs('cliente.pagos.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Mis Pagos') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('cliente.comunicacion.index')" :active="request()->routeIs('cliente.comunicacion.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Comunicación') }}</span>
                </x-nav-link>
            </div>
        </div>
        @endrole

        <!-- Menú Entrenador -->
        @role('entrenador')
        <div x-data="{ open: {{ request()->routeIs('rutinas-predefinidas.*') || request()->routeIs('clientes.*') || request()->routeIs('nutricion.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200"
                :class="{'bg-emerald-600/30': open}">
                <div class="flex items-center" :class="{'justify-center w-full': !isExpanded}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Entrenamiento') }}</span>
                    <svg x-cloak x-show="isExpanded" class="w-4 h-4 ml-auto" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </button>
            <div x-show="open" class="pl-4 mt-1 space-y-1">
                <x-nav-link :href="route('rutinas-predefinidas.index')" :active="request()->routeIs('rutinas-predefinidas.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Rutinas') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Clientes') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('nutricion.index')" :active="request()->routeIs('nutricion.*')"
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Nutrición') }}</span>
                </x-nav-link>
            </div>
        </div>
        @endrole
    </div>

    <!-- User Profile Section -->
    <div class="sticky bottom-0 w-full border-t border-teal-600/50 bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800">
        <div class="p-2">
            <div class="flex items-center p-2 rounded-lg bg-emerald-700/50 backdrop-blur-sm"
                :class="{'justify-center': !isExpanded}">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center overflow-hidden">
                        @if(Auth::user()->foto_perfil && file_exists(public_path(Auth::user()->foto_perfil)))
                        <img src="{{ asset(Auth::user()->foto_perfil) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                        <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        @endif
                    </div>
                </div>
                <div x-cloak x-show="isExpanded"
                    class="ml-3 flex-1 min-w-0">
                    <div class="text-sm font-medium text-emerald-100 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-emerald-300 truncate uppercase font-semibold">{{ Auth::user()->rol }}</div>
                </div>
            </div>

            <!-- Configuración de Perfil (Escritorio) -->
            <a href="{{ route('profile.edit') }}"
                class="mt-2 w-full flex items-center justify-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5" :class="{'mr-3': isExpanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-cloak x-show="isExpanded">{{ __('Configuración de Perfil') }}</span>
            </a>

            <!-- Botón de Cerrar Sesión -->
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center p-2 rounded-lg text-gray-100 hover:bg-orange-500/20 hover:text-orange-200 transition-all duration-200">
                    <svg class="w-5 h-5" :class="{'mr-3': isExpanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span x-cloak x-show="isExpanded">{{ __('Cerrar Sesión') }}</span>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Main Content Wrapper -->
<div :class="{'lg:ml-16': !isExpanded, 'lg:ml-64': isExpanded}"
    class="min-h-screen bg-gray-50 transition-all duration-300">
    <!-- Page Content -->
    <main class="py-6 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</div>

<!-- Sección de diagnóstico (quitar en producción) -->
@if(config('app.debug') && Auth::user()->foto_perfil)
    <div class="p-2 text-xs bg-gray-100 text-gray-700">
        <p>Ruta de foto: {{ Auth::user()->foto_perfil }}</p>
        <p>¿Archivo existe? {{ file_exists(public_path(Auth::user()->foto_perfil)) ? 'Sí' : 'No' }}</p>
        <p>Ruta completa: {{ public_path(Auth::user()->foto_perfil) }}</p>
    </div>
@endif