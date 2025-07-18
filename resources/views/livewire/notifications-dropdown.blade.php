{{-- Dropdown de notificaciones completo y corregido --}}
<div class="relative"
     x-data="{ open: @entangle('showDropdown') }"
     x-init="
        $watch(() => {
            return document.querySelector('nav')?.__alpine?.$data?.scrolled ?? false;
        }, (value) => {
            $wire.set('scrolled', value);
        });
    ">
    <button @click="open = !open"
            :class="{
                'text-white hover:text-gray-200': $wire.scrolled,
                'text-gray-600 hover:text-gray-800': !$wire.scrolled
            }"
            class="relative p-2 transition-colors duration-300 focus:outline-none"
            aria-haspopup="true"
            aria-expanded="true">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-5 w-5" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke="currentColor">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="1.5" 
                  d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/>
            <path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
        </svg>

        @if ($unreadNotifications->count() > 0)
            <span class="absolute -top-0 -right-0 bg-red-500 text-white text-xs rounded-full h-3 w-3 md:h-4 md:w-4 flex items-center justify-center">
                {{ $unreadNotifications->count() }}
            </span>
        @endif
    </button>

    <div x-show="open"
         x-cloak
         @click.away="open = false"
         class="absolute right-0 mt-2 w-72 md:w-80 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">

        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-medium text-gray-900">Notificaciones</h3>
            @if ($unreadNotifications->count() > 0)
                <button wire:click="markAllAsRead" 
                        class="text-xs text-blue-600 hover:text-blue-800">
                    Marcar todas como leídas
                </button>
            @endif
        </div>

        <div class="max-h-60 overflow-y-auto">
            @forelse ($unreadNotifications as $notification)
                <div wire:click="markAsRead('{{ $notification->id }}')"
                   class="flex items-start px-4 py-3 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100 last:border-b-0 cursor-pointer">
                    <div class="flex-shrink-0 mt-1">
                        @php
                            $iconColor = 'text-blue-500'; // Default color
                            
                            if(isset($notification->data['status'])) {
                                $iconColor = $notification->data['status'] === \App\Models\UserApplication::STATUS_APPROVED 
                                    ? 'text-green-500' 
                                    : ($notification->data['status'] === \App\Models\UserApplication::STATUS_REJECTED 
                                        ? 'text-red-500' 
                                        : 'text-blue-500');
                            }
                        @endphp
                        <x-heroicon-s-information-circle class="h-5 w-5 {{ $iconColor }}"/>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-xs font-medium text-gray-900">
                            @if(isset($notification->data['requested_user_type']))
                                {{ \App\Models\UserApplication::TYPE_OPTIONS[$notification->data['requested_user_type']] ?? 'Solicitud' }}
                            @else
                                {{ $notification->data['title'] ?? 'Nueva Notificación' }}
                            @endif
                        </p>
                        
                        <p class="text-xs text-gray-600 mt-1">
                            @if(isset($notification->data['status']))
                                @if($notification->data['status'] === \App\Models\UserApplication::STATUS_APPROVED)
                                    ¡Solicitud aprobada!
                                @elseif($notification->data['status'] === \App\Models\UserApplication::STATUS_REJECTED)
                                    Solicitud rechazada
                                @else
                                    {{ $notification->data['body'] ?? 'Estado desconocido' }}
                                @endif
                            @else
                                {{ $notification->data['body'] ?? 'No hay descripción disponible' }}
                            @endif
                        </p>
                        
                        @if(isset($notification->data['link']))
                            <a href="{{ $notification->data['link'] }}" 
                               wire:click.stop
                               class="text-blue-600 hover:underline text-xs block mt-1"  wire:navigate>
                                Ver detalles
                            </a>
                        @endif
                        
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="px-4 py-3 text-sm text-gray-500 text-center">
                    No tienes notificaciones nuevas.
                </p>
            @endforelse

            @if ($unreadNotifications->isEmpty() && $readNotifications->isNotEmpty())
                <div class="px-4 py-2 border-t border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-500">
                        Notificaciones recientes
                    </h4>
                </div>
                @foreach ($readNotifications->take(3) as $notification)
                    <div wire:click="markAsRead('{{ $notification->id }}')"
                       class="flex items-start px-4 py-3 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100 last:border-b-0 opacity-75 cursor-pointer">
                        <div class="flex-shrink-0 mt-1">
                            @php
                                $iconColor = 'text-gray-500'; // Default color
                                
                                if(isset($notification->data['status'])) {
                                    $iconColor = $notification->data['status'] === \App\Models\UserApplication::STATUS_APPROVED 
                                        ? 'text-green-500' 
                                        : ($notification->data['status'] === \App\Models\UserApplication::STATUS_REJECTED 
                                            ? 'text-red-500' 
                                            : 'text-gray-500');
                                }
                            @endphp
                            <x-heroicon-s-information-circle class="h-5 w-5 {{ $iconColor }}"/>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-xs font-medium text-gray-900">
                                @if(isset($notification->data['requested_user_type']))
                                    {{ \App\Models\UserApplication::TYPE_OPTIONS[$notification->data['requested_user_type']] ?? 'Solicitud' }}
                                @else
                                    {{ $notification->data['title'] ?? 'Notificación' }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-600 mt-1">
                                @if(isset($notification->data['status']))
                                    @if($notification->data['status'] === \App\Models\UserApplication::STATUS_APPROVED)
                                        Solicitud aprobada
                                    @elseif($notification->data['status'] === \App\Models\UserApplication::STATUS_REJECTED)
                                        Solicitud rechazada
                                    @else
                                        {{ $notification->data['body'] ?? 'Estado desconocido' }}
                                    @endif
                                @else
                                    {{ $notification->data['body'] ?? 'Sin descripción' }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        @if ($unreadNotifications->isNotEmpty() || $readNotifications->isNotEmpty())
            <div class="border-t border-gray-100 text-center">
                <a href="#"
                   class="block px-4 py-2 text-sm text-blue-600 hover:text-blue-800"  wire:navigate>
                    Ver todas las notificaciones
                </a>
            </div>
        @endif
    </div>
</div>