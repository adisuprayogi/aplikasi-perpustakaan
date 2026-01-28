<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class ReservationExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Reservation $reservation
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
        $collection = $this->reservation->item->collection;
        $daysRemaining = $this->reservation->expiry_date->diffInDays(now());

        return (new MailMessage)
            ->subject("Reservasi Akan Kadaluarsa ({$daysRemaining} Hari)")
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Reservasi Anda untuk koleksi berikut akan **kadaluarsa dalam ' . $daysRemaining . ' hari**.')
            ->line('**Judul:** ' . $collection->title)
            ->line('**Tanggal Kadaluarsa:** ' . $this->reservation->expiry_date->format('d/m/Y'))
            ->line('Segera ambil koleksi di perpustakaan sebelum kadaluarsa.')
            ->action('Lihat Reservasi Saya', url('/my-reservations'))
            ->salutation('Terima kasih, Tim Perpustakaan');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $collection = $this->reservation->item->collection;
        $daysRemaining = $this->reservation->expiry_date->diffInDays(now());

        return [
            'title' => 'Reservasi Akan Kadaluarsa',
            'message' => "Reservasi \"{$collection->title}\" akan kadaluarsa dalam {$daysRemaining} hari.",
            'reservation_id' => $this->reservation->id,
            'collection_title' => $collection->title,
            'collection_id' => $collection->id,
            'expiry_date' => $this->reservation->expiry_date->format('d/m/Y'),
            'days_remaining' => $daysRemaining,
            'type' => 'reservation_expiring',
            'icon' => 'clock',
            'color' => 'warning',
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        $collection = $this->reservation->item->collection;
        $daysRemaining = $this->reservation->expiry_date->diffInDays(now());

        return new DatabaseMessage([
            'title' => 'Reservasi Akan Kadaluarsa',
            'message' => "Reservasi \"{$collection->title}\" akan kadaluarsa dalam {$daysRemaining} hari.",
            'reservation_id' => $this->reservation->id,
            'collection_title' => $collection->title,
            'expiry_date' => $this->reservation->expiry_date->format('d/m/Y'),
            'days_remaining' => $daysRemaining,
            'type' => 'reservation_expiring',
            'icon' => 'clock',
            'color' => 'warning',
        ]);
    }
}
