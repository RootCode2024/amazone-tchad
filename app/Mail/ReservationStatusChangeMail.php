<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $type;
    public $template;

    /**
     * Create a new message instance.
     */
    public function __construct($reservation, $type)
    {
        $this->reservation = $reservation;
        $this->type = $type;

       $this->template = $reservation->status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Changement de statut de votre réservation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.status.' . $this->template,
            with: ['reservation' => $this->reservation, 'type' => $this->type],
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
