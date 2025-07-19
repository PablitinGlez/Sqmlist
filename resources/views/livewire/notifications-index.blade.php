<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6 text-center">
                <i class="fas fa-bell text-blue-500 mr-2"></i> Mis Notificaciones
            </h1>

            @auth
                @if ($notifications->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                        <dotlottie-wc
                            src="https://lottie.host/04ca74d0-e328-4b13-aaa9-794ddfb8bbb9/M7KjbiqXON.lottie"
                            style="width: 250px; height: 250px;"
                            speed="1"
                            autoplay
                            loop
                        ></dotlottie-wc>
                        <p class="mt-6 text-xl font-semibold text-gray-700">
                            ¡No tienes notificaciones nuevas!
                        </p>
                        <p class="mt-2 text-gray-500 max-w-md">
                            Mantente atento, aquí te informaremos sobre actualizaciones importantes de tu cuenta y propiedades.
                        </p>
                    </div>
                @else
                    <div class="flex justify-end mb-4">
                        <button
                            wire:click="markAllAsRead"
                            :disabled="$unreadCount === 0" {{-- <--- ¡NUEVO! Deshabilita si no hay notificaciones sin leer --}}
                            class="px-4 py-2 rounded-md transition-colors text-sm
                                {{ $unreadCount === 0 ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-blue-500 text-white hover:bg-blue-600' }}" {{-- <--- ¡NUEVO! Clases condicionales --}}
                        >
                            <i class="fas fa-check-double mr-1"></i> Marcar todas como leídas
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach ($notifications as $notification)
                            <div class="p-4 rounded-lg shadow-sm {{ $notification->read_at ? 'bg-gray-100 text-gray-600' : 'bg-white border border-gray-200' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-semibold {{ $notification->read_at ? 'text-gray-600' : 'text-gray-800' }}">
                                            {{ $notification->data['title'] ?? 'Notificación sin título' }}
                                        </p>
                                        <p class="text-sm {{ $notification->read_at ? 'text-gray-500' : 'text-gray-700' }} mt-1">
                                            {{ $notification->data['body'] ?? 'Contenido de la notificación.' }}
                                        </p>
                                        @if (isset($notification->data['link']))
                                            <a href="{{ $notification->data['link'] }}" class="text-blue-500 hover:underline text-xs mt-2 inline-block" wire:navigate>
                                                Ver detalles
                                            </a>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0 ml-4 text-right">
                                        <span class="text-xs text-gray-400">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        @if (!$notification->read_at)
                                            <button wire:click="markAsRead('{{ $notification->id }}')" class="block mt-2 text-blue-500 hover:text-blue-700 text-xs">
                                                Marcar como leída
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                {{-- Message for unauthenticated users --}}
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <i class="fas fa-lock text-gray-400 text-6xl mb-6"></i>
                    <p class="mt-6 text-xl font-semibold text-gray-700">
                        ¡Inicia sesión para ver tus notificaciones!
                    </p>
                    <p class="mt-2 text-gray-500 max-w-md">
                        Crea una cuenta o inicia sesión para mantenerte al día con las novedades de tu cuenta.
                    </p>
                    <a href="{{ route('login') }}" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" wire:navigate>
                        <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
