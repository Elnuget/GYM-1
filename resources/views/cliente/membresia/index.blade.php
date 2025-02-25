<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Mi Membresía</h2>
                        <div class="bg-indigo-50 rounded-full p-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    @if($membresia)
                        <div class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl overflow-hidden shadow-lg">
                            <div class="px-8 py-10 relative">
                                <div class="absolute top-0 right-0 p-4">
                                    <span class="px-3 py-1 bg-green-400/20 text-green-100 rounded-full text-sm backdrop-blur-sm">
                                        Activa
                                    </span>
                                </div>

                                <div class="mb-8">
                                    <h3 class="text-2xl font-bold text-white mb-2">
                                        {{ $membresia->gimnasio->nombre }}
                                    </h3>
                                    <p class="text-indigo-100 text-sm">
                                        {{ $membresia->gimnasio->direccion }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-center text-white/90">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-indigo-100">Tipo de Membresía</p>
                                                <p class="font-medium">{{ ucfirst($membresia->tipo_membresia) }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center text-white/90">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-indigo-100">Fecha de Inicio</p>
                                                <p class="font-medium">{{ Carbon\Carbon::parse($membresia->fecha_inicio)->format('d/m/Y') }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center text-white/90">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-indigo-100">Vencimiento</p>
                                                <p class="font-medium">{{ Carbon\Carbon::parse($membresia->fecha_vencimiento)->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex items-center text-white/90">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-indigo-100">Días Restantes</p>
                                                <p class="font-medium">{{ Carbon\Carbon::now()->diffInDays($membresia->fecha_vencimiento) }} días</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center text-white/90">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-indigo-100">Costo Mensual</p>
                                                <p class="font-medium">S/. {{ number_format($membresia->costo, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 pt-6 border-t border-white/10">
                                    <h4 class="text-white font-medium mb-4">Beneficios incluidos:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="flex items-center text-white/90">
                                            <svg class="w-4 h-4 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-sm">Acceso ilimitado al gimnasio</span>
                                        </div>
                                        <div class="flex items-center text-white/90">
                                            <svg class="w-4 h-4 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-sm">Rutinas personalizadas</span>
                                        </div>
                                        <div class="flex items-center text-white/90">
                                            <svg class="w-4 h-4 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-sm">Asesoría nutricional</span>
                                        </div>
                                        <div class="flex items-center text-white/90">
                                            <svg class="w-4 h-4 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-sm">Seguimiento personalizado</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 pt-6 border-t border-white/10">
                                    <h4 class="text-white font-medium mb-4">Horario del Gimnasio:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-indigo-100">
                                        <div>
                                            <p class="font-medium text-white">Lunes a Viernes</p>
                                            <p>6:00 AM - 10:00 PM</p>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">Sábados y Domingos</p>
                                            <p>8:00 AM - 8:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="bg-indigo-50 rounded-full p-3 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes una membresía activa</h3>
                            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                                Para acceder a todos los beneficios del gimnasio, necesitas adquirir una membresía.
                                Contacta con el personal del gimnasio para conocer los planes disponibles.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-cliente-layout> 