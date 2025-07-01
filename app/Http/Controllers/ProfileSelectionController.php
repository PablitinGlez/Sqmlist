<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserApplication;

/**
 * Controlador ProfileSelectionController
 * Permite a un usuario autenticado seleccionar el tipo de perfil de anunciante que desea ser.
 * Redirige al usuario según su estado actual (si ya tiene un rol o una solicitud en curso).
 */
class ProfileSelectionController extends Controller
{
    /**
     * Aplica el middleware 'auth' a todas las acciones del controlador.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el formulario de selección de perfil o redirige al usuario.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showSelectionForm()
    {
        $user = Auth::user();

        // Si el usuario ya tiene un rol de anunciante, redirige al dashboard.
        $hasAdvertiserRole = $user->hasAnyRole(['owner', 'agent', 'real_estate_company']);
        if ($hasAdvertiserRole) {
            session()->flash('info', 'Ya tienes un perfil de anunciante activo. Administra tus propiedades desde tu panel de control.');
            return redirect()->route('dashboard');
        }

        // Si el usuario tiene una solicitud pendiente o aprobada, redirige a la página de estado.
        $latestApplication = $user->userApplications()->latest()->first();
        if (
            $latestApplication &&
            ($latestApplication->status === UserApplication::STATUS_PENDING ||
                $latestApplication->status === UserApplication::STATUS_APPROVED)
        ) {
            session()->flash('info', 'Ya tienes una solicitud de perfil en curso. Puedes revisar su estado aquí.');
            return redirect()->route('solicitud.estado');
        }

        // Define las opciones de perfil a mostrar en el formulario.
        $profiles = [
            'owner' => [
                'title' => 'Particular / Dueño Directo',
                'description' => 'Para publicar tus propias propiedades sin intermediarios.',
                'icon_class' => 'heroicon-o-user'
            ],
            'agent' => [
                'title' => 'Agente / Corredor',
                'description' => 'Para profesionales inmobiliarios que gestionan propiedades de terceros.',
                'icon_class' => 'heroicon-o-briefcase'
            ],
            'real_estate_company' => [
                'title' => 'Constructora / Desarrolladora',
                'description' => 'Para empresas que construyen y venden proyectos inmobiliarios.',
                'icon_class' => 'heroicon-o-building-office-2'
            ],
        ];

        return view('solicitud.profile_selection', compact('profiles'));
    }
}
