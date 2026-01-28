<?php

namespace App\Mail;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanDueMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Loan $loan
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $daysUntilDue = $this->loan->due_date->diffInDays(now(), false);
        $isOverdue = $daysUntilDue < 0;

        $subject = $isOverdue
            ? 'Peminjaman Terlambat - ' . $this->loan->item->collection->title
            : 'Peminjaman Akan Jatuh Tempo - ' . $this->loan->item->collection->title;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $daysUntilDue = $this->loan->due_date->diffInDays(now(), false);
        $isOverdue = $daysUntilDue < 0;

        return new Content(
            view: 'emails.loans.due',
            with: [
                'loan' => $this->loan,
                'member' => $this->loan->member,
                'collection' => $this->loan->item->collection,
                'dueDate' => $this->loan->due_date->format('d/m/Y'),
                'isOverdue' => $isOverdue,
                'daysUntilDue' => $daysUntilDue,
                'calculatedFine' => $this->loan->calculated_fine ?? 0,
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
