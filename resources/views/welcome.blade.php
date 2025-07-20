{{-- resources/views/welcome.blade.php --}}
{{--
    Esta vista representa la página principal de la aplicación,
    sirviendo como el punto de aterrizaje para los usuarios.
    Organiza varias secciones del sitio mediante componentes Blade.
--}}
<x-app-layout>
    <x-sections.hero></x-sections.hero>
    <x-sections.introduction></x-sections.introduction>
    <x-sections.explore-cities></x-sections.explore-cities> {{-- <--- ¡NUEVO! --}}
   
    <x-sections.statistics></x-sections.statistics>
</x-app-layout>
