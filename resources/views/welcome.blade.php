{{-- resources/views/home.blade.php --}}
{{--
    Esta vista representa la página principal de la aplicación,
    sirviendo como el punto de aterrizaje para los usuarios.
    Organiza varias secciones del sitio mediante componentes Blade.
--}}
<x-app-layout>

    <x-sections.hero></x-sections.hero>
    <x-sections.introduction></x-sections.introduction>
    <x-sections.featured-properties></x-sections.featured-properties>
    <x-sections.statistics></x-sections.statistics>
    <x-sections.steps></x-sections.steps>
    
    </x-app-layout>
    