<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DueDateNotification extends Notification
{
    use Queueable;

    protected $loan;
    protected $daysUntilDue;

    public function __construct($loan, int $daysUntilDue)
    {
        $this->loan = $loan;
        $this->daysUntilDue = $daysUntilDue;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengingatan: Peminjaman Akan Jatuh Tempo')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Kami ingin mengingatkan bahwa peminjaman Anda akan jatuh tempo dalam ' . $this->daysUntilDue . ' hari.')
            ->line('**Judul:** ' . $this->loan->item->collection->title)
            ->line('**Tanggal Pinjam:** ' . $this->loan->loaned_at->format('d M Y'))
            ->line('**Tanggal Kembali:** ' . $this->loan->due_date->format('d M Y'))
            ->line('Harap kembalikan buku sebelum tanggal jatuh tempo untuk menghindari denda.')
            ->action('Lihat Detail Peminjaman', url('/dashboard'))
            ->line('Terima kasih telah menggunakan perpustakaan kami.');
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => 'Peminjaman Akan Jatuh Tempo',
            'message' => 'Buku "' . $this->loan->item->collection->title . '" akan jatuh tempo dalam ' . $this->daysUntilDue . ' hari.',
            'type' => 'due_date_reminder',
            'loan_id' => $this->loan->id,
        ]);
    }
}
