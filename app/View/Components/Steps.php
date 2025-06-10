<?php
// app/View/Components/Steps.php

namespace App\View\Components;

use Illuminate\View\Component;

class Steps extends Component
{
    public $steps;

    public function __construct()
    {
        $this->steps = [
            [
                'number' => 1,
                'title' => 'Registro Inicial',
                'description' => 'Completa tu perfil profesional con tus datos personales y experiencia.',
                'color' => 'blue'
            ],
            [
                'number' => 2,
                'title' => 'Envía Documentación',
                'description' => 'Adjunta tu solicitud oficial y documentos requeridos para la verificación.',
                'color' => 'blue'
            ],
            [
                'number' => 3,
                'title' => 'Proceso de Aprobación',
                'description' => 'Nuestro equipo revisará tu solicitud en un plazo de 48-72 horas.',
                'color' => 'blue'
            ],
            [
                'number' => '✓',
                'title' => '¡Comienza a Vender!',
                'description' => 'Accede a tu panel y empieza a promocionar propiedades inmediatamente.',
                'color' => 'green'
            ]
        ];
    }

    public function render()
    {
        return view('components.sections.steps');
    }
}
