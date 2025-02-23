<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Encabezado de Bienvenida -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    ¡Bienvenido{{ isset($gimnasio) ? ' a ' . $gimnasio->nombre : '' }}!
                </h1>
                <p class="text-lg text-gray-600">Estamos emocionados de tenerte como nuevo miembro</p>
            </div>

            <!-- Tarjetas de Próximos Pasos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <!-- Paso 1: Elegir Membresía -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-emerald-100">
                    <div class="flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-full mb-4">
                        <span class="text-xl font-bold text-emerald-600">1</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Elige tu Membresía</h3>
                    <p class="text-gray-600 mb-4">Selecciona el plan que mejor se adapte a tus objetivos y necesidades.</p>
                    <a href="{{ route('cliente.membresias.index') }}" 
                       class="inline-block w-full text-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        Ver Membresías
                    </a>
                </div>

                <!-- Paso 2: Completar Perfil -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-emerald-100">
                    <div class="flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-full mb-4">
                        <span class="text-xl font-bold text-emerald-600">2</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Completa tu Perfil</h3>
                    <p class="text-gray-600 mb-4">Actualiza tu información personal y preferencias de entrenamiento.</p>
                    <a href="{{ route('cliente.perfil.index') }}" 
                       class="inline-block w-full text-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        Ir a mi Perfil
                    </a>
                </div>

                <!-- Paso 3: Explorar Servicios -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-emerald-100">
                    <div class="flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-full mb-4">
                        <span class="text-xl font-bold text-emerald-600">3</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Explora Servicios</h3>
                    <p class="text-gray-600 mb-4">Descubre las clases, entrenadores y servicios disponibles.</p>
                    <a href="{{ route('cliente.servicios.index') }}" 
                       class="inline-block w-full text-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        Ver Servicios
                    </a>
                </div>
            </div>

            <!-- Información del Gimnasio -->
            <div class="bg-white rounded-xl shadow-sm p-8 border border-emerald-100">
                <h2 class="text-2xl font-semibold mb-6">Información Importante</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-lg mb-2">Horario del Gimnasio</h3>
                        <p class="text-gray-600">{{ $gimnasio->horario }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg mb-2">Ubicación</h3>
                        <p class="text-gray-600">{{ $gimnasio->direccion }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg mb-2">Contacto</h3>
                        <p class="text-gray-600">
                            Teléfono: {{ $gimnasio->telefono }}<br>
                            Email: {{ $gimnasio->email }}
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg mb-2">Redes Sociales</h3>
                        <div class="flex space-x-4">
                            @if($gimnasio->facebook)
                                <a href="{{ $gimnasio->facebook }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    <i class="fab fa-facebook fa-2x"></i>
                                </a>
                            @endif
                            @if($gimnasio->instagram)
                                <a href="{{ $gimnasio->instagram }}" target="_blank" class="text-pink-600 hover:text-pink-800">
                                    <i class="fab fa-instagram fa-2x"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 