<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Mail\ContactConfirmationMail;


/**
 * Controlador ContactController
 * Gestiona la visualización y procesamiento del formulario de contacto público.
 * Guarda los mensajes en la base de datos y envía correos de notificación/confirmación.
 */
class ContactController extends Controller
{
   
    public function create()
    {
        return view('pages.contact');
    }

    /**
     * Procesa y almacena un nuevo mensaje del formulario de contacto.
     * Envía notificaciones por correo electrónico al administrador y al remitente.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\RedirectResponse 
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|in:consulta_general,soporte_tecnico,ventas,otros',
            'message' => 'required|string|min:10|max:2000'
        ], [
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser un correo electrónico válido.',
            'subject.required' => 'Debes seleccionar un motivo.',
            'subject.in' => 'El motivo seleccionado no es válido.',
            'message.required' => 'El mensaje es obligatorio.',
            'message.min' => 'El mensaje debe tener al menos 10 caracteres.',
            'message.max' => 'El mensaje no puede exceder 2000 caracteres.'
        ]);

        try {
            $contact = Contact::create($validated);

            if (config('mail.contact.admin_email')) {
                Mail::to(config('mail.contact.admin_email'))
                    ->send(new ContactFormMail($contact));
            }

            Mail::to($validated['email'])
                ->send(new ContactConfirmationMail($contact));

            return redirect()->back()->with(
                'success',
                '¡Gracias por contactarnos! Hemos recibido tu mensaje y te responderemos pronto.'
            );
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un problema al enviar tu mensaje. Por favor, inténtalo de nuevo.');
        }
    }
}
