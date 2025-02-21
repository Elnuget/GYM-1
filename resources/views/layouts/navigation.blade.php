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
            <x-application-logo class="block h-8 w-auto fill-current text-emerald-300" />
            <span class="ml-3 text-xl font-bold text-emerald-100">GymFlow</span>
        </a>
    </div>

    <!-- Scrollable Navigation Links Container -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-4 space-y-2">
            <!-- Panel -->
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span>{{ __('Panel') }}</span>
            </x-nav-link>

            <!-- Membresías -->
            <x-nav-link :href="route('membresias.index')" :active="request()->routeIs('membresias.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <span>{{ __('Membresías') }}</span>
            </x-nav-link>

            <!-- Sección Entrenamiento -->
            <div class="mt-6 mb-2">
                <span class="px-3 text-xs font-semibold text-emerald-300 uppercase tracking-wider">Entrenamiento</span>
            </div>

            <!-- Rutinas Predefinidas -->
            <x-nav-link :href="route('rutinas-predefinidas.index')" :active="request()->routeIs('rutinas-predefinidas.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>{{ __('Rutinas Predefinidas') }}</span>
            </x-nav-link>

            <!-- Asignación de Rutinas -->
            <x-nav-link :href="route('asignacion-rutinas.index')" :active="request()->routeIs('asignacion-rutinas.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>{{ __('Asignación de Rutinas') }}</span>
            </x-nav-link>

            <!-- Sección Gestión -->
            <div class="mt-6 mb-2">
                <span class="px-3 text-xs font-semibold text-emerald-300 uppercase tracking-wider">Gestión</span>
            </div>

            <!-- Roles y Permisos -->
            <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>{{ __('Roles y Permisos') }}</span>
            </x-nav-link>

            <!-- Usuarios -->
            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>{{ __('Usuarios') }}</span>
            </x-nav-link>

            <!-- Dueños de Gimnasios -->
            <x-nav-link :href="route('duenos-gimnasio.index')" :active="request()->routeIs('duenos-gimnasio.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>{{ __('Dueños de Gimnasios') }}</span>
            </x-nav-link>

            <!-- Gimnasios -->
            <x-nav-link :href="route('gimnasios.index')" :active="request()->routeIs('gimnasios.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>{{ __('Gimnasios') }}</span>
            </x-nav-link>

            <!-- Pagos de Gimnasios -->
            <x-nav-link :href="route('pagos-gimnasios.index')" :active="request()->routeIs('pagos-gimnasios.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>{{ __('Pagos de Gimnasios') }}</span>
            </x-nav-link>

            <!-- Clientes -->
            <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ __('Clientes') }}</span>
            </x-nav-link>

            <!-- Control de Asistencias -->
            <x-nav-link :href="route('asistencias.index')" :active="request()->routeIs('asistencias.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>{{ __('Control de Asistencias') }}</span>
            </x-nav-link>

            <!-- Nutrición -->
            <x-nav-link :href="route('nutricion.index')" :active="request()->routeIs('nutricion.*')" 
                class="flex items-center p-3 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                <span>{{ __('Nutrición') }}</span>
            </x-nav-link>

            <!-- Perfil de Usuario y Cerrar Sesión -->
            <div class="mt-6 pt-6 border-t border-emerald-600/30">
                <div class="flex items-center p-3 rounded-lg bg-emerald-700/50">
                    <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center">
                        <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-emerald-100">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-emerald-300">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center p-3 rounded-lg text-gray-100 hover:bg-orange-500/20 hover:text-orange-200 transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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
            <x-application-logo class="block h-8 w-auto fill-current text-emerald-300" />
            <span x-cloak x-show="isExpanded" class="ml-3 text-xl font-bold tracking-wider text-emerald-100">GymFlow</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 space-y-1 px-2 py-4">
        <!-- Dashboard -->
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
            class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Dashboard') }}</span>
        </x-nav-link>

        <!-- Gestión de Gimnasios -->
        <div x-data="{ open: false }">
            <button @click="open = !open" 
                    class="w-full flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <div class="flex items-center" :class="{'justify-center w-full': !isExpanded}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Gestión de Gimnasios') }}</span>
                    <svg x-cloak x-show="isExpanded" class="w-4 h-4 ml-auto" :class="{'rotate-180': open}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>
            
            <div x-show="open" class="pl-4 mt-1 space-y-1">
                <x-nav-link :href="route('gimnasios.index')" :active="request()->routeIs('gimnasios.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Gimnasios') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('duenos-gimnasio.index')" :active="request()->routeIs('duenos-gimnasio.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Dueños de Gimnasios') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('pagos-gimnasios.index')" :active="request()->routeIs('pagos-gimnasios.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Pagos de Gimnasios') }}</span>
                </x-nav-link>
            </div>
        </div>

        <!-- Gestión de Clientes -->
        <div x-data="{ open: false }">
            <button @click="open = !open" 
                    class="w-full flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <div class="flex items-center" :class="{'justify-center w-full': !isExpanded}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Gestión de Clientes') }}</span>
                    <svg x-cloak x-show="isExpanded" class="w-4 h-4 ml-auto" :class="{'rotate-180': open}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>
            
            <div x-show="open" class="pl-4 mt-1 space-y-1">
                <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Clientes') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('membresias.index')" :active="request()->routeIs('membresias.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Membresías') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('metodos-pago.index')" :active="request()->routeIs('metodos-pago.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Métodos de Pago') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('asistencias.index')" :active="request()->routeIs('asistencias.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Control de Asistencias') }}</span>
                </x-nav-link>
            </div>
        </div>

        <!-- Entrenamiento -->
        <div x-data="{ open: false }">
            <button @click="open = !open" 
                    class="w-full flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <div class="flex items-center" :class="{'justify-center w-full': !isExpanded}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Entrenamiento') }}</span>
                    <svg x-cloak x-show="isExpanded" class="w-4 h-4 ml-auto" :class="{'rotate-180': open}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>
            
            <div x-show="open" class="pl-4 mt-1 space-y-1">
                <x-nav-link :href="route('rutinas-predefinidas.index')" :active="request()->routeIs('rutinas-predefinidas.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Rutinas Predefinidas') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('asignacion-rutinas.index')" :active="request()->routeIs('asignacion-rutinas.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Asignación de Rutinas') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('nutricion.index')" :active="request()->routeIs('nutricion.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Nutrición') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('implementos.index')" :active="request()->routeIs('implementos.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Implementos') }}</span>
                </x-nav-link>
            </div>
        </div>

        <!-- Configuración -->
        <div x-data="{ open: false }">
            <button @click="open = !open" 
                    class="w-full flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                <div class="flex items-center" :class="{'justify-center w-full': !isExpanded}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Configuración') }}</span>
                    <svg x-cloak x-show="isExpanded" class="w-4 h-4 ml-auto" :class="{'rotate-180': open}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>
            
            <div x-show="open" class="pl-4 mt-1 space-y-1">
                <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Roles y Permisos') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" 
                    class="flex items-center p-2 rounded-lg text-gray-100 hover:bg-emerald-600/50 hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-cloak x-show="isExpanded" class="ml-3 whitespace-nowrap">{{ __('Usuarios') }}</span>
                </x-nav-link>
            </div>
        </div>
    </div>

    <!-- User Profile Section -->
    <div class="sticky bottom-0 w-full border-t border-teal-600/50 bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800">
        <div class="p-2">
            <div class="flex items-center p-2 rounded-lg bg-emerald-700/50 backdrop-blur-sm" 
                 :class="{'justify-center': !isExpanded}">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center">
                        <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </div>
                <div x-cloak x-show="isExpanded" 
                     class="ml-3 flex-1 min-w-0">
                    <div class="text-sm font-medium text-emerald-100 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-emerald-300 truncate">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <!-- Botón de Cerrar Sesión -->
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center p-2 rounded-lg text-gray-100 hover:bg-orange-500/20 hover:text-orange-200 transition-all duration-200">
                    <svg class="w-5 h-5" :class="{'mr-3': isExpanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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
