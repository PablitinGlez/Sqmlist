<?php

namespace App\Filament\Advertiser\Resources;

use App\Filament\Advertiser\Resources\PropertyResource\Pages;

use App\Models\Category;

use App\Models\Property;
use App\Models\PropertyType;
use Filament\Forms;

use Filament\Forms\Components\Radio;

use Filament\Forms\Components\Select;

use Filament\Forms\Components\Wizard;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

use Illuminate\Support\Facades\Config;


class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Mis Propiedades';
    protected static ?string $pluralModelLabel = 'Mis Propiedades';
    protected static ?string $modelLabel = 'Propiedad';
    protected static ?int $navigationSort = 1;

 
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->with([
                'address',
                'propertyType.features.featureSection',
                'featureValues.feature',
                'images' => function ($query) {
                    $query->orderBy('order', 'asc'); 
                }
            ])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * 
     * 
     * 
     *
     * @param PropertyType|null
     * @param Model|null 
     * @param bool 
     * @return array
     */
    private static function getFeatureSectionsTabs(?PropertyType $propertyType, ?Model $record, bool $disabled): array
    {
        if (!$propertyType) {
            return [
                Forms\Components\Tabs\Tab::make('Especificaciones')
                    ->schema([
                        Forms\Components\Placeholder::make('no_specs_found')
                            ->content(new HtmlString('<p class="text-md text-gray-600 dark:text-gray-400">No se encontraron especificaciones para este tipo de propiedad.</p>'))
                    ])
            ];
        }

        $sections = new Collection();
        foreach ($propertyType->features as $feature) {
            if ($feature->featureSection) {
                $sections->put($feature->featureSection->slug, $feature->featureSection);
            }
        }
        $sections = $sections->sortBy('order');

        $tabsForFeatures = [];

        foreach ($sections as $section) {
            $sectionFeatures = $propertyType->features->filter(function ($feature) use ($section) {
                return $feature->feature_section_id === $section->id;
            })->sortBy('pivot.order_for_type');

            if ($sectionFeatures->isNotEmpty()) {
                $sectionFields = [];

                foreach ($sectionFeatures as $feature) {
                    $fieldName = $feature->slug;
                    $label = $feature->name;
                    $placeholder = $feature->name;

                    if (($feature->input_type === 'number' || $feature->input_type === 'stepper') && $feature->unit) {
                        $placeholder = $feature->name . ' (' . $feature->unit . ')';
                    } elseif ($feature->input_type === 'select') {
                        $placeholder = 'Selecciona una opción';
                    }

                    $field = null;

                    switch ($feature->input_type) {
                        case 'number':
                        case 'stepper':
                            $field = Forms\Components\TextInput::make("feature_values.{$fieldName}")
                                ->label($label)
                                ->placeholder($placeholder)
                                ->numeric()
                                ->disabled($disabled)
                                ->dehydrated(true) // SIEMPRE deshidratar para guardar
                                ->afterStateHydrated(function ($component, $state) use ($record, $feature) {
                                    if ($record && $record->exists && $record->relationLoaded('featureValues')) {
                                        $featureValue = $record->featureValues->firstWhere('feature_id', $feature->id);
                                        if ($featureValue) {
                                            $component->state($featureValue->value);
                                        }
                                    }
                                });

                            if ($feature->data_type === 'float') {
                                $field->step(0.01);
                            } else {
                                $field->integer();
                            }
                            break;

                        case 'select':
                            $options = json_decode($feature->options, true) ?? [];
                            $field = Forms\Components\Radio::make("feature_values.{$fieldName}")
                                ->label($label)
                                ->options($options)
                                ->columns(2)
                                ->disabled($disabled)
                                ->dehydrated(true) // SIEMPRE deshidratar para guardar
                                ->afterStateHydrated(function ($component, $state) use ($record, $feature) {
                                    if ($record && $record->exists && $record->relationLoaded('featureValues')) {
                                        $featureValue = $record->featureValues->firstWhere('feature_id', $feature->id);
                                        if ($featureValue) {
                                            $component->state($featureValue->value);
                                        }
                                    }
                                });
                            break;

                        case 'checkbox':
                            $field = Forms\Components\Checkbox::make("feature_values.{$fieldName}")
                                ->label($label)
                                ->default(false)
                                ->disabled($disabled)
                                ->dehydrated(true) // SIEMPRE deshidratar para guardar
                                ->afterStateHydrated(function ($component, $state) use ($record, $feature) {
                                    if ($record && $record->exists && $record->relationLoaded('featureValues')) {
                                        $featureValue = $record->featureValues->firstWhere('feature_id', $feature->id);
                                        if ($featureValue) {
                                            $component->state((bool) $featureValue->value);
                                        }
                                    }
                                });
                            break;

                        case 'text':
                        default:
                            $field = Forms\Components\TextInput::make("feature_values.{$fieldName}")
                                ->label($label)
                                ->placeholder($placeholder)
                                ->disabled($disabled)
                                ->dehydrated(true) // SIEMPRE deshidratar para guardar
                                ->afterStateHydrated(function ($component, $state) use ($record, $feature) {
                                    if ($record && $record->exists && $record->relationLoaded('featureValues')) {
                                        $featureValue = $record->featureValues->firstWhere('feature_id', $feature->id);
                                        if ($featureValue) {
                                            $component->state($featureValue->value);
                                        }
                                    }
                                });
                            break;
                    }

                    if ($feature->pivot->is_required_for_type) {
                        $field->required();
                    }

                    $sectionFields[] = $field;
                }

                $tabsForFeatures[] = Forms\Components\Tabs\Tab::make($section->name)
                    ->icon($section->icon ?? null)
                    ->schema($sectionFields)
                    ->columns(2);
            }
        }

        return $tabsForFeatures;
    }

    /**
     * Define el esquema del formulario para la PROPIEDAD EN MODO EDICIÓN/REVISIÓN.
     * Este método es ahora reutilizable y puede controlar si los campos están deshabilitados.
     *
     * @param Form $form El objeto Form de Filament.
     * @param bool $disabled Si es true, todos los campos se deshabilitarán.
     * @return array
     */
    public static function getEditFormSchema(Form $form, bool $disabled = false): array
    {
        // Obtener el registro actual
        $record = $form->getRecord();
        $propertyTypeId = $record->property_type_id ?? null;

        $propertyType = null;
        if ($propertyTypeId) {
            $propertyType = PropertyType::with([
                'features' => function ($query) {
                    $query->orderBy('pivot_order_for_type');
                },
                'features.featureSection' => function ($query) {
                    $query->orderBy('order');
                }
            ])->find($propertyTypeId);
        }

        return [
            Forms\Components\Section::make('Información General de la Propiedad')
                ->schema([
                    Forms\Components\TextInput::make('id')
                        ->label('ID de Propiedad')
                        ->disabled() // Siempre deshabilitado
                        ->columnSpan(1),
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->label('Anunciante')
                        ->disabled() // Siempre deshabilitado
                        ->columnSpan(1),
                    Forms\Components\Select::make('property_type_id')
                        ->relationship('propertyType', 'name')
                        ->label('Tipo de Propiedad')
                        ->disabled() // El tipo de propiedad NO se puede cambiar en edición
                        ->columnSpan(1),
                    // Campo Title - Solo lectura en edición
                    Forms\Components\TextInput::make('title')
                        ->label('Título de la Propiedad')
                        ->disabled() // Deshabilitado en edición
                        ->dehydrated(false) // No deshidratar (no se guarda si está deshabilitado)
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('price')
                        ->label('Precio')
                        ->prefix('$')
                        ->suffix('MXN')
                        ->numeric()
                        ->rules(['numeric', 'min:0', 'max:999999999.99'])
                        ->required()
                        ->disabled($disabled)
                        ->columnSpan(1),
                    Forms\Components\Select::make('operation_type')
                        ->label('Tipo de Operación')
                        ->options([
                            'sale' => 'Venta',
                            'rent' => 'Renta',
                            'both' => 'Venta y Renta',
                        ])
                        ->required()
                        ->disabled($disabled)
                        ->columnSpan(1),
                    Forms\Components\Textarea::make('description') // Directamente en la sección general
                        ->label('Descripción')
                        ->rows(5)
                        ->maxLength(1500)
                        ->required()
                        ->disabled($disabled)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('contact_whatsapp_number')
                        ->label('WhatsApp Contacto')
                        ->tel()
                        ->prefix('+52')
                        ->maxLength(10)
                        ->minLength(10)
                        ->required()
                        ->disabled($disabled)
                        ->afterStateHydrated(function ($component, $state, $record) {
                            if (empty($state) && $record === null) { // Solo precargar si es un nuevo registro
                                $user = auth()->user();
                                $value = $user->profileDetails->whatsapp_number ?? null;
                                $component->state($value);
                            }
                        })
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set) {
                            $cleanNumber = preg_replace('/[^0-9]/', '', $state);
                            if (strlen($cleanNumber) > 10) {
                                $cleanNumber = substr($cleanNumber, 0, 10);
                            }
                            $set('contact_whatsapp_number', $cleanNumber);
                        }),
                    Forms\Components\TextInput::make('contact_phone_number')
                        ->label('Teléfono Contacto')
                        ->tel()
                        ->prefix('+52')
                        ->maxLength(10)
                        ->minLength(10)
                        ->required()
                        ->disabled($disabled)
                        ->afterStateHydrated(function ($component, $state, $record) {
                            if (empty($state) && $record === null) { // Solo precargar si es un nuevo registro
                                $user = auth()->user();
                                $value = $user->profileDetails->phone_number ?? null;
                                $component->state($value);
                            }
                        })
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set) {
                            $cleanNumber = preg_replace('/[^0-9]/', '', $state);
                            if (strlen($cleanNumber) > 10) {
                                $cleanNumber = substr($cleanNumber, 0, 10);
                            }
                            $set('contact_phone_number', $cleanNumber);
                        }),
                    Forms\Components\TextInput::make('contact_email')
                        ->label('Email Contacto')
                        ->email()
                        ->maxLength(255)
                        ->required()
                        ->disabled($disabled)
                        ->afterStateHydrated(function ($component, $state, $record) {
                            if (empty($state) && $record === null) { // Solo precargar si es un nuevo registro
                                $user = auth()->user();
                                $value = $user->profileDetails->contact_email ?? $user->email ?? null;
                                $component->state($value);
                            }
                        }),
                ])->columns(2),

            Forms\Components\Section::make('Dirección de la Propiedad')
                ->schema([
                    Forms\Components\TextInput::make('full_address') // Campo para la dirección concatenada
                        ->label('Dirección Completa')
                        ->disabled() // La dirección NO es editable
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->full_address ?? 'Sin dirección')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('street')
                        ->label('Calle')
                        ->disabled() // La dirección NO es editable
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->street ?? 'Sin calle'),
                    Forms\Components\TextInput::make('outdoor_number')
                        ->label('Número Exterior')
                        ->disabled() // La dirección NO es editable
                        ->dehydrated(false)
                        ->formatStateUsing(function ($record) {
                            $address = $record->address;
                            if (!$address) return 'S/N';
                            if ($address->no_external_number === true || $address->no_external_number === 1 || empty($address->outdoor_number) || $address->outdoor_number === null) {
                                return 'S/N';
                            }
                            return $address->outdoor_number;
                        }),
                    Forms\Components\TextInput::make('interior_number')
                        ->label('Número Interior')
                        ->disabled() // La dirección NO es editable
                        ->dehydrated(false)
                        ->formatStateUsing(function ($record) {
                            $address = $record->address;
                            if (!$address) return 'S/N';
                            if ($address->no_interior_number === true || $address->no_interior_number === 1 || empty($address->interior_number) || $address->interior_number === null) {
                                return 'S/N';
                            }
                            return $address->interior_number;
                        }),
                    Forms\Components\TextInput::make('neighborhood_name')
                        ->label('Colonia')
                        ->disabled() // La dirección NO es editable
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->neighborhood_name ?? 'Sin colonia'),
                    Forms\Components\TextInput::make('municipality_name')
                        ->label('Municipio')
                        ->disabled() // La dirección NO es editable
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->municipality_name ?? 'Sin municipio'),
                    Forms\Components\TextInput::make('state_name')
                        ->label('Estado')
                        ->disabled() // La dirección NO es editable
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->state_name ?? 'Sin estado'),
                    Forms\Components\TextInput::make('postal_code')
                        ->label('Código Postal')
                        ->disabled() // La dirección NO es editable
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->postal_code ?? 'Sin CP'),

                    Forms\Components\Placeholder::make('google_map_display')
                        ->label('Ubicación en el Mapa')
                        ->content(function ($record) {
                            $lat = $record->address->latitude ?? 19.4326;
                            $lng = $record->address->longitude ?? -99.1332;
                            $apiKey = Config::get('services.google_maps.api_key');

                            if (!$apiKey) {
                                return new HtmlString('<p class="text-red-500">La clave de la API de Google Maps no está configurada.</p>');
                            }

                            $mapId = 'map-' . uniqid(); // Generar un ID único para cada mapa

                            return new HtmlString("
                                <div wire:ignore>
                                    <div id='{$mapId}' style='width: 100%; height: 300px; border-radius: 8px; overflow: hidden; background-color: #e0e0e0;'></div>
                                </div>
                                <script>
                                    (function() {
                                        const mapId = '{$mapId}';
                                        const lat = {$lat};
                                        const lng = {$lng};
                                        
                                        // Crear namespace global para evitar conflictos
                                        if (!window.propertyMaps) {
                                            window.propertyMaps = {};
                                        }
                                        
                                        function initMapInstance() {
                                            const mapElement = document.getElementById(mapId);
                                            if (!mapElement) {
                                                console.error('Map element not found:', mapId);
                                                return;
                                            }

                                            // Si ya existe un mapa para este elemento, destruirlo
                                            if (window.propertyMaps[mapId]) {
                                                // No es necesario destruir explícitamente en Filament si el componente se re-renderiza
                                                // pero podemos limpiar la referencia para evitar duplicados lógicos
                                                window.propertyMaps[mapId] = null;
                                            }
                                            
                                            // Limpiar contenido
                                            mapElement.innerHTML = '';
                                            
                                            if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) {
                                                mapElement.innerHTML = '<p class=\"text-center text-gray-500 p-4\">No hay coordenadas válidas para mostrar el mapa.</p>';
                                                return;
                                            }

                                            const mapOptions = {
                                                center: { lat: lat, lng: lng },
                                                zoom: 16,
                                                mapTypeControl: false,
                                                streetViewControl: false,
                                                fullscreenControl: false,
                                                zoomControl: true,
                                                gestureHandling: 'cooperative',
                                                disableDefaultUI: false,
                                                clickableIcons: false,
                                                backgroundColor: '#e5e3df'
                                            };

                                            const map = new google.maps.Map(mapElement, mapOptions);
                                            
                                            // Guardar referencia del mapa
                                            window.propertyMaps[mapId] = map;

                                            new google.maps.Marker({
                                                position: { lat: lat, lng: lng },
                                                map: map,
                                                title: 'Ubicación de la propiedad',
                                                icon: {
                                                    url: 'data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"%231E90FF\" class=\"icon icon-tabler icons-tabler-filled icon-tabler-home\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12.707 2.293l9 9c.63 .63 .184 1.707 -.707 1.707h-1v6a3 3 0 0 1 -3 3h-1v-7a3 3 0 0 0 -2.824 -2.995l-.176 -.005h-2a3 3 0 0 0 -3 3v7h-1a3 3 0 0 1 -3 -3v-6h-1c-.89 0 -1.337 -1.077 -.707 -1.707l9 -9a1 1 0 0 1 1.414 0m.293 11.707a1 1 0 0 1 1 1v7h-4v-7a1 1 0 0 1 .883 -.993l.117 -.007z\" /></svg>',
                                                    scaledSize: new google.maps.Size(24, 24)
                                                },
                                                draggable: false
                                            });

                                            google.maps.event.addListenerOnce(map, 'idle', function() {
                                                google.maps.event.trigger(map, 'resize');
                                                map.setCenter(mapOptions.center);
                                            });
                                        }
                                        
                                        function loadGoogleMaps() {
                                            if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                                                initMapInstance();
                                            } else {
                                                // Verificar si el script ya se está cargando
                                                if (!window.googleMapsLoading) {
                                                    window.googleMapsLoading = true;
                                                    
                                                    const script = document.createElement('script');
                                                    script.src = 'https://maps.googleapis.com/maps/api/js?key={$apiKey}&libraries=places';
                                                    script.async = true;
                                                    script.defer = true;
                                                    
                                                    script.onload = function() {
                                                        window.googleMapsLoading = false;
                                                        initMapInstance();
                                                    };
                                                    
                                                    script.onerror = function() {
                                                        window.googleMapsLoading = false;
                                                        console.error('Error loading Google Maps');
                                                    };
                                                    
                                                    document.head.appendChild(script);
                                                } else {
                                                    // Si ya se está cargando, esperar y reintentar
                                                    setTimeout(function() {
                                                        loadGoogleMaps();
                                                    }, 500);
                                                }
                                            }
                                        }
                                        
                                        // Inicializar con un pequeño delay para asegurar DOM ready
                                        setTimeout(function() {
                                            loadGoogleMaps();
                                        }, 100);
                                        
                                        // Observer para reinicializar cuando el elemento sea visible
                                        if (typeof IntersectionObserver !== 'undefined') {
                                            const observer = new IntersectionObserver(function(entries) {
                                                entries.forEach(function(entry) {
                                                    if (entry.isIntersecting && !window.propertyMaps[mapId]) {
                                                        setTimeout(function() {
                                                            loadGoogleMaps();
                                                        }, 200);
                                                    }
                                                });
                                            }, {
                                                threshold: 0.1
                                            });
                                            
                                            setTimeout(function() {
                                                const mapElement = document.getElementById(mapId);
                                                if (mapElement) {
                                                    observer.observe(mapElement);
                                                }
                                            }, 100);
                                        }
                                    })();
                                </script>
                            ");
                        })
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Especificaciones de la Propiedad')
                ->schema([
                    Forms\Components\Tabs::make('Especificaciones')
                        // ✅ Llamada al método helper que solo devuelve las pestañas de FEATURES
                        ->tabs(static::getFeatureSectionsTabs($propertyType, $record, $disabled))
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),

            Forms\Components\Section::make('Imágenes de la Propiedad') // Directamente en la sección general
                ->schema([
                    Forms\Components\FileUpload::make('images')
                        ->label('Imágenes')
                        ->multiple()
                        ->image()
                        ->reorderable()
                        ->panelLayout('grid')
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                            '4:3',
                            '1:1',
                        ])
                        ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                        ->maxSize(10240)
                        ->maxFiles(20)
                        ->directory('properties')
                        ->visibility('public')
                        ->required()
                        ->helperText('Sube hasta 20 imágenes. Máximo 10MB por archivo. Formatos: JPG, PNG. Puedes reordenar arrastrando las imágenes.')
                        ->columnSpanFull()
                        ->disabled($disabled)
                        // CONFIGURACIÓN PARA EDICIÓN - Hidratar desde la relación images
                        ->afterStateHydrated(function ($component, $state, $record) {
                            if ($record && $record->exists && $record->relationLoaded('images')) {
                                // Obtener las rutas de las imágenes existentes ordenadas por 'order'
                                $imagePaths = $record->images()
                                    ->orderBy('order')
                                    ->pluck('path')
                                    ->toArray();

                                $component->state($imagePaths);
                            }
                        })
                        // CONFIGURACIÓN PARA DESHIDRATACIÓN - Siempre permitir que se envíe
                        ->dehydrated(true)
                ])
                ->columnSpanFull(),
        ];
    }

    public static function form(Form $form): Form
    {
        // Determinar si estamos en la página de edición o creación
        $isEditing = $form->getOperation() === 'edit';

        if ($isEditing) {
            // Si estamos editando, usamos el esquema de edición/revisión con campos habilitados
            return $form->schema(static::getEditFormSchema($form, false)); // false = campos habilitados
        } else {
            // Si estamos creando, usamos el Wizard original
            return $form
                ->schema([
                    Wizard::make([
                        Forms\Components\Wizard\Step::make('Ubicación')
                            ->schema([
                                Forms\Components\Livewire::make('address-autocomplete')
                                    ->columnSpanFull()
                                    ->extraAttributes([
                                        'class' => 'border-none',
                                        'wire:ignore.self' => true,
                                    ])
                                    ->statePath('selectedAddressData')
                                    ->live(),
                                Forms\Components\Hidden::make('address_data')
                                    ->reactive(),
                            ])
                            ->columns(1)
                            ->afterValidation(function (Forms\Get $get, array $state) {
                                // Lógica de validación para el paso de ubicación si es necesaria
                            }),

                        Forms\Components\Wizard\Step::make('Generales')
                            ->schema([
                                Forms\Components\Placeholder::make('')
                                    ->content(new HtmlString('<h2 class="text-xl font-semibold text-gray-800 mb-4">¿Cuéntanos qué quieres publicar?</h2>')),
                                // Nuevo campo para el Título de la Propiedad
                                Forms\Components\TextInput::make('title')
                                    ->label('Título de la Propiedad')
                                    ->placeholder('Ej. Casa bonita de dos pisos con jardín')
                                    ->helperText('Ingresa un título corto y descriptivo para que las personas encuentren más fácil tu propiedad.')
                                    ->required()
                                    ->maxLength(40)
                                    ->columnSpanFull(), // Ocupa todo el ancho
                                Radio::make('operation_type')
                                    ->label('Tipo de operación')
                                    ->options([
                                        'sale' => 'Venta',
                                        'rent' => 'Renta',
                                        'both' => 'Venta y Renta',
                                    ])
                                    ->required()
                                    ->inline()
                                    ->live(),
                                Select::make('property_type_id')
                                    ->label('Tipo de propiedad')
                                    ->options(function () {
                                        $groupedOptions = [];
                                        $categories = Category::with('propertyTypes')->orderBy('name')->get();
                                        foreach ($categories as $category) {
                                            $propertyTypes = $category->propertyTypes->pluck('name', 'id')->toArray();
                                            if (!empty($propertyTypes)) {
                                                $groupedOptions[$category->name] = $propertyTypes;
                                            }
                                        }
                                        return $groupedOptions;
                                    })
                                    ->required()
                                    ->searchable()
                                    ->placeholder('Selecciona un tipo de propiedad')
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Set $set) {
                                        $set('feature_values', []);
                                    }),
                            ])
                            ->columns(1)
                            ->afterValidation(function (Forms\Get $get, array $state) {
                                // Lógica de validación para el paso de generales si es necesaria
                            }),

                        Forms\Components\Wizard\Step::make('Especificaciones')
                            ->schema(function (Forms\Get $get): array {
                                $propertyTypeId = $get('property_type_id');

                                if (!$propertyTypeId) {
                                    return [
                                        Forms\Components\Placeholder::make('no_property_type_selected')
                                            ->content(new HtmlString('<p class="text-md text-gray-600 dark:text-gray-400">Por favor, selecciona un <strong>Tipo de Propiedad</strong> en el paso anterior para ver las especificaciones.</p>'))
                                    ];
                                }

                                $propertyType = PropertyType::with([
                                    'features' => function ($query) {
                                        $query->orderBy('pivot_order_for_type');
                                    },
                                    'features.featureSection' => function ($query) {
                                        $query->orderBy('order');
                                    }
                                ])->find($propertyTypeId);

                                if (!$propertyType) {
                                    return [
                                        Forms\Components\Placeholder::make('property_type_not_found')
                                            ->content(new HtmlString('<p class="text-md text-red-600 dark:text-red-400">Tipo de Propiedad no encontrado.</p>'))
                                    ];
                                }

                                // En el wizard de creación, el record es null, así que pasamos null
                                $tabs = static::getFeatureSectionsTabs($propertyType, null, false); // Solo las pestañas de features

                                // Añadir las pestañas de Multimedia y Descripción al final
                                $tabs[] = Forms\Components\Tabs\Tab::make('Multimedia')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        Forms\Components\Section::make('Fotos de la Propiedad')
                                            ->description('Usa fotos horizontales que muestren completamente cada área. Si hay muebles, ordénalos para que el espacio luzca presentable.')
                                            ->schema([
                                                Forms\Components\FileUpload::make('images')
                                                    ->label('Imágenes')
                                                    ->multiple()
                                                    ->image()
                                                    ->reorderable()
                                                    ->panelLayout('grid')
                                                    ->imageEditor()
                                                    ->imageEditorAspectRatios([
                                                        '16:9',
                                                        '4:3',
                                                        '1:1',
                                                    ])
                                                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                                                    ->maxSize(10240)
                                                    ->maxFiles(20)
                                                    ->directory('properties')
                                                    ->visibility('public')
                                                    ->required()
                                                    ->helperText('Sube hasta 20 imágenes. Máximo 10MB por archivo. Formatos: JPG, PNG. Puedes reordenar arrastrando las imágenes.')
                                                    ->columnSpanFull()
                                                    ->extraAttributes([
                                                        'class' => 'custom-file-upload-grid',
                                                        'style' => '--cols-default: 4; --cols-lg: 5; --cols-xl: 6;'
                                                    ]),
                                            ])
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1); // Asegura que el contenido de la pestaña se organiza en 1 columna

                                $tabs[] = Forms\Components\Tabs\Tab::make('Descripción')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        Forms\Components\Section::make('Describe tu propiedad')
                                            ->description('Agrega datos relevantes como: Acabados de la propiedad, Servicios adicionales, Reglamentos, Lugares cercanos como escuelas, hospitales, tiendas departamentales, Entretenimiento, etc.')
                                            ->schema([
                                                Forms\Components\Textarea::make('description') // Cambiado a Textarea para descripciones largas
                                                    ->label('Descripción')
                                                    ->placeholder('Describe tu propiedad detalladamente...')
                                                    ->columnSpanFull()
                                                    ->maxLength(1500),
                                            ])
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1); // Asegura que el contenido de la pestaña se organiza en 1 columna

                                return [
                                    Forms\Components\Tabs::make('Especificaciones de la Propiedad')
                                        ->tabs($tabs)
                                        ->columnSpanFull(),
                                ];
                            })
                            ->columns(1)
                            ->afterValidation(function (Forms\Get $get, array $state) {
                                // Lógica de validación para el paso de especificaciones si es necesaria
                            }),

                        // ELIMINADOS: Los pasos individuales de Multimedia y Descripción.
                        // Ahora están dentro de las pestañas de 'Especificaciones' en el Wizard.

                        Forms\Components\Wizard\Step::make('Precio')
                            ->schema([
                                Forms\Components\Section::make('Precio de Venta')
                                    ->description('Ingresa el precio de tu propiedad. Este será el precio visible para los compradores.')
                                    ->schema([
                                        Forms\Components\TextInput::make('price')
                                            ->label('Precio')
                                            ->numeric()
                                            ->prefix('$')
                                            ->suffix('MXN')
                                            ->required()
                                            ->rules(['numeric', 'min:0', 'max:999999999.99']),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull(),
                            ])
                            ->columns(1),

                        Forms\Components\Wizard\Step::make('Contacto')
                            ->schema([
                                Forms\Components\Section::make('Datos de contacto')
                                    ->description('Ingresa tus datos personales para que se puedan contactar por el medio más favorable de los interesados en tu propiedad.')
                                    ->schema([
                                        Forms\Components\TextInput::make('contact_whatsapp_number')
                                            ->label('Número de WhatsApp')
                                            ->placeholder('Número')
                                            ->tel()
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (empty($state)) {
                                                    $user = auth()->user();
                                                    $value = null;
                                                    if ($user && $user->profileDetails) {
                                                        $value = $user->profileDetails->whatsapp_number;
                                                    }
                                                    $component->state($value);
                                                }
                                            })
                                            ->prefixIcon('heroicon-o-device-phone-mobile')
                                            ->prefix('+52')
                                            ->maxLength(10)
                                            ->required()
                                            ->minLength(10)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, $set) {
                                                $cleanNumber = preg_replace('/[^0-9]/', '', $state);
                                                if (strlen($cleanNumber) > 10) {
                                                    $cleanNumber = substr($cleanNumber, 0, 10);
                                                }
                                                $set('contact_whatsapp_number', $cleanNumber);
                                            }),
                                        Forms\Components\TextInput::make('contact_phone_number')
                                            ->label('Teléfono')
                                            ->placeholder('Número')
                                            ->tel()
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (empty($state)) {
                                                    $user = auth()->user();
                                                    $value = null;
                                                    if ($user && $user->profileDetails) {
                                                        $value = $user->profileDetails->phone_number;
                                                    }
                                                    $component->state($value);
                                                }
                                            })
                                            ->prefixIcon('heroicon-o-phone')
                                            ->prefix('+52')
                                            ->maxLength(10)
                                            ->minLength(10)
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, $set) {
                                                $cleanNumber = preg_replace('/[^0-9]/', '', $state);
                                                if (strlen($cleanNumber) > 10) {
                                                    $cleanNumber = substr($cleanNumber, 0, 10);
                                                }
                                                $set('contact_phone_number', $cleanNumber);
                                            }),
                                        Forms\Components\TextInput::make('contact_email')
                                            ->label('Correo Electrónico')
                                            ->placeholder('e-mail')
                                            ->email()
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (empty($state)) {
                                                    $user = auth()->user();
                                                    $value = null;
                                                    if ($user && $user->profileDetails) {
                                                        $value = $user->profileDetails->contact_email;
                                                    } else if ($user) {
                                                        $value = $user->email;
                                                    }
                                                    $component->state($value);
                                                }
                                            })
                                            ->prefixIcon('heroicon-o-envelope')
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Forms\Components\Placeholder::make('contact_info')
                                            ->content(new HtmlString('
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                    <p><strong>Nota:</strong> Estos datos se han prellenado con la información de tu perfil. Puedes modificarlos si deseas usar diferentes datos de contacto para esta propiedad específica.</p>
                                                    <p class="mt-2 text-primary-600 dark:text-primary-400">Una vez creada, su propiedad será evaluada por un administrador para poder ser publicada. Si cumple con los requisitos, su propiedad se publicará automáticamente.</p>
                                                </div>
                                            '))
                                            ->columnSpanFull(),

                                        Forms\Components\Actions::make([
                                            Forms\Components\Actions\Action::make('enviar_a_revision')
                                                ->label('Enviar a Revisión')
                                                ->icon('heroicon-o-arrow-up-on-square')
                                                ->color('primary')
                                                ->size('lg')
                                                ->extraAttributes([
                                                    'class' => 'w-full justify-center mt-6'
                                                ])
                                                ->action(function (Forms\Components\Actions\Action $action) {
                                                    $action->getLivewire()->create();
                                                })
                                        ])
                                            ->alignCenter()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(1),
                    ])
                        ->columnSpanFull(),
                ]);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featuredImage.path')
                    ->label('Imagen')
                    ->defaultImageUrl(url('/images/placeholder.png'))
                    ->size(60),

              

                Tables\Columns\TextColumn::make('propertyType.name')
                    ->label('Tipo de Propiedad')
                    ->sortable(),

                Tables\Columns\TextColumn::make('address.full_address')
                    ->label('Ubicación')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('address', function ($query) use ($search) {
                            $query->where('street', 'like', "%{$search}%")
                                ->orWhere('outdoor_number', 'like', "%{$search}%")
                                ->orWhere('interior_number', 'like', "%{$search}%")
                                ->orWhere('neighborhood_name', 'like', "%{$search}%")
                                ->orWhere('postal_code', 'like', "%{$search}%")
                                ->orWhere('municipality_name', 'like', "%{$search}%")
                                ->orWhere('state_name', 'like', "%{$search}%");
                        });
                    })
                    ->wrap()
                    ->size('sm'),

                Tables\Columns\TextColumn::make('operation_type')
                    ->label('Operación')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'sale' => 'Venta',
                        'rent' => 'Renta',
                        'both' => 'Venta y Renta',
                        default => ucfirst($state),
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'sale' => 'success',
                        'rent' => 'info',
                        'both' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('MXN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'published' => 'Publicada',
                        'pending_review' => 'Pendiente de Revisión',
                        'draft' => 'Borrador',
                        'rejected' => 'Rechazada',
                        'inactive' => 'Inactiva',
                        'sold' => 'Vendida',
                        'rented' => 'Rentada',
                        default => 'gray',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'published' => 'success',
                        'pending_review' => 'warning',
                        'draft' => 'info',
                        'rejected' => 'danger',
                        'inactive' => 'gray',
                        'sold' => 'primary',
                        'rented' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('operation_type')
                    ->label('Tipo de Operación')
                    ->options([
                        'sale' => 'Venta',
                        'rent' => 'Renta',
                        'both' => 'Venta y Renta',
                    ])
                    ->placeholder('Todos'),

                Tables\Filters\SelectFilter::make('property_type_id')
                    ->label('Tipo de Propiedad')
                    ->options(PropertyType::all()->pluck('name', 'id'))
                    ->placeholder('Todos los tipos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn(Property $record): bool => $record->status === Property::STATUS_PUBLISHED),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('markAsInactive')
                        ->label('Marcar como Inactiva')
                        ->icon('heroicon-o-minus-circle')
                        ->color('gray')
                        ->visible(fn(Property $record): bool => $record->status === Property::STATUS_PUBLISHED)
                        ->action(function (Property $record) {
                            $record->update(['status' => Property::STATUS_INACTIVE]);
                            \Filament\Notifications\Notification::make()
                                ->title('Propiedad marcada como inactiva')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('markAsSold')
                        ->label('Marcar como Vendida')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('info')
                        ->visible(fn(Property $record): bool => $record->operation_type === 'sale' && $record->status === Property::STATUS_PUBLISHED)
                        ->action(function (Property $record) {
                            $record->update(['status' => Property::STATUS_SOLD]);
                            \Filament\Notifications\Notification::make()
                                ->title('Propiedad marcada como vendida')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('markAsRented')
                        ->label('Marcar como Rentada')
                        ->icon('heroicon-o-key')
                        ->color('info')
                        ->visible(fn(Property $record): bool => $record->operation_type === 'rent' && $record->status === Property::STATUS_PUBLISHED)
                        ->action(function (Property $record) {
                            $record->update(['status' => Property::STATUS_RENTED]);
                            \Filament\Notifications\Notification::make()
                                ->title('Propiedad marcada como rentada')
                                ->success()
                                ->send();
                        }),
                ])
                    ->label('Acciones')
                    ->visible(fn(Property $record): bool => $record->status === Property::STATUS_PUBLISHED),
            ])
            ->bulkActions([
                //
            ])
            ->recordUrl(fn(Model $record): ?string => $record->status === Property::STATUS_PUBLISHED ? static::getUrl('edit', ['record' => $record]) : null)
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('No tienes propiedades registradas')
            ->emptyStateDescription('Haz clic en "Crear Propiedad" para empezar.')
            ->emptyStateIcon('heroicon-o-building-office');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'title',
            'description',
            'address.street',
            'address.outdoor_number',
            'address.interior_number',
            'address.neighborhood_name',
            'address.postal_code',
            'address.municipality_name',
            'address.state_name'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        if (!$record instanceof Property) {
            return [];
        }

        $fullAddress = collect([
            $record->address->street,
            $record->address->outdoor_number,
            $record->address->interior_number,
            $record->address->neighborhood_name,
            $record->address->postal_code,
            $record->address->municipality_name,
            $record->address->state_name,
        ])->filter()->implode(', ');
        return [
            'Ubicación' => $fullAddress ?? 'N/A',
            'Tipo' => $record->propertyType->name ?? 'N/A',
        ];
    }


    protected function getFormActions(): array
    {
        return [
            //
        ];
    }
}
