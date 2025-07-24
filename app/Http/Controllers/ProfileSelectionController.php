<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserApplication;

class ProfileSelectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showSelectionForm()
    {
        $user = Auth::user();

        $hasAdvertiserRole = $user->hasAnyRole(['owner', 'agent', 'real_estate_company']);
        if ($hasAdvertiserRole) {
            session()->flash('info', 'Ya tienes un perfil de anunciante activo. Administra tus propiedades desde tu panel de control.');
            return redirect('/dashboard');
        }

        $latestApplication = $user->userApplications()->latest()->first();
        if (
            $latestApplication &&
            ($latestApplication->status === UserApplication::STATUS_PENDING ||
                $latestApplication->status === UserApplication::STATUS_APPROVED)
        ) {
            session()->flash('info', 'Ya tienes una solicitud de perfil en curso. Puedes revisar su estado aquí.');
            return redirect()->route('solicitud.estado');
        }

        $profiles = [
            'owner' => [
                'title' => 'Particular',
                'description' => 'Para publicar tus propias propiedades sin intermediarios.',
                'icon_class' => 'heroicon-o-user'
            ],
            'agent' => [
                'title' => 'Agente',
                'description' => 'Para profesionales inmobiliarios que gestionan propiedades de terceros.',
                'icon_class' => 'heroicon-o-briefcase'
            ],
            'real_estate_company' => [
                'title' => 'Inmobiliaria',
                'description' => 'Para empresas que construyen y venden proyectos inmobiliarios.',
                'icon_class' => 'heroicon-o-building-office-2'
            ],
        ];

        return view('solicitud.profile_selection', compact('profiles'));
    }
}
