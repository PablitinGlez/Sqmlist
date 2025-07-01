<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg my-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-6 text-center">Solicitud de Perfil de Usuario</h1>
    <p class="text-center text-gray-600 mb-8">Por favor, completa el siguiente formulario para solicitar tu tipo de perfil en nuestra plataforma.</p>

    {{-- Mensajes de sesión --}}
    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">¡Éxito!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">¡Error!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Información:</p>
            <p>{{ session('info') }}</p>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-8">
        
        {{-- Sección: Tipo de Perfil --}}
        <div>
            <label for="userType" class="block text-base font-semibold text-gray-800 mb-2">
                Tipo de Perfil <span class="text-red-500">*</span>
            </label>
            <select wire:model.live="userType" id="userType"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('userType') border-red-500 @enderror">
                <option value="">Selecciona una opción</option>
                @foreach ($userTypesOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('userType')
                <p class="text-red-500 text-sm mt-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Sección: Datos de Contacto --}}
        <div class="border-t border-gray-200 pt-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Datos de Contacto</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="phoneNumber" class="block text-base font-medium text-gray-700 mb-2">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" wire:model.blur="phoneNumber" id="phoneNumber" 
                            placeholder="Ej. 525512345678"
                                             
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phoneNumber') border-red-500 @enderror">
                    @error('phoneNumber')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="whatsappNumber" class="block text-base font-medium text-gray-700 mb-2">
                        Número de WhatsApp (Opcional)
                    </label>
                    <input type="tel" wire:model.blur="whatsappNumber" id="whatsappNumber" 
                            placeholder="Ej. 525512345678"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('whatsappNumber') border-red-500 @enderror">
                    @error('whatsappNumber')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="contactEmail" class="block text-base font-medium text-gray-700 mb-2">
                        Email de Contacto (Opcional)
                    </label>
                    <input type="email" wire:model.blur="contactEmail" id="contactEmail" 
                            placeholder="tu@email.com"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('contactEmail') border-red-500 @enderror">
                    @error('contactEmail')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Campos para Agentes e Inmobiliarias --}}
        @if($this->needsLicense())
        <div class="border-t border-gray-200 pt-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                @if($userType === 'real_estate_company')
                    Información de la Empresa
                @else
                    Información Profesional
                @endif
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="yearsExperience" class="block text-base font-medium text-gray-700 mb-2">
                        @if($userType === 'real_estate_company')
                            Años en el Sector <span class="text-red-500">*</span>
                        @else
                            Años de Experiencia <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <select wire:model.blur="yearsExperience" id="yearsExperience"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('yearsExperience') border-red-500 @enderror">
                        <option value="">Selecciona...</option>
                        @for($i = 0; $i <= 50; $i++)
                            <option value="{{ $i }}">{{ $i }} año{{ $i != 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                    @error('yearsExperience')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="realEstateCompany" class="block text-base font-medium text-gray-700 mb-2">
                        @if($userType === 'real_estate_company')
                            Nombre de la Inmobiliaria <span class="text-red-500">*</span>
                        @else
                            Inmobiliaria (Opcional)
                        @endif
                    </label>
                    <input type="text" wire:model.blur="realEstateCompany" id="realEstateCompany"
                            placeholder="Nombre de la inmobiliaria o empresa"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('realEstateCompany') border-red-500 @enderror">
                    @error('realEstateCompany')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                @if($userType === 'real_estate_company')
                <div class="md:col-span-2">
                    <label for="rfc" class="block text-base font-medium text-gray-700 mb-2">
                        RFC <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model.blur="rfc" id="rfc"
                            placeholder="Ej. XAXX010101000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rfc') border-red-500 @enderror">
                    @error('rfc')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Sección: Documentos --}}
        <div class="border-t border-gray-200 pt-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Documentos Requeridos</h2>
            
            <div class="space-y-6">
                {{-- Tipo de Identificación --}}
                <div>
                    <label for="identificationType" class="block text-base font-medium text-gray-700 mb-2">
                        Tipo de Identificación <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.blur="identificationType" id="identificationType"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('identificationType') border-red-500 @enderror">
                        <option value="">Selecciona...</option>
                        <option value="INE">INE</option>
                        <option value="Pasaporte">Pasaporte</option>
                        <option value="Cedula Profesional">Cédula Profesional</option>
                        <option value="Otro">Otro</option>
                    </select>
                    @error('identificationType')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Archivo de Identificación --}}
                <div>
                    <label for="identificationFile" class="block text-base font-medium text-gray-700 mb-2">
                        Documento de Identificación <span class="text-red-500">*</span>
                        <span class="text-gray-500 text-sm">(PDF, JPG, PNG - Max 2MB)</span>
                    </label>
                    
                    <div x-data="{ fileName: null }"
                         x-on:file-validated.window="
                             if ($event.detail.field === 'identificationFile') {
                                 fileName = $event.detail.fileName;
                             }
                         "
                         x-on:file-invalid.window="
                             if ($event.detail.field === 'identificationFile') {
                                 fileName = null;
                             }
                         "
                         x-on:file-cleared.window="
                             if ($event.detail.field === 'identificationFile') {
                                 fileName = null;
                             }
                         ">
                        <template x-if="fileName">
                            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-md flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-green-700 font-medium text-sm" x-text="'Archivo seleccionado: ' + fileName"></span>
                                </div>
                                <button type="button" wire:click="clearIdentificationFile" 
                                        class="text-red-600 hover:text-red-800 font-medium text-sm">
                                    Eliminar
                                </button>
                            </div>
                        </template>
                    </div>
                    
                    <input type="file" 
                           wire:model="identificationFile" 
                           id="identificationFile"
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer @error('identificationFile') border-red-500 @enderror">
                    
                    @error('identificationFile')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Licencia Inmobiliaria --}}
                @if($this->needsLicense())
                <div>
                    <label for="licenseFile" class="block text-base font-medium text-gray-700 mb-2">
                        Licencia Inmobiliaria <span class="text-red-500">*</span>
                        <span class="text-gray-500 text-sm">(PDF, JPG, PNG - Max 2MB)</span>
                    </label>
                    
                    <div x-data="{ fileName: null }"
                         x-on:file-validated.window="
                             if ($event.detail.field === 'licenseFile') {
                                 fileName = $event.detail.fileName;
                             }
                         "
                         x-on:file-invalid.window="
                             if ($event.detail.field === 'licenseFile') {
                                 fileName = null;
                             }
                         "
                         x-on:file-cleared.window="
                             if ($event.detail.field === 'licenseFile') {
                                 fileName = null;
                             }
                         ">
                        <template x-if="fileName">
                            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-md flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-green-700 font-medium text-sm" x-text="'Archivo seleccionado: ' + fileName"></span>
                                </div>
                                <button type="button" wire:click="clearLicenseFile" 
                                        class="text-red-600 hover:text-red-800 font-medium text-sm">
                                    Eliminar
                                </button>
                            </div>
                        </template>
                    </div>
                    
                    <input type="file" 
                           wire:model="licenseFile" 
                           id="licenseFile"
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer @error('licenseFile') border-red-500 @enderror">
                    
                    @error('licenseFile')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                @endif
            </div>
        </div>

        {{-- Botones de acción --}}
        <div class="flex justify-end space-x-4 border-t border-gray-200 pt-8">
            <a href="{{ route('dashboard') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 font-medium hover:bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75">
                Cancelar
            </a>
            
            <button type="submit" 
                    wire:loading.attr="disabled"
                    wire:target="submit"
                    class="px-8 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">
                <span wire:loading.remove wire:target="submit">Enviar Solicitud</span>
                <span wire:loading wire:target="submit" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Enviando...
                </span>
            </button>
        </div>
    </form>
</div>