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
</head>
<body class="font-sans antialiased" x-data="{ mobileMenuOpen: false, isExpanded: false }">
    <div class="min-h-screen bg-gray-50">
        <!-- Incluir el menú de navigation.blade.php -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <div :class="{'lg:ml-16': !isExpanded, 'lg:ml-64': isExpanded}"
             class="transition-all duration-300">
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    
    @if (session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
</body>
</html> 