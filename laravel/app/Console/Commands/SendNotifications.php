<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send {--type=all : Type of notifications to send (all|expiring|overdue|mark-expired)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for expiring reservations and overdue loans';

    protected NotificationService $notificationService;

    /**
     * Create a new command instance.
     */
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');

        $this->info('Starting notification task...');
        $this->info('Type: ' . $type);
        $this->newLine();

        $totalSent = 0;

        switch ($type) {
            case 'expiring':
                $sent = $this->sendExpiringReservationNotifications();
                $totalSent += $sent;
                $this->info("✓ Sent {$sent} expiring reservation notifications");
                break;

            case 'overdue':
                $sent = $this->sendOverdueLoanNotifications();
                $totalSent += $sent;
                $this->info("✓ Sent {$sent} overdue loan notifications");
                break;

            case 'mark-expired':
                $count = $this->markExpiredReservations();
                $this->info("✓ Marked {$count} reservations as expired");
                break;

            case 'all':
            default:
                $sent = $this->sendExpiringReservationNotifications();
                $totalSent += $sent;
                $this->info("✓ Sent {$sent} expiring reservation notifications");

                $sent = $this->sendOverdueLoanNotifications();
                $totalSent += $sent;
                $this->info("✓ Sent {$sent} overdue loan notifications");

                $count = $this->markExpiredReservations();
                $this->info("✓ Marked {$count} reservations as expired");
                break;
        }

        $this->newLine();
        $this->info("Task completed. Total notifications sent: {$totalSent}");

        Log::info('Notification task completed', [
            'type' => $type,
            'total_sent' => $totalSent,
            'timestamp' => now()->toDateTimeString(),
        ]);

        return Command::SUCCESS;
    }

    /**
     * Send expiring reservation notifications.
     */
    protected function sendExpiringReservationNotifications(): int
    {
        $this->info('Checking for expiring reservations...');

        $sent = $this->notificationService->sendExpiringReservationsNotifications(2);

        return $sent;
    }

    /**
     * Send overdue loan notifications.
     */
    protected function sendOverdueLoanNotifications(): int
    {
        $this->info('Checking for overdue loans...');

        $sent = $this->notificationService->sendOverdueLoansNotifications();

        return $sent;
    }

    /**
     * Mark expired reservations.
     */
    protected function markExpiredReservations(): int
    {
        $this->info('Checking for expired reservations...');

        $count = $this->notificationService->markExpiredReservations();

        return $count;
    }
}
