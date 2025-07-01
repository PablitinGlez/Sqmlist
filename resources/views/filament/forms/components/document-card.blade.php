@props([
    'record',
    'title',
    'description',
    'filePath',
    'buttonText',
    'buttonColor' => 'custom-blue',
])

<div>
    <div class="flex justify-start w-lg">
        @if ($filePath)
            <x-filament::button
                x-data=""
                x-on:click="$dispatch('open-modal', { 
                    id: 'document-modal-{{ $record->id }}-{{ md5($filePath) }}',
                    title: '{{ $title }}',
                    fileUrl: '{{ Storage::url($filePath) }}',
                    fileName: '{{ basename($filePath) }}'
                })"
                color="custom-blue"
                size="md"
                icon="heroicon-o-eye"
                icon-position="after"
                class="w-full max-w-xs"
            >
                {{ $buttonText }}
            </x-filament::button>
        @else
            <x-filament::badge
                color="gray"
                size="md"
                icon="heroicon-o-x-circle"
                class="w-full max-w-xs text-center py-2"
            >
                No disponible
            </x-filament::badge>
        @endif
    </div>
</div>

@if ($filePath)
<div 
    x-data="{ 
        open: false,
        fileUrl: '',
        fileName: '',
        title: ''
    }"
    x-on:open-modal.window="
        if ($event.detail.id === 'document-modal-{{ $record->id }}-{{ md5($filePath) }}') {
            open = true;
            fileUrl = $event.detail.fileUrl;
            fileName = $event.detail.fileName;
            title = $event.detail.title;
        }
    "
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div 
        x-show="open"
        x-on:click.away="open = false"
        class="relative w-full max-w-6xl h-[95vh] bg-white dark:bg-gray-900 rounded-lg shadow-xl overflow-hidden flex flex-col"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="title"></h3>
            <div class="flex items-center gap-x-2">
                <x-filament::button
                    x-bind:href="fileUrl"
                    download
                    color="gray"
                    size="sm"
                    icon="heroicon-o-arrow-down-tray"
                    tag="a"
                    x-tooltip="'Descargar'"
                >Descargar
                </x-filament::button>
                
                <x-filament::button
                    x-on:click="open = false"
                    color="gray"
                    size="sm"
                    icon="heroicon-o-x-mark"
                    x-tooltip="'Cerrar'"
                >Cerrar
                </x-filament::button>
            </div>
        </div>

        <div class="flex-1 overflow-auto">
            <div class="w-full h-full bg-gray-100 dark:bg-gray-800">
                <iframe 
                    x-bind:src="fileUrl"
                    class="w-full h-full border-0"
                    style="min-height: calc(95vh - 80px);"
                    x-show="fileUrl.toLowerCase().includes('.pdf')"
                    loading="lazy"
                ></iframe>
                
                <div 
                    x-show="fileUrl.toLowerCase().match(/\.(jpg|jpeg|png|gif|webp)$/)"
                    class="w-full h-full flex items-center justify-center p-4"
                    style="min-height: calc(95vh - 80px);"
                >
                    <img 
                        x-bind:src="fileUrl"
                        x-bind:alt="fileName"
                        class="max-w-full max-h-full object-contain"
                        loading="lazy"
                    >
                </div>
                
                <div 
                    x-show="!fileUrl.toLowerCase().includes('.pdf') && !fileUrl.toLowerCase().match(/\.(jpg|jpeg|png|gif|webp)$/)"
                    class="flex flex-col items-center justify-center h-full p-8 text-center"
                    style="min-height: calc(95vh - 80px);"
                >
                    <x-filament::icon
                        icon="heroicon-o-document"
                        class="w-16 h-16 text-gray-400 mb-4"
                    />
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Este tipo de archivo no se puede previsualizar en el navegador.
                    </p>
                    <x-filament::button
                        x-bind:href="fileUrl"
                        target="_blank"
                        color="primary"
                        size="sm"
                        icon="heroicon-o-arrow-top-right-on-square"
                        tag="a"
                    >
                        Abrir en nueva pestaña
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif