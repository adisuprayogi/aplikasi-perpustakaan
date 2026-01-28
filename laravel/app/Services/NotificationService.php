<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Loan;
use App\Models\Member;
use App\Mail\ReservationReadyMail;
use App\Mail\ReservationExpiringMail;
use App\Mail\ReservationCancelledMail;
use App\Mail\LoanDueMail;
use App\Notifications\ReservationReadyNotification;
use App\Notifications\ReservationExpiringNotification;
use App\Notifications\LoanDueNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send notification when reservation is ready.
     */
    public function sendReservationReadyNotification(Reservation $reservation): bool
    {
        try {
            $member = $reservation->member;
            $collection = $reservation->item->collection;
            $branch = $reservation->branch;

            // Prepare notification data
            $notificationData = [
                'type' => 'reservation_ready',
                'reservation_id' => $reservation->id,
                'title' => 'Koleksi Siap Diambil!',
                'message' => "Halo {$member->name}, koleksi \"{$collection->title}\" yang Anda reservasi sudah siap diambil di {$branch->name}. Silakan ambil sebelum {$reservation->expiry_date->format('d/m/Y')}.",
                'member_id' => $member->id,
                'member_name' => $member->name,
                'member_email' => $member->email,
                'member_phone' => $member->phone,
                'collection_title' => $collection->title,
                'branch_name' => $branch->name,
                'expiry_date' => $reservation->expiry_date->format('d/m/Y'),
                'sent_at' => now()->toDateTimeString(),
            ];

            // Log notification
            Log::info('Reservation ready notification', $notificationData);

            // Store notification in metadata
            $reservation->update([
                'notification_sent' => true,
                'metadata->ready_notification_sent_at' => now()->toDateTimeString(),
            ]);

            // Send email notification
            if ($member->email && config('mail.default')) {
                Mail::to($member->email)->send(new ReservationReadyMail($reservation));
            }

            // Send in-app database notification
            $member->notify(new ReservationReadyNotification($reservation));

            // TODO: Implement SMS notification (if configured)

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send reservation ready notification', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send notification when reservation is expiring soon.
     */
    public function sendReservationExpiringNotification(Reservation $reservation): bool
    {
        try {
            $member = $reservation->member;
            $collection = $reservation->item->collection;
            $branch = $reservation->branch;

            $daysRemaining = $reservation->expiry_date->diffInDays(now());

            // Prepare notification data
            $notificationData = [
                'type' => 'reservation_expiring',
                'reservation_id' => $reservation->id,
                'title' => 'Reservasi Akan Kadaluarsa',
                'message' => "Halo {$member->name}, reservasi Anda untuk \"{$collection->title}\" akan kadaluarsa dalam {$daysRemaining} hari ({$reservation->expiry_date->format('d/m/Y')}). Silakan ambil koleksi di {$branch->name} sebelum kadaluarsa.",
                'member_id' => $member->id,
                'member_name' => $member->name,
                'member_email' => $member->email,
                'member_phone' => $member->phone,
                'collection_title' => $collection->title,
                'branch_name' => $branch->name,
                'expiry_date' => $reservation->expiry_date->format('d/m/Y'),
                'days_remaining' => $daysRemaining,
                'sent_at' => now()->toDateTimeString(),
            ];

            // Log notification
            Log::info('Reservation expiring notification', $notificationData);

            // Store notification in metadata
            $reservation->update([
                'metadata->expiring_notification_sent_at' => now()->toDateTimeString(),
            ]);

            // Send email notification (if configured)
            if ($member->email && config('mail.default')) {
                Mail::to($member->email)->send(new ReservationExpiringMail($reservation));
            }

            // Send in-app database notification
            $member->notify(new ReservationExpiringNotification($reservation));

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send reservation expiring notification', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send notification when reservation is cancelled.
     */
    public function sendReservationCancelledNotification(Reservation $reservation, string $reason = null): bool
    {
        try {
            $member = $reservation->member;
            $collection = $reservation->item->collection;

            // Prepare notification data
            $notificationData = [
                'type' => 'reservation_cancelled',
                'reservation_id' => $reservation->id,
                'title' => 'Reservasi Dibatalkan',
                'message' => "Halo {$member->name}, reservasi Anda untuk \"{$collection->title}\" telah dibatalkan." .
                    ($reason ? " Alasan: {$reason}" : ''),
                'member_id' => $member->id,
                'member_name' => $member->name,
                'member_email' => $member->email,
                'member_phone' => $member->phone,
                'collection_title' => $collection->title,
                'cancellation_reason' => $reason,
                'sent_at' => now()->toDateTimeString(),
            ];

            // Log notification
            Log::info('Reservation cancelled notification', $notificationData);

            // Send email notification (if configured)
            if ($member->email && config('mail.default')) {
                Mail::to($member->email)->send(new ReservationCancelledMail($reservation, $reason));
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send reservation cancelled notification', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send notification when loan is due soon.
     */
    public function sendLoanDueNotification(Loan $loan): bool
    {
        try {
            $member = $loan->member;
            $collection = $loan->item->collection;

            $daysUntilDue = $loan->due_date->diffInDays(now(), false);

            // Prepare notification data
            $notificationData = [
                'type' => 'loan_due_soon',
                'loan_id' => $loan->id,
                'title' => $daysUntilDue <= 0 ? 'Peminjaman Terlambat!' : 'Peminjaman Akan Jatuh Tempo',
                'message' => "Halo {$member->name}, peminjaman Anda untuk \"{$collection->title}\" " .
                    ($daysUntilDue <= 0
                        ? "sudah terlambat " . abs($daysUntilDue) . " hari."
                        : "akan jatuh tempo dalam {$daysUntilDue} hari (" . $loan->due_date->format('d/m/Y') . ")."),
                'member_id' => $member->id,
                'member_name' => $member->name,
                'member_email' => $member->email,
                'member_phone' => $member->phone,
                'collection_title' => $collection->title,
                'due_date' => $loan->due_date->format('d/m/Y'),
                'days_until_due' => $daysUntilDue,
                'sent_at' => now()->toDateTimeString(),
            ];

            // Log notification
            Log::info('Loan due notification', $notificationData);

            // Store in metadata
            $loan->update([
                'metadata->due_notification_sent_at' => now()->toDateTimeString(),
            ]);

            // Send email notification
            if ($member->email && config('mail.default')) {
                Mail::to($member->email)->send(new LoanDueMail($loan));
            }

            // Send in-app database notification
            $member->notify(new LoanDueNotification($loan));

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send loan due notification', [
                'loan_id' => $loan->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send bulk notifications for expiring reservations.
     * This is meant to be run by a scheduled task.
     */
    public function sendExpiringReservationsNotifications(int $daysThreshold = 2): int
    {
        $expiringReservations = Reservation::where('status', 'pending')
            ->whereBetween('expiry_date', [
                now()->startOfDay(),
                now()->addDays($daysThreshold)->endOfDay()
            ])
            ->where(function ($query) {
                $query->whereNull('metadata->expiring_notification_sent_at')
                    ->orWhere('metadata->expiring_notification_sent_at', '<', now()->subDay()->toDateTimeString());
            })
            ->with(['member', 'item.collection', 'branch'])
            ->get();

        $sentCount = 0;

        foreach ($expiringReservations as $reservation) {
            if ($this->sendReservationExpiringNotification($reservation)) {
                $sentCount++;
            }
        }

        return $sentCount;
    }

    /**
     * Send bulk notifications for overdue loans.
     * This is meant to be run by a scheduled task.
     */
    public function sendOverdueLoansNotifications(): int
    {
        $overdueLoans = Loan::where('status', 'active')
            ->where('due_date', '<', now())
            ->where(function ($query) {
                $query->whereNull('metadata->due_notification_sent_at')
                    ->orWhere('metadata->due_notification_sent_at', '<', now()->subDay()->toDateTimeString());
            })
            ->with(['member', 'item.collection'])
            ->get();

        $sentCount = 0;

        foreach ($overdueLoans as $loan) {
            if ($this->sendLoanDueNotification($loan)) {
                $sentCount++;
            }
        }

        return $sentCount;
    }

    /**
     * Mark expired reservations.
     * This is meant to be run by a scheduled task.
     */
    public function markExpiredReservations(): int
    {
        $expiredCount = Reservation::where('status', 'pending')
            ->where('expiry_date', '<', now())
            ->update([
                'status' => 'expired',
                'metadata->expired_at' => now()->toDateTimeString(),
            ]);

        return $expiredCount;
    }
}
