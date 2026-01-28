<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class LoanDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Loan $loan
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $collection = $this->loan->item->collection;
        $daysUntilDue = $this->loan->due_date->diffInDays(now(), false);
        $isOverdue = $daysUntilDue < 0;

        $subject = $isOverdue
            ? 'Peminjaman Terlambat - ' . $collection->title
            : 'Peminjaman Akan Jatuh Tempo - ' . $collection->title;

        $message = $isOverdue
            ? "Peminjaman koleksi Anda sudah terlambat " . abs($daysUntilDue) . " hari."
            : "Peminjaman koleksi Anda akan jatuh tempo dalam {$daysUntilDue} hari.";

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($message)
            ->line('**Judul:** ' . $collection->title)
            ->line('**Tanggal Jatuh Tempo:** ' . $this->loan->due_date->format('d/m/Y'))
            ->action('Lihat Peminjaman', url('/loans/' . $this->loan->id))
            ->salutation('Terima kasih, Tim Perpustakaan');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $collection = $this->loan->item->collection;
        $daysUntilDue = $this->loan->due_date->diffInDays(now(), false);
        $isOverdue = $daysUntilDue < 0;

        return [
            'title' => $isOverdue ? 'Peminjaman Terlambat!' : 'Peminjaman Akan Jatuh Tempo',
            'message' => "Peminjaman \"{$collection->title}\" " . ($isOverdue ? "terlambat " . abs($daysUntilDue) . " hari" : "akan jatuh tempo {$daysUntilDue} hari"),
            'loan_id' => $this->loan->id,
            'collection_title' => $collection->title,
            'collection_id' => $collection->id,
            'due_date' => $this->loan->due_date->format('d/m/Y'),
            'days_until_due' => $daysUntilDue,
            'is_overdue' => $isOverdue,
            'calculated_fine' => $this->loan->calculated_fine ?? 0,
            'type' => 'loan_due',
            'icon' => $isOverdue ? 'exclamation-circle' : 'calendar',
            'color' => $isOverdue ? 'danger' : 'info',
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        $collection = $this->loan->item->collection;
        $daysUntilDue = $this->loan->due_date->diffInDays(now(), false);
        $isOverdue = $daysUntilDue < 0;

        return new DatabaseMessage([
            'title' => $isOverdue ? 'Peminjaman Terlambat!' : 'Peminjaman Akan Jatuh Tempo',
            'message' => "Peminjaman \"{$collection->title}\" " . ($isOverdue ? "terlambat " . abs($daysUntilDue) . " hari" : "akan jatuh tempo {$daysUntilDue} hari"),
            'loan_id' => $this->loan->id,
            'collection_title' => $collection->title,
            'due_date' => $this->loan->due_date->format('d/m/Y'),
            'days_until_due' => $daysUntilDue,
            'is_overdue' => $isOverdue,
            'type' => 'loan_due',
            'icon' => $isOverdue ? 'exclamation-circle' : 'calendar',
            'color' => $isOverdue ? 'danger' : 'info',
        ]);
    }
}
