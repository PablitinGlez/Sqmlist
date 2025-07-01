<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Envía un correo electrónico al administrador con los detalles de un nuevo mensaje de contacto
 * recibido a través del formulario de la aplicación. Se envía de forma asíncrona.
 */
class ContactFormMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo mensaje de contacto - ' . $this->contact->subject_label,
            replyTo: $this->contact->email
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-form',
            with: [
                'contact' => $this->contact
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
