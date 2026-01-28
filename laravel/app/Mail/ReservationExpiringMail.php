<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Reservation $reservation
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $daysRemaining = $this->reservation->expiry_date->diffInDays(now());

        return new Envelope(
            subject: "Reservasi Akan Kadaluarsa ({$daysRemaining} Hari) - " . $this->reservation->item->collection->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $daysRemaining = $this->reservation->expiry_date->diffInDays(now());

        return new Content(
            view: 'emails.reservations.expiring',
            with: [
                'reservation' => $this->reservation,
                'member' => $this->reservation->member,
                'collection' => $this->reservation->item->collection,
                'branch' => $this->reservation->branch,
                'expiryDate' => $this->reservation->expiry_date->format('d/m/Y'),
                'daysRemaining' => $daysRemaining,
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
