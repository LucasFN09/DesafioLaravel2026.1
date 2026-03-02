<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ContactUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $assunto;
    public $mensagem;

    /**
     * Create a new message instance.
     */
    public function __construct(User $usuario, $assunto, $mensagem)
    {
        $this->usuario = $usuario;
        $this->assunto = $assunto;
        $this->mensagem = $mensagem;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->assunto,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-user',
            with: [
                'usuario' => $this->usuario,
                'mensagem' => $this->mensagem,
                'admin' => auth()->user(),
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
        return [];
    }
}
