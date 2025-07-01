<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\UserApplication;

class NavigationComposer
{
    public function compose(View $view): void
    {
        $navigationLinks = [
            [
                'name' => 'En venta',
                'route' => '#',
                'active' => false,
                'dropdown' => [
                    'Tipo de propiedad' => ['Casa', 'Departamento', 'Oficina', 'Local comercial'],
                ]
            ],
            [
                'name' => 'En renta',
                'route' => '#',
                'active' => false,
                'dropdown' => [
                    'Tipo de propiedad' => ['Casa', 'Departamento', 'Oficinas', 'Locales comerciales'],
                ]
            ],
            [
                'name' => 'Agentes',
                'route' => '#',
                'active' => false,
            ],
            [
                'name' => 'Nosotros',
                'route' => route('about'),
                'active' => false,
            ],
            [
                'name' => 'Contacto',
                'route' => route('contact.create'),
                'active' => false,
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
