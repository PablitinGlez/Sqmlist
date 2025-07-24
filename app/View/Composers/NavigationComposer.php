<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\UserApplication;
use App\Models\State;
use App\Models\PropertyType;

class NavigationComposer
{
    public function compose(View $view): void
    {
        $popularStates = State::whereIn('name', [
            'Ciudad de México',
            'Edo. de México',
            'Querétaro',
            'Morelos',
            'Nuevo León',
            'Yucatán',
            'Quintana Roo',
            'Jalisco'
        ])->orderBy('name')->get();

        $popularPropertyTypes = PropertyType::whereIn('name', [
            'Casa',
            'Departamento',
            'Terreno / Lote',
            'Casa en condominio',
            'Local Comercial',
            'Oficina',
            'Bodega',
            'Edificio'
        ])->orderBy('name')->get();

        $bedroomOptions = [
            ['value' => '1', 'label' => '1 recámara'],
            ['value' => '2', 'label' => '2 recámaras'],
            ['value' => '3', 'label' => '3 recámaras'],
            ['value' => '4', 'label' => '4 recámaras'],
            ['value' => '5+', 'label' => '5 o más recámaras']
        ];

        $navigationLinks = [
            [
                'name' => 'En venta',
                'route' => route('properties.index', ['operacion' => 'sale']),
                'active' => request()->routeIs('properties.index') && request('operacion') === 'sale',
                'type' => 'dropdown',
                'operacion' => 'sale',
                'dropdown_items' => [
                    'states' => $popularStates,
                    'property_types' => $popularPropertyTypes,
                    'bedrooms' => $bedroomOptions
                ]
            ],
            [
                'name' => 'En renta',
                'route' => route('properties.index', ['operacion' => 'rent']),
                'active' => request()->routeIs('properties.index') && request('operacion') === 'rent',
                'type' => 'dropdown',
                'operacion' => 'rent',
                'dropdown_items' => [
                    'states' => $popularStates,
                    'property_types' => $popularPropertyTypes,
                    'bedrooms' => $bedroomOptions
                ]
            ],
            [
                'name' => 'Nosotros',
                'route' => route('about'),
                'active' => request()->routeIs('about'),
                'type' => 'simple'
            ],
            [
                'name' => 'Contacto',
                'route' => route('contact.create'),
                'active' => request()->routeIs('contact.create'),
                'type' => 'simple'
            ],
        ];

        $buttonConfig = $this->getButtonConfig();

        $view->with([
            'navigationLinks' => $navigationLinks,
            'buttonText' => $buttonConfig['text'],
            'buttonRoute' => $buttonConfig['route'],
            'buttonClass' => $buttonConfig['class'],
            'shouldShowButton' => $buttonConfig['show'],
            'hasAdvertiserRole' => $buttonConfig['hasAdvertiserRole'],
        ]);
    }

    protected function getButtonConfig(): array
    {
        $defaultConfig = [
            'text' => 'Publicar',
            'route' => route('register'),
            'class' => 'bg-blue-600 hover:bg-blue-700',
            'show' => true,
            'hasAdvertiserRole' => false
        ];

        if (!Auth::check()) {
            return $defaultConfig;
        }

        $user = Auth::user();
        $businessRoles = ['owner', 'agent', 'real_estate_company'];
        $hasActiveBusinessProfile = $user->hasAnyRole($businessRoles) &&
            optional($user->profileDetails)->status === 'active';

        if ($hasActiveBusinessProfile) {
            return [
                'show' => false,
                'hasAdvertiserRole' => true,
                'text' => '',
                'route' => '',
                'class' => ''
            ];
        }

        $latestApplication = $user->userApplications()->latest()->first();

        if (!$latestApplication) {
            return [
                'text' => 'Publicar',
                'route' => route('solicitud.perfil'),
                'class' => 'bg-blue-600 hover:bg-blue-700',
                'show' => true,
                'hasAdvertiserRole' => false
            ];
        }

        return match ($latestApplication->status) {
            UserApplication::STATUS_PENDING, UserApplication::STATUS_APPROVED => [
                'text' => 'Estado de Solicitud',
                'route' => route('solicitud.estado'),
                'class' => 'bg-yellow-600 hover:bg-yellow-700',
                'show' => true,
                'hasAdvertiserRole' => false
            ],
            UserApplication::STATUS_REJECTED => [
                'text' => 'Publicar',
                'route' => route('solicitud.perfil'),
                'class' => 'bg-blue-600 hover:bg-blue-700',
                'show' => true,
                'hasAdvertiserRole' => false
            ],
            default => $defaultConfig
        };
    }
}
