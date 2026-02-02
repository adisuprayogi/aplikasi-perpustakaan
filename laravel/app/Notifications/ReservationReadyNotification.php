<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ReservationReadyNotification extends Notification
{
    use Queueable;

    protected $reservation;
    protected $pickupDeadline;

    public function __construct($reservation, $pickupDeadline)
    {
        $this->reservation = $reservation;
        $this->pickupDeadline = $pickupDeadline;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reservasi Siap Diambil')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Buku yang Anda reservasi sekarang sudah tersedia!')
            ->line('**Judul:** ' . $this->reservation->item->collection->title)
            ->line('**Lokasi:** ' . ($this->reservation->item->branch->name ?? '-'))
            ->line('**Batas Pengambilan:** ' . $this->pickupDeadline->format('d M Y H:i'))
            ->line('Silakan ambil buku sebelum batas waktu pengambilan.')
            ->action('Lihat Detail Reservasi', url('/my-reservations'))
            ->line('Terima kasih.');
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => 'Reservasi Siap Diambil',
            'message' => 'Buku "' . $this->reservation->item->collection->title . '" sudah tersedia. Ambil sebelum ' . $this->pickupDeadline->format('d M Y'),
            'type' => 'reservation_ready',
            'reservation_id' => $this->reservation->id,
        ]);
    }
}
