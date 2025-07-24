@props(['backgroundImage' => 'images/ola.webp', 'title' => 'Conecta con tu nuevo hogar en solo unos clics'])

<section class="relative h-[80vh] flex items-center justify-center overflow-hidden">
    <div id="hero-skeleton" class="absolute inset-0 z-20 bg-gray-100 animate-pulse flex items-center justify-center">
        <div class="absolute inset-0 bg-gray-300"></div>

        <div class="relative z-10 w-full">
            <x-partials.container>
                <div class="backdrop-blur-sm bg-gray-200/50 border border-gray-300/20 rounded-2xl p-8 md:p-12 max-w-4xl mx-auto text-start md:text-center sm:text-center shadow-2xl">

                    <div class="max-w-2xl text-xl sm:text md:text-2xl lg:text-2xl mb-8 leading-tight">
                        <div class="h-7 bg-gray-300 rounded-full mb-2"></div>
                    </div>

                    <div class="flex justify-start max-w-2xl mx-auto">
                        <div class="flex gap-8">
                            <div class="h-8 w-16 bg-gray-300 rounded"></div>
                            <div class="h-8 w-16 bg-gray-300 rounded"></div>
                        </div>
                    </div>

                    <div class="max-w-2xl mx-auto h-px bg-gray-300 mb-8 mt-8"></div>

                    <div class="flex flex-col md:flex-row gap-4 max-w-2xl mx-auto">
                        <div class="relative">
                            <div class="w-full md:w-48 h-12 bg-gray-300 rounded-lg"></div>
                        </div>

                        <div class="flex-1 relative">
                            <div class="w-full h-12 bg-gray-300 rounded-lg"></div>
                        </div>
                    </div>
                </div>
            </x-partials.container>
        </div>

        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
            <div class="w-6 h-10 bg-gray-300 rounded-full"></div>
        </div>
    </div>

    <div class="absolute inset-0 z-0">
        <img id="hero-image"
             src="{{ asset($backgroundImage) }}"
             alt="Hero Background"
             class="w-full h-full object-cover opacity-0 transition-opacity duration-500"
             loading="eager">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>


<div id="hero-content" class="relative z-10 w-full opacity-0">
    <x-partials.container>
        <div class="backdrop-blur-sm bg-white/5 border border-white/10 rounded-2xl p-8 md:p-12 max-w-4xl mx-auto text-start md:text-center sm:text-center shadow-2xl">
            <div class="relative max-w-2xl mx-auto">
                <h1 id="hero-title"
                class="text-xl sm:text md:text-2xl lg:text-2xl font-semibold text-white mb-8 leading-tight"
                data-original-text="Conecta con tu nuevo hogar en solo unos clics"
                style="opacity: 0;">
                Conecta con tu nuevo hogar en solo unos clics
                </h1>
            </div>

            @livewire('hero-search', [
                'initialLocationSearch' => request('ubicacion'),
                'initialPropertyType' => request('tipo'),
                'initialOperationType' => request('operacion')
            ])
        </div>
    </x-partials.container>
</div>

    <div id="scroll-icon" class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 opacity-0 transition-opacity duration-500">
        <div class="animate-bounce">
            <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </div>
</section>
<script>
    function hideHeroSkeleton() {
        const skeleton = document.getElementById('hero-skeleton');
        const image = document.getElementById('hero-image');
        const content = document.getElementById('hero-content');
        const scrollIcon = document.getElementById('scroll-icon');

        if (skeleton && image && content && scrollIcon) {
            if (skeleton.style.display !== 'none' || skeleton.style.opacity !== '0') {
                skeleton.style.opacity = '0';
                setTimeout(() => {
                    skeleton.style.display = 'none';
                }, 200);

                image.style.opacity = '1';
                content.style.opacity = '1';
                scrollIcon.style.opacity = '1';

                document.dispatchEvent(new CustomEvent('hero-content-loaded'));
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const heroImage = document.getElementById('hero-image');

        if (heroImage && heroImage.complete && heroImage.naturalHeight !== 0) {
            hideHeroSkeleton();
        } else if (heroImage) {
            heroImage.addEventListener('load', hideHeroSkeleton);
            heroImage.addEventListener('error', hideHeroSkeleton);
        } else {
            setTimeout(hideHeroSkeleton, 300);
        }
    });

    document.addEventListener('livewire:navigated', () => {
        hideHeroSkeleton();
    });
</script>


<style>
    
.typing-cursor::after {
    content: '|';
    color: white;
    font-weight: normal;
    animation: cursor-blink 1s infinite;
    margin-left: 2px;
}

@keyframes cursor-blink {
    0%, 50% {
        opacity: 1;
    }
    51%, 100% {
        opacity: 0;
    }
}



</style>