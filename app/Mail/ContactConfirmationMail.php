<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class ContactConfirmationMail extends Mailable implements ShouldQueue
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
            subject: 'Confirmación - Hemos recibido tu mensaje'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-confirmation',
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
