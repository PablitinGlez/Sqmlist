<?php

namespace App\Http\Controllers;

use App\Mail\PropertyContactMail;
use App\Models\Property;
use App\Models\PropertyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PropertyMessageController extends Controller
{
    public function store(Request $request, Property $property)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        if (!$property->user) {
            return back()->with('error', 'No se pudo encontrar el propietario de esta publicación.')->withInput();
        }

        $ownerEmail = $property->user->email;

        try {
            $propertyContact = PropertyContact::create([
                'property_id' => $property->id,
                'sender_name' => $validatedData['name'],
                'sender_email' => $validatedData['email'],
                'sender_phone' => $validatedData['phone'],
                'message_text' => $validatedData['message'],
            ]);

            Mail::to($ownerEmail)->send(new PropertyContactMail(
                $validatedData['name'],
                $validatedData['email'],
                $validatedData['phone'],
                $validatedData['message'],
                $property
            ));

            return back()->with('success', '¡Tu mensaje ha sido enviado y guardado con éxito!');
        } catch (\Exception $e) {
         
            return back()->with('error', 'Hubo un problema al enviar o guardar tu mensaje. Por favor, inténtalo de nuevo más tarde.')
                ->withInput();
        }
    }
}
