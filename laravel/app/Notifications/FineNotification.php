<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class FineNotification extends Notification
{
    use Queueable;

    protected $fine;
    protected $loan;

    public function __construct($fine, $loan)
    {
        $this->fine = $fine;
        $this->loan = $loan;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Denda Peminjaman')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Anda memiliki denda peminjaman yang perlu dibayar.')
            ->line('**Judul Buku:** ' . $this->loan->item->collection->title)
            ->line('**Jumlah Denda:** Rp ' . number_format($this->fine->amount, 0, ',', '.'))
            ->line('Silakan lakukan pembayaran denda di perpustakaan.')
            ->action('Lihat Detail', url('/dashboard'))
            ->line('Terima kasih.');
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => 'Denda Peminjaman',
            'message' => 'Anda memiliki denda sebesar Rp ' . number_format($this->fine->amount, 0, ',', '.') . ' untuk buku "' . $this->loan->item->collection->title . '"',
            'type' => 'fine',
            'fine_id' => $this->fine->id,
        ]);
    }
}
