<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @if($isLoading)
            @for($i = 0; $i < 8; $i++)
                @include('components.property-card-skeleton')
            @endfor
        @else
            @forelse($properties as $property)
                <x-property-card :property="$property" />
            @empty
                <div class="col-span-full text-center py-10 flex flex-col items-center justify-center">
                    <p class="text-gray-500 text-lg mb-4">No hay propiedades publicadas que coincidan con los criterios.</p>

                    <dotlottie-wc
                        src="https://lottie.host/b05d2783-7d4a-4d54-8707-f3e37523d229/U2Bf8GIxG4.lottie"
                        style="width: 300px; height: 300px;"
                        speed="1"
                        autoplay
                        loop
                    ></dotlottie-wc>
                </div>
            @endforelse
        @endif
    </div>

    @if(!$isLoading)
     
    @endif
</div>