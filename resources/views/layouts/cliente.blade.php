@php
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased" x-data="{ mobileMenuOpen: false, isExpanded: localStorage.getItem('sidebarExpanded') === 'true' }" 
      x-init="$watch('isExpanded', value => localStorage.setItem('sidebarExpanded', value))">
    <div class="min-h-screen bg-gray-50">
        <!-- Barra lateral de navegación -->
        <div x-data="{ mobileMenuOpen: false }">
            <div class="min-h-screen">
                @include('layouts.navigation')
                <!-- NO INCLUIR SLOT AQUÍ -->
            </div>
        </div>

        <!-- NO INCLUIR navigation.blade.php DE NUEVO -->
        
        @if (session('success'))
            <div x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="fixed bottom-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Script para redirigir el enlace Panel a /cliente/dashboard -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Buscar todos los enlaces que contienen "Panel"
            const panelLinks = Array.from(document.querySelectorAll('a')).filter(
                link => link.textContent.trim() === 'Panel'
            );
            
            // Cambiar la URL de destino de esos enlaces
            panelLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = "{{ route('cliente.dashboard') }}";
                });
            });
        });
    </script>
</body>
</html> 