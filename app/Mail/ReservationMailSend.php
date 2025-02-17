<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationMailSend extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $type;
    public $view;

    /**
     * Create a new message instance.
     */
    public function __construct($reservation, $type)
    {
        $this->reservation = $reservation;
        $this->type = $type;

        $views = [
            'HOTEL' => 'reservation.hotel',
            'VOL' => 'reservation.flight',
            'LOCATION' => 'reservation.carlocation',
        ];

        $this->view = $views[$this->type] ?? 'reservation.flighthotel';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle réservation (' . $this->type . ')',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.' . $this->view,
            with: [
                'reservation' => $this->reservation,
                'type' => $this->type,
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
