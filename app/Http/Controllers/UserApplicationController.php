<?php

namespace App\Http\Controllers;

use App\Models\UserApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $userTypesOptions = UserApplication::TYPE_OPTIONS;

        
        if ($user->hasAnyRole(['owner', 'agent', 'real_estate_company'])) {
            return redirect('dashboard')
                ->with('info', 'Ya tienes un perfil de anunciante activo.');
        }

        
        $preselectedType = null;
        $typeFromUrl = $request->query('type');

        if ($typeFromUrl && array_key_exists($typeFromUrl, $userTypesOptions)) {
            $preselectedType = $typeFromUrl;

            if ($user->hasRole($preselectedType)) {
                return redirect('dashboard')
                    ->with('info', 'Ya eres un(a) ' . $userTypesOptions[$preselectedType] . ' registrado(a).');
            }
        }

      
        $latestApplication = $user->userApplications()->latest()->first();

        if ($latestApplication && in_array($latestApplication->status, [
            UserApplication::STATUS_PENDING,
            UserApplication::STATUS_APPROVED
        ])) {
            return redirect()->route('solicitud.estado')
                ->with('info', 'Ya tienes una solicitud ' .
                    $latestApplication->status_human_readable . '.');
        }

        return view('solicitud.formulario', [
            'preselectedType' => $preselectedType,
            'userTypesOptions' => $userTypesOptions,
        ]);
    }

    public function status()
    {
        $userApplication = Auth::user()->userApplications()->latest()->first();

        if (!$userApplication) {
            return redirect()->route('solicitud.formulario')
                ->with('info', 'Aún no has enviado ninguna solicitud de perfil.');
        }

        return view('solicitud.estado', compact('userApplication'));
    }
}
