<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Models\Reservation;
use App\Models\Member;
use App\Notifications\DueDateNotification;
use App\Notifications\OverdueNotification;
use App\Notifications\ReservationReadyNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automated notifications for due dates, overdues, and reservations';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Sending notifications...');

        // Send due date reminders (3 days before due)
        $this->sendDueDateReminders();

        // Send overdue notifications
        $this->sendOverdueNotifications();

        // Send reservation ready notifications
        $this->sendReservationReadyNotifications();

        $this->info('Notifications sent successfully.');

        return self::SUCCESS;
    }

    /**
     * Send due date reminders.
     */
    protected function sendDueDateReminders(): void
    {
        $dueDate = Carbon::now()->addDays(3)->endOfDay();

        $loans = Loan::whereNull('returned_at')
            ->where('status', 'active')
            ->whereDate('due_date', $dueDate)
            ->with(['item.collection', 'member.user'])
            ->get();

        foreach ($loans as $loan) {
            if ($loan->member && $loan->member->user) {
                $loan->member->user->notify(new DueDateNotification($loan, 3));
                $this->info("Sent due date reminder to: {$loan->member->user->name}");
            }
        }
    }

    /**
     * Send overdue notifications.
     */
    protected function sendOverdueNotifications(): void
    {
        $loans = Loan::whereNull('returned_at')
            ->where('status', 'overdue')
            ->whereDate('due_date', '<', Carbon::now()->subDays(7)->endOfDay())
            ->with(['item.collection', 'member.user'])
            ->get();

        foreach ($loans as $loan) {
            if ($loan->member && $loan->member->user) {
                $daysOverdue = Carbon::now()->diffInDays($loan->due_date);
                $fine = $loan->calculateFine();
                $loan->member->user->notify(new OverdueNotification($loan, $daysOverdue, $fine));
                $this->info("Sent overdue notification to: {$loan->member->user->name}");
            }
        }
    }

    /**
     * Send reservation ready notifications.
     */
    protected function sendReservationReadyNotifications(): void
    {
        $reservations = Reservation::where('status', 'ready')
            ->whereNull('notified_at')
            ->with(['item.collection.branch', 'member.user'])
            ->get();

        foreach ($reservations as $reservation) {
            if ($reservation->member && $reservation->member->user) {
                $pickupDeadline = Carbon::now()->addDays(3);
                $reservation->member->user->notify(new ReservationReadyNotification($reservation, $pickupDeadline));
                $reservation->update(['notified_at' => now()]);
                $this->info("Sent reservation ready notification to: {$reservation->member->user->name}");
            }
        }
    }
}
