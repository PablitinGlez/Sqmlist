<?php

namespace App\Http\Controllers;

use App\Mail\PropertyContactMail;
use App\Models\Property;
use App\Models\PropertyContact; // ¡IMPORTA EL NUEVO MODELO AQUI!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // Puedes usarlo para depuración

class PropertyMessageController extends Controller
{
    /**
     * Almacena un nuevo mensaje de contacto para una propiedad.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Property $property)
    {
        // 1. Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        // 2. Verificar si la propiedad tiene un usuario asociado (dueño)
        if (!$property->user) {
            return back()->with('error', 'No se pudo encontrar el propietario de esta publicación.')->withInput();
        }

        $ownerEmail = $property->user->email;

        try {
            // 3. Guardar el mensaje en la base de datos
            // Usamos 'message_text' en el modelo y migración para evitar conflictos
            $propertyContact = PropertyContact::create([
                'property_id' => $property->id,
                'sender_name' => $validatedData['name'],
                'sender_email' => $validatedData['email'],
                'sender_phone' => $validatedData['phone'], // 'nullable' ya maneja si es null
                'message_text' => $validatedData['message'],
                // is_read y is_archived se establecen en false por defecto en la migración
            ]);

            // 4. Enviar el correo electrónico al dueño de la propiedad
            Mail::to($ownerEmail)->send(new PropertyContactMail(
                $validatedData['name'],
                $validatedData['email'],
                $validatedData['phone'],
                $validatedData['message'],
                $property
            ));

            return back()->with('success', '¡Tu mensaje ha sido enviado y guardado con éxito!');
        } catch (\Exception $e) {
            // Manejar errores: Si algo sale mal (ej. problema con la DB o el correo)
            // Es buena práctica registrar el error para poder depurarlo.
            Log::error("Error al procesar mensaje de propiedad: " . $e->getMessage(), [
                'property_id' => $property->id,
                'sender_email' => $request->email,
                'exception' => $e
            ]);

            return back()->with('error', 'Hubo un problema al enviar o guardar tu mensaje. Por favor, inténtalo de nuevo más tarde.')
                ->withInput();
        }
    }
}
