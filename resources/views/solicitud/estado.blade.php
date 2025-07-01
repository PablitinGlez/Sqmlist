{{--
    Esta vista muestra el estado actual de la solicitud de perfil de un usuario.
    Se adapta dinámicamente para mostrar mensajes y acciones según si la solicitud está
    pendiente, aprobada o rechazada. También detalla la información enviada en la solicitud.
--}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto mt-16 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    @php
                        // Asegúrate de que UserApplication::TYPE_OPTIONS esté definido en tu modelo App\Models\UserApplication
                        $requestedUserTypeLabel = \App\Models\UserApplication::TYPE_OPTIONS[$userApplication->requested_user_type] ?? 'un perfil';
                    @endphp

                    {{-- Mensajes de estado de la solicitud --}}
                    @if($userApplication->status === 'pending')
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Solicitud en Revisión</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Tu solicitud para ser {{ $requestedUserTypeLabel }} fue enviada el {{ $userApplication->created_at->format('d/m/Y H:i') }}.
                                <br>
                                La estamos revisando y pronto te contactaremos.
                            </p>
                            <div class="mt-6 flex justify-center space-x-3">
                                <a href="{{ route('home') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    Ir al Inicio
                                </a>
                            </div>
                        </div>
                    @elseif($userApplication->status === 'approved')
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">¡Solicitud Aprobada!</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                ¡Felicidades! Tu solicitud para ser **{{ $requestedUserTypeLabel }}** ha sido aprobada.
                            </p>
                            @if($userApplication->status_message)
                                <div class="mt-4 p-4 bg-green-50 rounded-md">
                                    <p class="text-sm text-green-800">{{ $userApplication->status_message }}</p>
                                </div>
                            @endif
                            <div class="mt-6">
                                <a href="{{ route('dashboard') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Ir al Dashboard
                                </a>
                            </div>
                        </div>
                    @elseif($userApplication->status === 'rejected')
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Solicitud Rechazada</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Lamentamos informarte que tu solicitud para ser **{{ $requestedUserTypeLabel }}** no fue aprobada.
                            </p>
                            @if($userApplication->status_message)
                                <div class="mt-4 p-4 bg-red-50 rounded-md">
                                    <p class="text-sm text-red-800">{{ $userApplication->status_message }}</p>
                                </div>
                            @endif
                            <div class="mt-6">
                                <a href="{{ route('solicitud.formulario') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Enviar Nueva Solicitud
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Sección de detalles de la solicitud --}}
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Detalles de la Solicitud</h4>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-16 gap-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:space-x-2">
                                <dt class="text-sm font-medium text-gray-500 flex-shrink-0">Número de Teléfono:</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $userApplication->phone_number }}</dd>
                            </div>

                            @if($userApplication->whatsapp_number)
                                <div class="flex flex-col sm:flex-row sm:items-baseline sm:space-x-2">
                                    <dt class="text-sm font-medium text-gray-500 flex-shrink-0">Número de WhatsApp:</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $userApplication->whatsapp_number }}</dd>
                                </div>
                            @endif
                            @if($userApplication->contact_email)
                                <div class="flex flex-col sm:flex-row sm:items-baseline sm:space-x-2">
                                    <dt class="text-sm font-medium text-gray-500 flex-shrink-0">Email:</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $userApplication->contact_email }}</dd>
                                </div>
                            @endif

                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:space-x-2">
                                <dt class="text-sm font-medium text-gray-500 flex-shrink-0">Tipo de Solicitud:</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $requestedUserTypeLabel }}</dd>
                            </div>

                            @if($userApplication->years_experience !== null) {{-- Usar !== null para incluir 0 años --}}
                                <div class="flex flex-col sm:flex-row sm:items-baseline sm:space-x-2">
                                    <dt class="text-sm font-medium text-gray-500 flex-shrink-0">Años de experiencia:</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $userApplication->years_experience }} año{{ $userApplication->years_experience != 1 ? 's' : '' }}</dd>
                                </div>
                            @endif

                            @if($userApplication->real_estate_company)
                                <div class="flex flex-col sm:flex-row sm:items-baseline sm:space-x-2">
                                    <dt class="text-sm font-medium text-gray-500 flex-shrink-0">Inmobiliaria/Empresa:</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $userApplication->real_estate_company }}</dd>
                                </div>
                            @endif

                            @if($userApplication->rfc) {{-- Mostrar RFC si existe --}}
                                <div class="flex flex-col sm:flex-row sm:items-baseline sm:space-x-2">
                                    <dt class="text-sm font-medium text-gray-500 flex-shrink-0">RFC:</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $userApplication->rfc }}</dd>
                                </div>
                            @endif

                            @if($userApplication->identification_type)
                                <div class="flex flex-col sm:flex-row sm:items-baseline sm:space-x-2">
                                    <dt class="text-sm font-medium text-gray-500 flex-shrink-0">Tipo de Identificación:</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $userApplication->identification_type }}</dd>
                                </div>
                            @endif

                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:space-x-2">
                                <dt class="text-sm font-medium text-gray-500 flex-shrink-0">Fecha de solicitud:</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $userApplication->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:space-x-2">
                                <dt class="text-sm font-medium text-gray-500 flex-shrink-0">Estado:</dt>
                                <dd class="mt-1 sm:mt-0 font-semibold">
                                    @if($userApplication->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                    @elseif($userApplication->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aprobada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rechazada
                                        </span>
                                    @endif
                                </dd>
                            </div>

                            <div class="sm:col-span-2 mt-4 border-t border-gray-200 pt-4">
                                <h5 class="text-sm font-medium text-gray-900 mb-2">Documentos Adjuntos</h5>
                                <div class="space-y-3">
                                    @if($userApplication->identification_path)
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md bg-gray-50">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span class="text-sm font-medium text-gray-700">Documento de Identificación</span>
                                            </div>
                                            <a href="{{ Storage::url($userApplication->identification_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver / Descargar</a>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">Documento de identificación no disponible.</p>
                                    @endif

                                    @if($userApplication->license_path)
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md bg-gray-50">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{-- Muestra una etiqueta más genérica si no es específicamente una "licencia" --}}
                                                    {{ ($userApplication->requested_user_type === 'agent' || $userApplication->requested_user_type === 'real_estate_company') ? 'Licencia/Documento Profesional' : 'Documento de respaldo' }}
                                                </span>
                                            </div>
                                            <a href="{{ Storage::url($userApplication->license_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver / Descargar</a>
                                        </div>
                                    @else
                                        {{-- Solo mostrar este mensaje si el tipo de usuario *requiere* una licencia pero no se subió --}}
                                        @php
                                            $requiresLicense = in_array($userApplication->requested_user_type, ['agent', 'real_estate_company']);
                                        @endphp
                                        @if($requiresLicense)
                                            <p class="text-sm text-gray-500">Documento de licencia/profesional no disponible.</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
