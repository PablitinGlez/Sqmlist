<?php

namespace App\Mail;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PropertyContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $phone;
    public $userMessage;
    public $property;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $email, ?string $phone, string $userMessage, Property $property)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->userMessage = $userMessage;
        $this->property = $property;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo Mensaje de Contacto para tu Propiedad: ' . $this->property->title,
            replyTo: [
                new \Illuminate\Mail\Mailables\Address($this->email, $this->name),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // ¡CAMBIO AQUÍ! Usamos 'view' en lugar de 'markdown'
            view: 'emails.property-contact',
            with: [
                'propertyName' => $this->property->title,
                'propertyLink' => route('properties.show', $this->property->slug),
                'senderName' => $this->name,
                'senderEmail' => $this->email,
                'senderPhone' => $this->phone,
                'userMessage' => $this->userMessage,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
