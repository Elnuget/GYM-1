{{-- Plantilla principal para el registro de cliente --}}
<x-app-layout>
    @php
        // Verificar si el usuario actual tiene membresías
        $tieneMembresiaActiva = \App\Models\Membresia::where('id_usuario', auth()->id())->exists();
        
        // Verificar si tiene membresía pero con pagos pendientes
        $tieneMembresiaConPagoPendiente = false;
        if ($tieneMembresiaActiva) {
            $membresia = \App\Models\Membresia::where('id_usuario', auth()->id())->latest()->first();
            if ($membresia && $membresia->saldo_pendiente > 0) {
                // Verificar si tiene algún pago registrado
                $tienePago = \App\Models\Pago::where('id_membresia', $membresia->id_membresia)->exists();
                $tieneMembresiaConPagoPendiente = !$tienePago;
            }
        }
        
        // Siempre definimos 5 pasos fijos
        $totalPasos = 5;
        
        // Verificar si hay un paso específico en la URL
        $pasoUrl = request()->query('paso');
        
        // Determinar el paso inicial según la situación del usuario
        if ($pasoUrl && is_numeric($pasoUrl)) {
            // Si hay un paso en la URL, usar ese
            $pasoInicial = (int)$pasoUrl;
        } elseif ($tieneMembresiaConPagoPendiente) {
            $pasoInicial = 2; // Si tiene membresía con pago pendiente, ir al paso 2 (Pago)
        } elseif ($tieneMembresiaActiva) {
            // Si tiene membresía activa, verificar si ya tiene pagos
            $membresia = \App\Models\Membresia::where('id_usuario', auth()->id())->latest()->first();
            $tienePagos = \App\Models\Pago::where('id_membresia', $membresia->id_membresia)->exists();
            
            if ($tienePagos) {
                $pasoInicial = 3; // Si ya tiene pagos, ir al paso 3 (Información Personal)
            } else {
                $pasoInicial = 2; // Si no tiene pagos, ir al paso 2 (Pago)
            }
        } else {
            $pasoInicial = session('current_step', 1); // Caso normal, empezar en paso 1 o recuperar el último paso
        }
    @endphp
    
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Componente principal del formulario de registro --}}
            @include('components.registro-cliente.formulario-registro')
        </div>
    </div>
</x-app-layout> 