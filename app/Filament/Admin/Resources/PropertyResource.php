<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropertyResource\Pages;
use App\Filament\Admin\Resources\PropertyResource\RelationManagers;
use App\Models\Property;
use App\Models\PropertyType;
use App\Notifications\PropertyStatusUpdated;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Propiedades (Admin)';

    protected static ?string $navigationGroup = 'Gestión de Contenido';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'address',
            'propertyType.features.featureSection',
            'featureValues.feature',
            'images'
        ]);
    }

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
                                ->dehydrated(true)
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
                                ->dehydrated(true)
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
                                ->dehydrated(true)
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
                                ->dehydrated(true)
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

    public static function getEditFormSchema(Form $form, bool $disabled = false): array
    {
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
                        ->disabled()
                        ->columnSpan(1),
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->label('Anunciante')
                        ->disabled()
                        ->columnSpan(1),
                    Forms\Components\Select::make('property_type_id')
                        ->relationship('propertyType', 'name')
                        ->label('Tipo de Propiedad')
                        ->disabled()
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
                    Forms\Components\Textarea::make('description')
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
                        ->disabled($disabled),
                    Forms\Components\TextInput::make('contact_phone_number')
                        ->label('Teléfono Contacto')
                        ->tel()
                        ->prefix('+52')
                        ->maxLength(10)
                        ->minLength(10)
                        ->required()
                        ->disabled($disabled),
                    Forms\Components\TextInput::make('contact_email')
                        ->label('Email Contacto')
                        ->email()
                        ->maxLength(255)
                        ->required()
                        ->disabled($disabled),
                ])->columns(2),

            Forms\Components\Section::make('Dirección de la Propiedad')
                ->schema([
                    Forms\Components\TextInput::make('full_address')
                        ->label('Dirección Completa')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->full_address ?? 'Sin dirección')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('street')
                        ->label('Calle')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->street ?? 'Sin calle'),
                    Forms\Components\TextInput::make('outdoor_number')
                        ->label('Número Exterior')
                        ->disabled()
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
                        ->disabled()
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
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->neighborhood_name ?? 'Sin colonia'),
                    Forms\Components\TextInput::make('municipality_name')
                        ->label('Municipio')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->municipality_name ?? 'Sin municipio'),
                    Forms\Components\TextInput::make('state_name')
                        ->label('Estado')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn($record) => $record->address?->state_name ?? 'Sin estado'),
                    Forms\Components\TextInput::make('postal_code')
                        ->label('Código Postal')
                        ->disabled()
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

                            $mapId = 'map-display-' . uniqid();

                            return new HtmlString("
                                <div wire:ignore>
                                    <div id='{$mapId}' style='width: 100%; height: 300px; border-radius: 8px; overflow: hidden; background-color: #e0e0e0;'></div>
                                </div>
                                <script>
                                    window.initMapDisplay = window.initMapDisplay || function() {
                                        const mapElement = document.getElementById('{$mapId}');
                                        if (!mapElement) {
                                            console.error('Map display element not found:', '{$mapId}');
                                            return;
                                        }

                                        if (mapElement.dataset.mapInitialized === 'true') {
                                            return;
                                        }
                                        mapElement.dataset.mapInitialized = 'true';

                                        const lat = parseFloat({$lat});
                                        const lng = parseFloat({$lng});

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
                                    };

                                    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                                        const script = document.createElement('script');
                                        script.src = 'https://maps.googleapis.com/maps/api/js?key=" . $apiKey . "&callback=initMapDisplay';
                                        script.async = true;
                                        script.defer = true;
                                        document.head.appendChild(script);
                                    } else {
                                        initMapDisplay();
                                    }
                                </script>
                            ");
                        })
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Especificaciones de la Propiedad')
                ->schema(function (Forms\Get $get, $record): array {
                    $propertyTypeId = $record->property_type_id ?? $get('property_type_id');

                    if (!$propertyTypeId) {
                        return [
                            Forms\Components\Placeholder::make('no_property_type_selected')
                                ->content(new HtmlString('<p class="text-md text-gray-600 dark:text-gray-400">El tipo de propiedad no está definido para mostrar las especificaciones.</p>'))
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

                    if ($record && !$record->relationLoaded('featureValues')) {
                        $record->load('featureValues.feature');
                    }

                    $tabsForFeatures = static::getFeatureSectionsTabs($propertyType, $record, true);

                    return [
                        Forms\Components\Tabs::make('Especificaciones')
                            ->tabs($tabsForFeatures)
                            ->columnSpanFull(),
                    ];
                })
                ->columnSpanFull(),

            Section::make('Imágenes de la Propiedad')
                ->schema([
                    Repeater::make('images')
                        ->relationship('images')
                        ->schema([
                            Placeholder::make('preview')
                                ->content(function ($get) {
                                    $imagePath = asset('storage/' . $get('path'));

                                    return new HtmlString('
                                    <div x-data="{ showModal: false }" class="relative">
                                        <!-- Imagen miniatura -->
                                        <div 
                                            @click.prevent.stop="showModal = true" 
                                            class="relative cursor-pointer group overflow-hidden rounded-lg"
                                        >
                                            <img 
                                                src="' . $imagePath . '" 
                                                class="w-full h-36 object-cover transition-transform duration-300 group-hover:scale-105"
                                                alt="Imagen de la propiedad"
                                            />
                                            
                                            <!-- Overlay con ícono -->
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal (se renderiza fuera del contexto del formulario) -->
                                        <template x-teleport="body">
                                            <div 
                                                x-show="showModal" 
                                                x-transition:enter="transition ease-out duration-300" 
                                                x-transition:enter-start="opacity-0" 
                                                x-transition:enter-end="opacity-100" 
                                                x-transition:leave="transition ease-in duration-200" 
                                                x-transition:leave-start="opacity-100" 
                                                x-transition:leave-end="opacity-0"
                                                @click.prevent.stop="showModal = false"
                                                @keydown.escape.window.prevent.stop="showModal = false"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
                                                style="display: none;"
                                            >
                                                <!-- Contenedor del modal -->
                                                <div 
                                                    x-show="showModal"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 transform scale-95"
                                                    x-transition:enter-end="opacity-100 transform scale-100"
                                                    x-transition:leave="transition ease-in duration-200"
                                                    x-transition:leave-start="opacity-100 transform scale-100"
                                                    x-transition:leave-end="opacity-0 transform scale-95"
                                                    class="relative max-w-4xl max-h-full bg-white rounded-lg shadow-2xl overflow-hidden"
                                                    @click.prevent.stop
                                                >
                                                    <!-- Botón cerrar -->
                                                    <button 
                                                        @click.prevent.stop="showModal = false"
                                                        type="button"
                                                        class="absolute top-4 right-4 z-10 bg-black bg-opacity-50 hover:bg-opacity-70 text-white rounded-full p-2 transition-all duration-200"
                                                    >
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- Imagen grande -->
                                                    <img 
                                                        src="' . $imagePath . '" 
                                                        class="w-full h-auto max-h-[80vh] object-contain"
                                                        alt="Imagen de la propiedad"
                                                    />
                                                    
                                                    <!-- Información adicional -->
                                                    <div class="p-4 bg-gray-50 text-center">
                                                        <p class="text-sm text-gray-600">
                                                            Presiona <kbd class="px-2 py-1 text-xs bg-gray-200 rounded">Esc</kbd> o haz clic fuera para cerrar
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                ');
                                })
                                ->hiddenLabel(),
                        ])
                        ->columns(1)
                        ->disabled()
                        ->grid(3)
                        ->defaultItems(0)
                        ->reorderable(false)
                        ->addable(false)
                        ->deletable(false)
                        ->hiddenLabel(),
                ])->columnSpanFull(),

            // Sección de Estado y Notas del Administrador
            Forms\Components\Section::make('Estado y Notas del Administrador')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Estado de la Propiedad')
                        ->options([
                            Property::STATUS_PENDING_REVIEW => 'Pendiente de Revisión',
                            Property::STATUS_PUBLISHED => 'Publicada',
                            Property::STATUS_REJECTED => 'Rechazada',
                            Property::STATUS_INACTIVE => 'Inactiva',
                            Property::STATUS_SOLD => 'Vendida',
                            Property::STATUS_RENTED => 'Rentada',
                        ])
                        ->required()
                        ->native(false)
                        ->live(),
                    Forms\Components\Textarea::make('admin_notes')
                        ->label('Notas del Administrador')
                        ->placeholder('Escribe aquí las razones del rechazo o cualquier nota relevante.')
                        ->rows(3)
                        ->columnSpanFull()
                        ->visible(fn(Forms\Get $get) => $get('status') === Property::STATUS_REJECTED),
                ])->columns(1), // <--- ¡Este cierre era el que faltaba o estaba mal ubicado!
        ]; // <--- Y este cierre para el array del esquema del formulario
    }

    public static function form(Form $form): Form
    {
        $isEditing = $form->getOperation() === 'edit';

        if ($isEditing) {
            return $form->schema(static::getEditFormSchema($form, false));
        } else {
            return $form
                ->schema([
                    Forms\Components\Wizard::make([
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
                             
                            }),

                        Forms\Components\Wizard\Step::make('Generales')
                            ->schema([
                                Forms\Components\Placeholder::make('')
                                    ->content(new HtmlString('<h2 class="text-xl font-semibold text-gray-800 mb-4">¿Cuéntanos qué quieres publicar?</h2>')),
                                Forms\Components\Radio::make('operation_type')
                                    ->label('Tipo de operación')
                                    ->options([
                                        'sale' => 'Venta',
                                        'rent' => 'Renta',
                                        'both' => 'Venta y Renta',
                                    ])
                                    ->required()
                                    ->inline()
                                    ->live(),
                                Forms\Components\Select::make('property_type_id')
                                    ->label('Tipo de propiedad')
                                    ->options(function () {
                                        $groupedOptions = [];
                                        $categories = \App\Models\Category::with('propertyTypes')->orderBy('name')->get();
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

                                $tabs = static::getFeatureSectionsTabs($propertyType, null, false);

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
                                    ->columns(1);

                                $tabs[] = Forms\Components\Tabs\Tab::make('Descripción')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        Forms\Components\Section::make('Describe tu propiedad')
                                            ->description('Agrega datos relevantes como: Acabados de la propiedad, Servicios adicionales, Reglamentos, Lugares cercanos como escuelas, hospitales, tiendas departamentales, Entretenimiento, etc.')
                                            ->schema([
                                                Forms\Components\Textarea::make('description')
                                                    ->label('Descripción')
                                                    ->placeholder('Describe tu propiedad detalladamente...')
                                                    ->columnSpanFull()
                                                    ->maxLength(1500),
                                            ])
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1);

                                return [
                                    Forms\Components\Tabs::make('Especificaciones de la Propiedad')
                                        ->tabs($tabs)
                                        ->columnSpanFull(),
                                ];
                            })
                            ->columns(1)
                            ->afterValidation(function (Forms\Get $get, array $state) {
                                
                            }),

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
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', Property::STATUS_PENDING_REVIEW))
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

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Enviado por')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Solicitud')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filtrar por Estado')
                    ->options([
                        Property::STATUS_PENDING_REVIEW => 'Pendiente de Revisión',
                        Property::STATUS_PUBLISHED => 'Publicada',
                        Property::STATUS_REJECTED => 'Rechazada',
                        Property::STATUS_DRAFT => 'Borrador',
                        Property::STATUS_INACTIVE => 'Inactiva',
                        Property::STATUS_SOLD => 'Vendida',
                        Property::STATUS_RENTED => 'Rentada',
                    ])
                    ->default(Property::STATUS_PENDING_REVIEW),
                Tables\Filters\SelectFilter::make('operation_type')
                    ->label('Tipo de Operación')
                    ->options([
                        'sale' => 'Venta',
                        'rent' => 'Renta',
                        'both' => 'Venta y Renta',
                    ]),
                Tables\Filters\SelectFilter::make('property_type_id')
                    ->label('Tipo de Propiedad')
                    ->options(PropertyType::all()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Revisar'),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->label('Aprobar')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('¿Aprobar esta propiedad?')
                        ->modalDescription('Una vez aprobada, la propiedad será visible públicamente.')
                        ->visible(fn(Property $record): bool => $record->status === Property::STATUS_PENDING_REVIEW || $record->status === Property::STATUS_REJECTED)
                        ->action(function (Property $record) {
                            Log::info('Iniciando aprobación de propiedad desde acción de tabla', ['property_id' => $record->id]);

                            $record->update([
                                'status' => Property::STATUS_PUBLISHED,
                                'published_at' => now(),
                                'approved_at' => now(),
                                'rejected_at' => null,
                                'admin_notes' => null,
                            ]);

                            if (!$record->user) {
                                Log::error('Usuario no encontrado para la propiedad', ['property_id' => $record->id]);
                                Notification::make()
                                    ->title('Error')
                                    ->body('No se encontró el usuario propietario para enviar la notificación.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            Log::info('Usuario encontrado, enviando notificación de aprobación desde acción de tabla', [
                                'property_id' => $record->id,
                                'user_id' => $record->user->id
                            ]);

                            try {
                                $record->user->notify(new PropertyStatusUpdated($record));
                                Log::info('Notificación de aprobación enviada exitosamente desde acción de tabla');
                            } catch (\Exception $e) {
                                Log::error('Error al enviar notificación de aprobación desde acción de tabla', [
                                    'error' => $e->getMessage(),
                                    'property_id' => $record->id
                                ]);
                            }

                            Notification::make()
                                ->title('Propiedad aprobada y publicada.')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('reject')
                        ->label('Rechazar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('¿Rechazar esta propiedad?')
                        ->modalDescription('La propiedad no será visible públicamente.')
                        ->form([
                            Forms\Components\Textarea::make('admin_notes')
                                ->label('Razón del rechazo (obligatorio)')
                                ->required()
                                ->rows(3)
                                ->placeholder('Explica por qué la propiedad es rechazada. Esta nota será visible para el anunciante.'),
                        ])
                        ->visible(fn(Property $record): bool => $record->status === Property::STATUS_PENDING_REVIEW || $record->status === Property::STATUS_PUBLISHED)
                        ->action(function (Property $record, array $data) {
                            $adminNotes = $data['admin_notes'];

                            Log::info('Iniciando rechazo de propiedad desde acción de tabla', [
                                'property_id' => $record->id,
                                'admin_notes' => $adminNotes
                            ]);

                            $record->update([
                                'status' => Property::STATUS_REJECTED,
                                'admin_notes' => $adminNotes,
                                'rejected_at' => now(),
                                'published_at' => null,
                                'approved_at' => null,
                            ]);

                            if (!$record->user) {
                                Log::error('Usuario no encontrado para la propiedad', ['property_id' => $record->id]);
                                Notification::make()
                                    ->title('Error')
                                    ->body('No se encontró el usuario propietario para enviar la notificación.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            Log::info('Usuario encontrado, enviando notificación de rechazo desde acción de tabla', [
                                'property_id' => $record->id,
                                'user_id' => $record->user->id
                            ]);

                            try {
                                $record->user->notify(new PropertyStatusUpdated($record, $adminNotes));
                                Log::info('Notificación de rechazo enviada exitosamente desde acción de tabla');
                            } catch (\Exception $e) {
                                Log::error('Error al enviar notificación de rechazo desde acción de tabla', [
                                    'error' => $e->getMessage(),
                                    'property_id' => $record->id
                                ]);
                            }

                            Notification::make()
                                ->title('Propiedad rechazada.')
                                ->danger()
                                ->send();
                        }),
                ])->label('Acciones de Moderación'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'asc')
            ->emptyStateHeading('No hay solicitudes de propiedades pendientes')
            ->emptyStateDescription('Todas las propiedades han sido revisadas o no hay nuevas solicitudes.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
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

    public static function canCreate(): bool
    {
        return false;
    }
}
