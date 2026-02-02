<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OverdueNotification extends Notification
{
    use Queueable;

    protected $loan;
    protected $daysOverdue;
    protected $fineAmount;

    public function __construct($loan, int $daysOverdue, float $fineAmount)
    {
        $this->loan = $loan;
        $this->daysOverdue = $daysOverdue;
        $this->fineAmount = $fineAmount;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('PENTING: Peminjaman Terlambat')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Peminjaman Anda telah terlambat ' . $this->daysOverdue . ' hari.')
            ->line('**Judul:** ' . $this->loan->item->collection->title)
            ->line('**Tanggal Jatuh Tempo:** ' . $this->loan->due_date->format('d M Y'))
            ->line('**Denda:** Rp ' . number_format($this->fineAmount, 0, ',', '.'))
            ->line('Harap segera kembalikan buku ke perpustakaan untuk menghentikan akumulasi denda.')
            ->action('Lihat Detail Peminjaman', url('/dashboard'))
            ->line('Terima kasih.');
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => 'Peminjaman Terlambat',
            'message' => 'Buku "' . $this->loan->item->collection->title . '" terlambat ' . $this->daysOverdue . ' hari. Denda: Rp ' . number_format($this->fineAmount, 0, ',', '.'),
            'type' => 'overdue',
            'loan_id' => $this->loan->id,
        ]);
    }
}
