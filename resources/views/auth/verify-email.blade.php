{{--
    Este layout se utiliza para páginas que no requieren autenticación,
    como la verificación de correo electrónico.
    Incluye un fondo con patrón y elementos centrados para la interacción del usuario.
--}}
<x-guest-layout>
    <div class="relative h-full w-full min-h-screen bg-white overflow-hidden">

        <div class="absolute inset-0 bg-[linear-gradient(to_right,#4f4f4f1a_1px,transparent_1px),linear-gradient(to_bottom,#4f4f4f1a_1px,transparent_1px)] bg-[size:14px_24px] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_110%)] z-0"></div>

        <div class="relative z-10 flex flex-col justify-center py-12 sm:px-6 lg:px-8 min-h-screen">

            <div class="absolute top-6 left-6">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white rounded-lg shadow-sm hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200 border border-gray-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Regresar
                </a>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md mt-2">
                <div class="flex justify-center">
                    <div class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 3.26a2 2 0 001.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <h1 class="mt-6 text-center text-3xl font-bold text-gray-900">
                    Verifica tu correo electrónico
                </h1>

            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-6 shadow-xl rounded-2xl sm:px-10 border border-gray-100">

                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <p class="text-sm text-gray-600 leading-relaxed">
                            Antes de continuar, por favor verifica tu dirección de correo electrónico haciendo clic en el enlace que acabamos de enviarte.
                        </p>
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <p class="text-sm font-medium text-green-800">
                                    ¡Correo enviado! Hemos enviado un nuevo enlace de verificación a tu dirección de correo.
                                </p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}" class="space-y-6">
                        @csrf

                        <div>
                            <button type="submit"
                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reenviar correo de verificación
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">
                                ¿No encuentras el correo? Revisa tu carpeta de spam o correo no deseado
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500">
                    ¿Necesitas ayuda? <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium">Contacta soporte</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
