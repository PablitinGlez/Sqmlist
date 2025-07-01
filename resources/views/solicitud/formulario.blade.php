{{--
    Esta vista carga el componente Livewire para el formulario de solicitud de perfil de usuario.
    Permite a los usuarios enviar su información y documentos para solicitar un tipo de perfil específico.
--}}
<x-app-layout>
    <div class="py-12 mt-16">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                
                @livewire('user-application-form', ['preselectedType' => $preselectedType, 'userTypesOptions' => $userTypesOptions])
            </div>
        </div>
    </div>
</x-app-layout>
