<x-cliente-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Encabezado -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Centro de Comunicación</h1>
                <p class="text-gray-600">Mantente conectado con tu entrenador y al tanto de las novedades</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Mensajes con Entrenador -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="bg-emerald-100 p-2 rounded-lg mr-3">
                                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-800">Mensajes con Entrenador</h2>
                                        <p class="text-sm text-gray-500">Comunícate directamente con tu entrenador</p>
                                    </div>
                                </div>
                                <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                    Nuevo Mensaje
                                </button>
                            </div>

                            <div class="space-y-4 mb-6">
                                @forelse($mensajes as $mensaje)
                                    <div class="flex gap-4 {{ $mensaje->emisor_id === auth()->id() ? 'justify-end' : '' }}">
                                        <div class="max-w-[80%]">
                                            <div class="rounded-xl p-4 {{ $mensaje->emisor_id === auth()->id() ? 'bg-emerald-50 border border-emerald-100' : 'bg-gray-50 border border-gray-100' }}">
                                                <p class="text-sm text-gray-800">{{ $mensaje->contenido }}</p>
                                            </div>
                                            <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                                                <span>{{ $mensaje->created_at->format('d/m/Y H:i') }}</span>
                                                @if($mensaje->leido)
                                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <div class="bg-gray-50 inline-flex p-4 rounded-full mb-4">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 mb-2">No hay mensajes para mostrar</p>
                                        <p class="text-sm text-gray-400">Comienza una conversación con tu entrenador</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Formulario de Mensaje -->
                            <form action="{{ route('cliente.comunicacion.enviar-mensaje') }}" method="POST" class="mt-4">
                                @csrf
                                <div class="flex gap-3">
                                    <input type="text" name="contenido" 
                                           class="flex-1 rounded-xl border-gray-200 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-all"
                                           placeholder="Escribe tu mensaje...">
                                    <button type="submit" 
                                            class="px-6 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors flex items-center gap-2">
                                        <span>Enviar</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel Lateral -->
                <div class="space-y-6">
                    <!-- Notificaciones -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Notificaciones</h3>
                            </div>

                            <div class="space-y-3">
                                @forelse($notificaciones as $notificacion)
                                    <div class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-gray-100">
                                        <h4 class="font-medium text-gray-800">{{ $notificacion->titulo }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $notificacion->contenido }}</p>
                                        <div class="mt-2 flex justify-between items-center text-xs text-gray-500">
                                            <span>{{ $notificacion->created_at->diffForHumans() }}</span>
                                            @if($notificacion->link)
                                                <a href="{{ $notificacion->link }}" class="text-emerald-600 hover:text-emerald-700">
                                                    Ver más →
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <div class="bg-gray-50 inline-flex p-3 rounded-full mb-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500">No hay notificaciones nuevas</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Anuncios del Gimnasio -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Anuncios del Gimnasio</h3>
                            </div>

                            <div class="space-y-4">
                                @forelse($anuncios as $anuncio)
                                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                                        @if($anuncio->imagen_url)
                                            <img src="{{ $anuncio->imagen_url }}" 
                                                 alt="Imagen del anuncio"
                                                 class="w-full h-40 object-cover">
                                        @endif
                                        <div class="p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-medium text-gray-800">{{ $anuncio->titulo }}</h4>
                                                @if($anuncio->importante)
                                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                                        Importante
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600">{{ $anuncio->contenido }}</p>
                                            <div class="mt-3 text-xs text-gray-500">
                                                {{ Carbon\Carbon::parse($anuncio->fecha_publicacion)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <div class="bg-gray-50 inline-flex p-3 rounded-full mb-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500">No hay anuncios activos</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function comunicacionData() {
            return {
                marcarNotificacionLeida(id) {
                    fetch(`/cliente/comunicacion/notificaciones/${id}/marcar-leida`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                }
            }
        }
    </script>
    @endpush
</x-cliente-layout> 