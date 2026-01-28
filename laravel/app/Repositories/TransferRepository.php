<?php

namespace App\Repositories;

use App\Models\ItemTransfer;
use App\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class TransferRepository implements RepositoryInterface
{
    /**
     * Get all transfers.
     */
    public function all(): Collection
    {
        return ItemTransfer::with(['item.collection', 'fromBranch', 'toBranch', 'requestedBy', 'shippedBy', 'receivedBy'])->get();
    }

    /**
     * Get all transfers with filters and pagination.
     */
    public function listWithFilters(array $filters = []): LengthAwarePaginator
    {
        $query = ItemTransfer::with(['item.collection', 'fromBranch', 'toBranch', 'requestedBy', 'shippedBy', 'receivedBy']);

        // Filter by status
        if (isset($filters['status']) && in_array($filters['status'], ['pending', 'shipped', 'received', 'cancelled'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by from branch
        if (isset($filters['from_branch_id'])) {
            $query->where('from_branch_id', $filters['from_branch_id']);
        }

        // Filter by to branch
        if (isset($filters['to_branch_id'])) {
            $query->where('to_branch_id', $filters['to_branch_id']);
        }

        // Filter by branch (either from or to)
        if (isset($filters['branch_id'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('from_branch_id', $filters['branch_id'])
                    ->orWhere('to_branch_id', $filters['branch_id']);
            });
        }

        // Search by item barcode or title
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('item', function (Builder $q) use ($search) {
                $q->where('barcode', 'like', "%{$search}%")
                    ->orWhereHas('collection', function (Builder $sq) use ($search) {
                        $sq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Order by requested_at desc
        $query->orderBy('requested_at', 'desc');

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Find transfer by ID.
     */
    public function find(int $id): ?ItemTransfer
    {
        return ItemTransfer::with(['item.collection', 'fromBranch', 'toBranch', 'requestedBy', 'shippedBy', 'receivedBy'])->find($id);
    }

    /**
     * Create new transfer.
     */
    public function create(array $data): ItemTransfer
    {
        return ItemTransfer::create($data);
    }

    /**
     * Update transfer.
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model->fresh();
    }

    /**
     * Delete transfer.
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Get pending transfers count.
     */
    public function getPendingCount(?int $branchId = null): int
    {
        $query = ItemTransfer::pending();
        if ($branchId) {
            $query->where('to_branch_id', $branchId);
        }
        return $query->count();
    }

    /**
     * Get shipped transfers count (not yet received).
     */
    public function getShippedCount(?int $branchId = null): int
    {
        $query = ItemTransfer::shipped();
        if ($branchId) {
            $query->where('to_branch_id', $branchId);
        }
        return $query->count();
    }

    /**
     * Get transfers for a specific item.
     */
    public function getByItemId(int $itemId): Collection
    {
        return ItemTransfer::with(['fromBranch', 'toBranch'])
            ->where('item_id', $itemId)
            ->orderBy('requested_at', 'desc')
            ->get();
    }

    /**
     * Check if item has pending transfer.
     */
    public function hasPendingTransfer(int $itemId): bool
    {
        return ItemTransfer::where('item_id', $itemId)
            ->pending()
            ->exists();
    }

    /**
     * Check if item has shipped transfer (not yet received).
     */
    public function hasShippedTransfer(int $itemId): bool
    {
        return ItemTransfer::where('item_id', $itemId)
            ->shipped()
            ->exists();
    }

    // ========== RepositoryInterface implementation ==========

    /**
     * {@inheritdoc}
     */
    public function findOrFail(int $id): Model
    {
        return ItemTransfer::with(['item.collection', 'fromBranch', 'toBranch', 'requestedBy', 'shippedBy', 'receivedBy'])->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->listWithFilters($filters);
    }

    /**
     * {@inheritdoc}
     */
    public function query(): Builder
    {
        return ItemTransfer::query();
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(string $field, mixed $value): ?Model
    {
        return ItemTransfer::where($field, $value)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findWhere(string $field, mixed $value): Collection
    {
        return ItemTransfer::where($field, $value)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function with(array $relations): self
    {
        // This is typically used for query chaining, not stored
        // For this implementation, we'll just return $this
        return $this;
    }
}
