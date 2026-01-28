<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Member;
use App\Models\CollectionItem;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationService
{
    /**
     * Create a new reservation.
     */
    public function createReservation(array $data): Reservation
    {
        $member = Member::findOrFail($data['member_id']);
        $item = CollectionItem::with(['collection', 'branch'])->findOrFail($data['item_id']);

        // Validate member eligibility
        if (!$member->isEligibleForBorrowing()) {
            throw new \InvalidArgumentException('Anggota tidak eligible untuk reservasi. Status: ' . $member->status);
        }

        // Check if member already has active reservation for this collection
        $existingReservation = Reservation::where('member_id', $member->id)
            ->whereHas('item', function ($q) use ($item) {
                $q->where('collection_id', $item->collection_id);
            })
            ->whereIn('status', ['pending', 'ready'])
            ->first();

        if ($existingReservation) {
            throw new \InvalidArgumentException('Anggota sudah memiliki reservasi aktif untuk koleksi ini.');
        }

        // Check if member has too many pending reservations
        $pendingCount = Reservation::where('member_id', $member->id)
            ->whereIn('status', ['pending', 'ready'])
            ->count();

        if ($pendingCount >= 3) {
            throw new \InvalidArgumentException('Anggota sudah mencapai batas maksimum reservasi aktif (3).');
        }

        return DB::transaction(function () use ($data, $member, $item) {
            // Calculate expiry date (default 7 days)
            $expiresAt = now()->addDays(7);

            $reservation = Reservation::create([
                'member_id' => $member->id,
                'item_id' => $item->id,
                'reservation_date' => now(),
                'expires_at' => $expiresAt,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            return $reservation;
        });
    }

    /**
     * Mark reservation as ready for pickup.
     */
    public function markAsReady(Reservation $reservation): Reservation
    {
        if ($reservation->status !== 'pending') {
            throw new \InvalidArgumentException('Hanya reservasi dengan status pending yang dapat ditandai siap.');
        }

        // Check if item is available
        $item = $reservation->item;
        if ($item->status !== 'available') {
            throw new \InvalidArgumentException('Item belum tersedia. Status: ' . $item->status);
        }

        $reservation->update([
            'status' => 'ready',
            'ready_at' => now(),
            'expires_at' => now()->addDays(3), // Expires 3 days after ready
        ]);

        return $reservation->fresh();
    }

    /**
     * Fulfill a reservation (convert to loan).
     */
    public function fulfillReservation(Reservation $reservation, int $loanBranchId): Loan
    {
        if ($reservation->status !== 'ready') {
            throw new \InvalidArgumentException('Reservasi harus dalam status siap (ready).');
        }

        if ($reservation->expires_at->isPast()) {
            throw new \InvalidArgumentException('Reservasi telah kadaluarsa.');
        }

        // Use LoanService to create the loan
        $loanService = app(LoanService::class);

        $loan = $loanService->createLoan([
            'member_id' => $reservation->member_id,
            'item_id' => $reservation->item_id,
            'loan_branch_id' => $loanBranchId,
        ]);

        // Update reservation status
        $reservation->update([
            'status' => 'fulfilled',
            'fulfilled_at' => now(),
        ]);

        return $loan;
    }

    /**
     * Cancel a reservation.
     */
    public function cancelReservation(Reservation $reservation, ?string $reason = null): Reservation
    {
        if (!in_array($reservation->status, ['pending', 'ready'])) {
            throw new \InvalidArgumentException('Hanya reservasi aktif yang dapat dibatalkan.');
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'metadata->cancellation_reason' => $reason,
        ]);

        return $reservation->fresh();
    }

    /**
     * Expire pending/ready reservations.
     */
    public function expireReservations(): int
    {
        $expiredCount = Reservation::whereIn('status', ['pending', 'ready'])
            ->where('expires_at', '<', now())
            ->update([
                'status' => 'expired',
                'metadata->expired_at' => now(),
            ]);

        return $expiredCount;
    }

    /**
     * Auto-fulfill reservations when item becomes available.
     */
    public function processPendingReservations(int $itemId): ?Reservation
    {
        // Get the oldest pending reservation for this item's collection
        $reservation = Reservation::whereHas('item', function ($q) use ($itemId) {
            $q->where('id', $itemId);
        })
            ->where('status', 'pending')
            ->oldest('reservation_date')
            ->first();

        if ($reservation) {
            try {
                return $this->markAsReady($reservation);
            } catch (\Exception $e) {
                // Log error but continue
                logger()->error('Failed to process reservation: ' . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Get reservation queue position.
     */
    public function getQueuePosition(Reservation $reservation): int
    {
        return Reservation::where('status', 'pending')
            ->whereHas('item', function ($q) use ($reservation) {
                $q->where('collection_id', $reservation->item->collection_id);
            })
            ->where('reservation_date', '<=', $reservation->reservation_date)
            ->count();
    }

    /**
     * Get reservation statistics.
     */
    public function getReservationStatistics(?int $branchId = null): array
    {
        $query = Reservation::query();

        if ($branchId) {
            $query->whereHas('item', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $total = (clone $query)->count();
        $pending = (clone $query)->where('status', 'pending')->count();
        $ready = (clone $query)->where('status', 'ready')->count();
        $fulfilled = (clone $query)->where('status', 'fulfilled')->count();
        $cancelled = (clone $query)->where('status', 'cancelled')->count();
        $expired = (clone $query)->where('status', 'expired')->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'ready' => $ready,
            'fulfilled' => $fulfilled,
            'cancelled' => $cancelled,
            'expired' => $expired,
            'active' => $pending + $ready,
        ];
    }

    /**
     * Get reservations expiring soon.
     */
    public function getReservationsExpiringSoon(?int $branchId = null, int $days = 2): \Illuminate\Database\Eloquent\Collection
    {
        $query = Reservation::whereIn('status', ['pending', 'ready'])
            ->whereBetween('expires_at', [now(), now()->addDays($days)]);

        if ($branchId) {
            $query->whereHas('item', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query->with(['member', 'item.collection', 'item.branch'])->get();
    }

    /**
     * Get member active reservations.
     */
    public function getMemberActiveReservations(int $memberId): \Illuminate\Database\Eloquent\Collection
    {
        return Reservation::where('member_id', $memberId)
            ->whereIn('status', ['pending', 'ready'])
            ->with(['item.collection', 'item.branch'])
            ->oldest('reservation_date')
            ->get();
    }

    /**
     * Check if member can make new reservation.
     */
    public function canMemberReserve(Member $member): bool
    {
        // Check eligibility
        if (!$member->isEligibleForBorrowing()) {
            return false;
        }

        // Check active reservation count
        $activeCount = Reservation::where('member_id', $member->id)
            ->whereIn('status', ['pending', 'ready'])
            ->count();

        return $activeCount < 3;
    }
}
