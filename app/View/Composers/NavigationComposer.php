<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\UserApplication;
// use App\Models\PropertyType; // Ya no es necesario importar PropertyType aquí si no generamos enlaces por tipo

class NavigationComposer
{
    public function compose(View $view): void
    {
        // No necesitamos cargar PropertyType si los enlaces no son por tipo de propiedad anidado.
        // $propertyTypes = PropertyType::orderBy('name')->get();

        $navigationLinks = [
            // --- Enlace para "En Venta" (directo) ---
            [
                'name' => 'En venta',
                'route' => route('properties.index', ['operacion' => 'sale']),
                // Activo si estamos en la ruta de propiedades y el parámetro 'operacion' es 'sale'
                'active' => request()->routeIs('properties.index') && request('operacion') === 'sale',
            ],
            // --- Enlace para "En Renta" (directo) ---
            [
                'name' => 'En renta',
                'route' => route('properties.index', ['operacion' => 'rent']),
                // Activo si estamos en la ruta de propiedades y el parámetro 'operacion' es 'rent'
                'active' => request()->routeIs('properties.index') && request('operacion') === 'rent',
            ],
            // --- Otros enlaces (Agentes, Nosotros, Contacto) ---
            [
                'name' => 'Agentes',
                'route' => '#', // Mantener como placeholder o cambiar a tu ruta real de agentes
                'active' => false, // O la lógica de activo para agentes
            ],
            [
                'name' => 'Nosotros',
                'route' => route('about'),
                'active' => request()->routeIs('about'),
            ],
            [
                'name' => 'Contacto',
                'route' => route('contact.create'),
                'active' => request()->routeIs('contact.create'),
            ],
        ];

        // Lógica del botón de publicación (se mantiene igual)
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
