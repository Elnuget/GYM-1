<!-- Sidebar Navigation -->
<nav class="fixed left-0 top-0 h-full w-64 bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800 text-white shadow-xl">
    <!-- Logo Section -->
    <div class="p-6 border-b border-teal-600/50">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <x-application-logo class="block h-10 w-auto fill-current text-emerald-300" />
            <span class="text-xl font-bold tracking-wider text-emerald-100">GymFlow</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="mt-6 space-y-2 px-4">
        <!-- Dashboard -->
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            {{ __('Panel') }}
        </x-nav-link>

        <!-- Memberships -->
        <x-nav-link :href="route('membresias.index')" :active="request()->routeIs('membresias.*')" 
            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
            {{ __('Membresías') }}
        </x-nav-link>

        <!-- Payments -->
        <x-nav-link :href="route('pagos.index')" :active="request()->routeIs('pagos.*')" 
            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            {{ __('Pagos') }}
        </x-nav-link>

        <!-- Payment Methods -->
        <x-nav-link :href="route('metodos-pago.index')" :active="request()->routeIs('metodos-pago.*')" 
            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            {{ __('Métodos de Pago') }}
        </x-nav-link>

        <!-- Training Section -->
        <div class="mt-6 border-t border-teal-600/50 pt-4">
            <span class="px-3 text-xs font-semibold text-emerald-300 uppercase tracking-wider">Entrenamiento</span>
        </div>

        <!-- Routines -->
        <x-nav-link :href="route('rutinas-predefinidas.index')" :active="request()->routeIs('rutinas-predefinidas.*')" 
            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            {{ __('Rutinas Predefinidas') }}
        </x-nav-link>

        <!-- Routine Assignment -->
        <x-nav-link :href="route('asignacion-rutinas.index')" :active="request()->routeIs('asignacion-rutinas.*')" 
            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            {{ __('Asignación de Rutinas') }}
        </x-nav-link>

        <!-- Attendance -->
        <x-nav-link :href="route('asistencias.index')" :active="request()->routeIs('asistencias.*')" 
            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            {{ __('Control de Asistencias') }}
        </x-nav-link>

        <!-- Nutrition -->
        <x-nav-link :href="route('nutricion.index')" :active="request()->routeIs('nutricion.*')" 
            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
            </svg>
            {{ __('Nutrición') }}
        </x-nav-link>

        <!-- Equipment -->
        <x-nav-link :href="route('implementos.index')" :active="request()->routeIs('implementos.*')" 
            class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
            </svg>
            {{ __('Implementos') }}
        </x-nav-link>
    </div>

    <!-- User Profile Section -->
    <div class="absolute bottom-0 w-full border-t border-teal-600/50">
        <div class="p-4">
            <div class="flex items-center p-3 rounded-lg bg-emerald-700/50 backdrop-blur-sm">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-teal-500 flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="ml-3">
                    <div class="text-sm font-medium text-emerald-100">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-emerald-300">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full flex items-center p-3 text-gray-100 hover:bg-orange-500/20 hover:text-orange-200 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    {{ __('Cerrar Sesión') }}
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Main Content Wrapper -->
<div class="ml-64 min-h-screen bg-gray-50">
    {{ $slot }}
</div>
