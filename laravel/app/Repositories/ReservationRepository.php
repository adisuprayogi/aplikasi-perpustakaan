<?php

namespace App\Repositories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ReservationRepository extends BaseRepository
{
    public function __construct(Reservation $reservation)
    {
        parent::__construct($reservation);
    }

    /**
     * Get pending reservations.
     */
    public function getPending(): Collection
    {
        return $this->query()
            ->where('status', 'pending')
            ->with(['member', 'item.collection', 'item.branch'])
            ->oldest('reservation_date')
            ->get();
    }

    /**
     * Get ready reservations.
     */
    public function getReady(): Collection
    {
        return $this->query()
            ->where('status', 'ready')
            ->with(['member', 'item.collection', 'item.branch'])
            ->oldest('ready_at')
            ->get();
    }

    /**
     * Get active reservations (pending + ready).
     */
    public function getActive(): Collection
    {
        return $this->query()
            ->whereIn('status', ['pending', 'ready'])
            ->with(['member', 'item.collection', 'item.branch'])
            ->oldest('reservation_date')
            ->get();
    }

    /**
     * Get reservations for a member.
     */
    public function getForMember(int $memberId): Collection
    {
        return $this->query()
            ->where('member_id', $memberId)
            ->with(['item.collection', 'item.branch'])
            ->latest('reservation_date')
            ->get();
    }

    /**
     * Get active reservations for a member.
     */
    public function getActiveForMember(int $memberId): Collection
    {
        return $this->query()
            ->where('member_id', $memberId)
            ->whereIn('status', ['pending', 'ready'])
            ->with(['item.collection', 'item.branch'])
            ->oldest('reservation_date')
            ->get();
    }

    /**
     * Get reservations for a collection.
     */
    public function getForCollection(int $collectionId): Collection
    {
        return $this->query()
            ->whereHas('item', function ($q) use ($collectionId) {
                $q->where('collection_id', $collectionId);
            })
            ->whereIn('status', ['pending', 'ready'])
            ->with(['member', 'item'])
            ->oldest('reservation_date')
            ->get();
    }

    /**
     * Get reservations expiring soon.
     */
    public function getExpiringSoon(int $days = 2): Collection
    {
        return $this->query()
            ->whereIn('status', ['pending', 'ready'])
            ->whereBetween('expires_at', [now(), now()->addDays($days)])
            ->with(['member', 'item.collection', 'item.branch'])
            ->oldest('expires_at')
            ->get();
    }

    /**
     * Get expired reservations.
     */
    public function getExpired(): Collection
    {
        return $this->query()
            ->whereIn('status', ['pending', 'ready'])
            ->where('expires_at', '<', now())
            ->with(['member', 'item.collection'])
            ->get();
    }

    /**
     * Get queue position for a reservation.
     */
    public function getQueuePosition(Reservation $reservation): int
    {
        return $this->query()
            ->where('status', 'pending')
            ->whereHas('item', function ($q) use ($reservation) {
                $q->where('collection_id', $reservation->item->collection_id);
            })
            ->where('reservation_date', '<=', $reservation->reservation_date)
            ->count();
    }

    /**
     * Check if member has reservation for collection.
     */
    public function memberHasReservationForCollection(int $memberId, int $collectionId): bool
    {
        return $this->query()
            ->where('member_id', $memberId)
            ->whereHas('item', function ($q) use ($collectionId) {
                $q->where('collection_id', $collectionId);
            })
            ->whereIn('status', ['pending', 'ready'])
            ->exists();
    }

    /**
     * Apply filters for pagination.
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (isset($filters['branch_id'])) {
            $query->whereHas('item', function ($q) use ($filters) {
                $q->where('branch_id', $filters['branch_id']);
            });
        }

        if (isset($filters['from_date'])) {
            $query->where('reservation_date', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('reservation_date', '<=', $filters['to_date']);
        }

        return $query;
    }

    /**
     * Get reservation statistics.
     */
    public function getStatistics(?int $branchId = null): array
    {
        $query = $this->model->newQuery();

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
     * Mark expired reservations.
     */
    public function markExpired(): int
    {
        return $this->query()
            ->whereIn('status', ['pending', 'ready'])
            ->where('expires_at', '<', now())
            ->update([
                'status' => 'expired',
                'metadata->expired_at' => now(),
            ]);
    }

    /**
     * Get oldest pending reservation for an item.
     */
    public function getOldestPendingForItem(int $itemId): ?Reservation
    {
        return $this->query()
            ->where('item_id', $itemId)
            ->where('status', 'pending')
            ->oldest('reservation_date')
            ->first();
    }
}
