<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div x-data="{ isExpanded: false }" class="min-h-screen bg-gray-50">
        <!-- Menú lateral para clientes -->
        <nav class="fixed left-0 top-0 h-full bg-gradient-to-br from-teal-600 via-teal-700 to-emerald-900 transition-all duration-300 shadow-xl"
             :class="{'w-64': isExpanded, 'w-16': !isExpanded}"
             @mouseenter="isExpanded = true"
             @mouseleave="isExpanded = false">
            
            <!-- Logo -->
            <div class="sticky top-0 z-10 border-b border-teal-600/50 bg-gradient-to-br from-cyan-600 via-teal-700 to-emerald-800">
    <a href="{{ route('dashboard') }}" class="flex items-center justify-center p-4">
        <!-- Logo -->
        <x-application-logo class="block h-8 w-auto fill-current text-emerald-300" />
        
        <!-- Texto "GymFlow" que se muestra/oculta con Alpine.js -->
        <span x-cloak x-show="isExpanded" 
              x-transition:enter="transition-opacity duration-300"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              class="ml-3 text-xl font-bold tracking-wider text-emerald-100">
            GymFlow
        </span>
    </a>
</div>

            <!-- Menú de navegación -->
            <div class="p-3 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('cliente.dashboard') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-white hover:bg-white/5 transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="isExpanded" 
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3">Dashboard</span>
                </a>

                <!-- Mi Perfil -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" 
                            class="flex items-center w-full px-3 py-2.5 rounded-lg text-white hover:bg-white/5 transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span x-show="isExpanded" class="ml-3">Mi Perfil</span>
                        <svg x-show="isExpanded" 
                             class="w-4 h-4 ml-auto transition-transform duration-200" 
                             :class="{'rotate-180': open}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open && isExpanded" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="pl-11 mt-1 space-y-1">
                        <a href="{{ route('cliente.perfil.informacion') }}" 
                           class="block py-2 text-white/80 hover:text-white transition-colors duration-200">
                            Información Personal
                        </a>
                        <a href="{{ route('cliente.perfil.medidas') }}" 
                           class="block py-2 text-white/80 hover:text-white transition-colors duration-200">
                            Historial de Medidas
                        </a>
                        <a href="{{ route('cliente.perfil.objetivos') }}" 
                           class="block py-2 text-white/80 hover:text-white transition-colors duration-200">
                            Mis Objetivos
                        </a>
                    </div>
                </div>

                <!-- Rutinas -->
                <a href="{{ route('cliente.rutinas.actual') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-white hover:bg-white/5 transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span x-show="isExpanded" 
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3">Mis Rutinas</span>
                </a>

                <!-- Nutrición -->
                <a href="{{ route('cliente.nutricion') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-white hover:bg-white/5 transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                    <span x-show="isExpanded" 
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3">Nutrición</span>
                </a>

                <!-- Comunicación -->
                <a href="{{ route('cliente.comunicacion') }}" 
                   class="flex items-center px-3 py-2.5 text-white hover:bg-white/10 rounded-lg {{ request()->routeIs('cliente.comunicacion*') ? 'bg-white/10' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span x-show="isExpanded" class="ml-3">Comunicación</span>
                    @if(isset($notificacionesNoLeidas) && $notificacionesNoLeidas > 0)
                        <span x-show="isExpanded" 
                              class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ $notificacionesNoLeidas }}
                        </span>
                    @endif
                </a>

                <!-- Asistencias -->
                <a href="{{ route('cliente.asistencias') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-white hover:bg-white/5 transition-all duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span x-show="isExpanded" 
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3">Asistencias</span>
                </a>

                <!-- Membresía -->
                <a href="{{ route('cliente.membresia') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('cliente.membresia') ? 'bg-white/10' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-show="isExpanded" 
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3">Membresía</span>
                </a>

                <!-- Pagos en Línea - Nueva opción -->
                <a href="{{ route('cliente.pagos.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('cliente.pagos.*') ? 'bg-white/10' : '' }}">
                    <x-icons.payment class="w-5 h-5 shrink-0" />
                    <span x-show="isExpanded" 
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3">Pagos en Línea</span>
                </a>

                <!-- Reportes -->
                <a href="{{ route('cliente.reportes') }}" 
                   class="flex items-center px-3 py-2.5 text-white hover:bg-white/10 rounded-lg {{ request()->routeIs('cliente.reportes*') ? 'bg-white/10' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span x-show="isExpanded" 
                          x-transition:enter="transition-opacity duration-300"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="ml-3">Reportes</span>
                </a>
            </div>

            <!-- User Profile Section -->
            <div class="absolute bottom-0 w-full border-t border-white/10 bg-gradient-to-br from-teal-700 to-emerald-900">
                <div class="p-3">
                    <div class="flex items-center p-2 rounded-lg bg-white/5 backdrop-blur-sm"
                         :class="{'justify-center': !isExpanded}">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div x-show="isExpanded" class="ml-3">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-white/60">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <!-- Botón de Cerrar Sesión -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center p-2 rounded-lg text-white hover:bg-white/10 transition-all duration-200">
                            <svg class="w-5 h-5" :class="{'mr-2': isExpanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span x-show="isExpanded">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div :class="{'ml-64': isExpanded, 'ml-16': !isExpanded}" 
             class="transition-all duration-300">
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html> 