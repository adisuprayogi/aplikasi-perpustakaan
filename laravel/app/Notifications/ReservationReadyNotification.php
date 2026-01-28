<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class ReservationReadyNotification extends Notification implements ShouldQueue
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
        $branch = $this->reservation->branch;

        return (new MailMessage)
            ->subject('Koleksi Siap Diambil - ' . $collection->title)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Koleksi yang Anda reservasi sudah **siap diambil** di perpustakaan.')
            ->line('**Judul:** ' . $collection->title)
            ->line('**Lokasi Pengambilan:** ' . $branch->name)
            ->line('**Batas Pengambilan:** ' . $this->reservation->expiry_date->format('d/m/Y'))
            ->action('Lihat Reservasi Saya', url('/my-reservations'))
            ->line('Silakan ambil koleksi sebelum tanggal kadaluarsa.')
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

        return [
            'title' => 'Koleksi Siap Diambil!',
            'message' => "Koleksi \"{$collection->title}\" sudah siap diambil.",
            'reservation_id' => $this->reservation->id,
            'collection_title' => $collection->title,
            'collection_id' => $collection->id,
            'branch_name' => $this->reservation->branch->name,
            'expiry_date' => $this->reservation->expiry_date->format('d/m/Y'),
            'type' => 'reservation_ready',
            'icon' => 'check-circle',
            'color' => 'success',
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => 'Koleksi Siap Diambil!',
            'message' => "Koleksi \"{$this->reservation->item->collection->title}\" sudah siap diambil.",
            'reservation_id' => $this->reservation->id,
            'collection_title' => $this->reservation->item->collection->title,
            'branch_name' => $this->reservation->branch->name,
            'expiry_date' => $this->reservation->expiry_date->format('d/m/Y'),
            'type' => 'reservation_ready',
            'icon' => 'check-circle',
            'color' => 'success',
        ]);
    }
}
