<!-- Mobile Menu Button (visible solo en móvil) -->
<div class="fixed top-0 left-0 m-4 z-50 lg:hidden">
    <button @click="mobileMenuOpen = !mobileMenuOpen" 
            class="flex items-center p-2 rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition-colors duration-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<!-- Sidebar Navigation -->
<nav x-data="{ isExpanded: false }" 
     :class="{'w-16': !isExpanded, 'w-64': isExpanded}"
     class="fixed left-0 top-0 h-screen bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800 text-white shadow-xl transition-all duration-300 ease-in-out z-40">
    
    <!-- Toggle Button -->
    <button @click="isExpanded = !isExpanded" 
            class="absolute -right-3 top-4 bg-emerald-600 rounded-full p-1 shadow-lg border-2 border-white z-50">
        <svg class="w-4 h-4 text-white transform transition-transform" 
             :class="{'rotate-180': !isExpanded}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <!-- Logo Section -->
    <div class="sticky top-0 z-10 border-b border-teal-600/50 bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center p-4">
            <x-application-logo class="block h-8 w-auto fill-current text-emerald-300" />
            <span x-show="isExpanded" 
                  x-transition:enter="transition-opacity duration-300"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100"
                  class="ml-3 text-xl font-bold tracking-wider text-emerald-100 whitespace-nowrap">GymFlow</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex flex-col h-[calc(100vh-180px)] overflow-y-auto py-4">
        <div class="flex-1 space-y-1 px-2">
            <!-- Dashboard -->
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                class="flex items-center justify-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <div class="flex items-center w-full">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span x-show="isExpanded" 
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3 whitespace-nowrap overflow-hidden">{{ __('Panel') }}</span>
                </div>
            </x-nav-link>

            <!-- Memberships -->
            <x-nav-link :href="route('membresias.index')" :active="request()->routeIs('membresias.*')" 
                class="flex items-center justify-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <div class="flex items-center w-full">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <span x-show="isExpanded"
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3 whitespace-nowrap overflow-hidden">{{ __('Membresías') }}</span>
                </div>
            </x-nav-link>

            <!-- Training Section Header -->
            <div x-show="isExpanded" class="mt-6 mb-2">
                <span class="px-3 text-xs font-semibold text-emerald-300 uppercase tracking-wider">Entrenamiento</span>
            </div>

            <!-- Routines -->
            <x-nav-link :href="route('rutinas-predefinidas.index')" :active="request()->routeIs('rutinas-predefinidas.*')" 
                class="flex items-center justify-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <div class="flex items-center w-full">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span x-show="isExpanded"
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3 whitespace-nowrap overflow-hidden">{{ __('Rutinas Predefinidas') }}</span>
                </div>
            </x-nav-link>

            <!-- Routine Assignment -->
            <x-nav-link :href="route('asignacion-rutinas.index')" :active="request()->routeIs('asignacion-rutinas.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span class="flex-1">{{ __('Asignación de Rutinas') }}</span>
            </x-nav-link>

            <!-- Management Section Header -->
            <div class="mt-6 mb-2">
                <span class="px-3 text-xs font-semibold text-emerald-300 uppercase tracking-wider">Gestión</span>
            </div>

            <!-- Roles y Permisos -->
            <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span class="flex-1">{{ __('Roles y Permisos') }}</span>
            </x-nav-link>

            <!-- Usuarios -->
            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="flex-1">{{ __('Usuarios') }}</span>
            </x-nav-link>

            <!-- Dueños de Gimnasios -->
            <x-nav-link :href="route('duenos-gimnasio.index')" :active="request()->routeIs('duenos-gimnasio.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="flex-1">{{ __('Dueños de Gimnasios') }}</span>
            </x-nav-link>

            <!-- Gimnasios -->
            <x-nav-link :href="route('gimnasios.index')" :active="request()->routeIs('gimnasios.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="flex-1">{{ __('Gimnasios') }}</span>
            </x-nav-link>

            <!-- Pagos de Gimnasios -->
            <x-nav-link :href="route('pagos-gimnasios.index')" :active="request()->routeIs('pagos-gimnasios.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="flex-1">{{ __('Pagos de Gimnasios') }}</span>
            </x-nav-link>

            <!-- Clientes -->
            <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="flex-1">{{ __('Clientes') }}</span>
            </x-nav-link>

            <!-- Attendance -->
            <x-nav-link :href="route('asistencias.index')" :active="request()->routeIs('asistencias.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="flex-1">{{ __('Control de Asistencias') }}</span>
            </x-nav-link>

            <!-- Nutrition -->
            <x-nav-link :href="route('nutricion.index')" :active="request()->routeIs('nutricion.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                <span class="flex-1">{{ __('Nutrición') }}</span>
            </x-nav-link>
            
        </div>
    </div>

    <!-- User Profile Section -->
    <div class="sticky bottom-0 w-full border-t border-teal-600/50 bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800">
        <div class="p-2">
            <div class="flex items-center justify-center p-2 rounded-lg bg-emerald-700/50 backdrop-blur-sm">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center">
                        <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </div>
                <div x-show="isExpanded" 
                     x-transition:enter="transition-opacity duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="ml-3 flex-1 min-w-0 overflow-hidden">
                    <div class="text-sm font-medium text-emerald-100 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-emerald-300 truncate">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center p-2 text-gray-100 hover:bg-orange-500/20 hover:text-orange-200 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span x-show="isExpanded"
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3 whitespace-nowrap overflow-hidden">{{ __('Cerrar Sesión') }}</span>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Main Content Wrapper -->
<div :class="{'ml-16': !isExpanded, 'ml-64': isExpanded}" 
     class="min-h-screen bg-gray-50 transition-all duration-300">
    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main class="py-6 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</div>
