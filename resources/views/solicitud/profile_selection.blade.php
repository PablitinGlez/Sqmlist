{{--
    Esta vista permite a los usuarios seleccionar el tipo de perfil de anunciante
    con el que se identifican (Dueño Directo, Agente, Inmobiliaria).
    Muestra las opciones disponibles y redirige al formulario de solicitud con el tipo preseleccionado.
--}}
<x-app-layout>
    <div class="bg-transparent relative overflow-hidden mt-24">
        <div class="absolute top-0 left-0 right-0 h-[330px] bg-gradient-to-t from-blue-50 to-transparent"></div>

        <div class="absolute right-72 top-1/5 w-20 h-20 opacity-30 hidden md:block">
            <img src="{{ asset('images/shapes/Highlight_10.svg') }}">
        </div>

        <div class="absolute right-32 top-1/3 w-16 h-16 opacity-30 hidden md:block">
            <img src="{{ asset('images/shapes/Highlight_07.svg') }}">
        </div>

        <div class="absolute left-32 top-16 w-20 h-20 opacity-30 hidden md:block">
            <img src="{{ asset('images/shapes/Arrow_05.svg') }}">
        </div>

        <div class="absolute right-1/4 bottom-1/4 w-14 h-14 opacity-5 hidden md:block">
        </div>

        <div class="relative z-10 py-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        ¿Con qué perfil
                        <span class="text-blue-600">te identificas?</span>
                    </h1>
                    <p class="text-base md:text-lg lg:text-xl text-gray-600">
                        Selecciona el que se ajusta a tus intereses
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                    @foreach($profiles as $key => $profile)
                        <div class="group cursor-pointer" onclick="selectProfile('{{ $key }}')">
                            <div class="bg-white rounded-xl transition-all duration-200 border border-gray-200 overflow-hidden h-48 md:h-60">
                                <div class="h-1 bg-blue-600"></div>

                                <div class="p-4 md:p-8 text-center h-full flex flex-col justify-between">
                                    <div class="flex-1 flex flex-col justify-center">
                                        <div class="mb-6">
                                            @if($key === 'owner')
                                                <img src="{{ asset('images/icons/dueno-de-casa.png') }}" alt="Icono Dueño Directo" class="w-16 h-16 mx-auto mb-3">
                                            @elseif($key === 'agent')
                                                <img src="{{ asset('images/icons/apreton-de-manos.png') }}" alt="Icono Agente" class="w-16 h-16 mx-auto mb-3">
                                            @elseif($key === 'real_estate_company')
                                                <img src="{{ asset('images/icons/edificio.png') }}" alt="Icono Inmobiliaria" class="w-16 h-16 mx-auto mb-3">
                                            @endif
                                        </div>

                                        <h3 class="text-lg font-normal text-gray-900 leading-tight">
                                            {{ $profile['title'] }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    function selectProfile(profileType) {
        
        window.location.href = `{{ route('solicitud.formulario') }}?type=${profileType}`;
    }
</script>
@endpush
</x-app-layout>