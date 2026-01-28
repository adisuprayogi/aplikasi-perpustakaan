<?php

namespace App\Repositories;

use App\Models\CollectionItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ItemRepository extends BaseRepository
{
    public function __construct(CollectionItem $item)
    {
        parent::__construct($item);
    }

    /**
     * Find item by barcode.
     */
    public function findByBarcode(string $barcode): ?CollectionItem
    {
        return $this->findBy('barcode', $barcode);
    }

    /**
     * Get available items.
     */
    public function getAvailable(): Collection
    {
        return $this->query()
            ->where('status', 'available')
            ->with(['collection', 'collection.collectionType', 'branch'])
            ->get();
    }

    /**
     * Get borrowed items.
     */
    public function getBorrowed(): Collection
    {
        return $this->query()
            ->where('status', 'borrowed')
            ->with(['collection', 'branch'])
            ->get();
    }

     /**
     * Get items by collection.
     */
    public function getByCollection(int $collectionId): Collection
    {
        return $this->query()
            ->where('collection_id', $collectionId)
            ->with(['branch'])
            ->get();
    }

    /**
     * Get available items by collection.
     */
    public function getAvailableByCollection(int $collectionId): Collection
    {
        return $this->query()
            ->where('collection_id', $collectionId)
            ->where('status', 'available')
            ->with(['branch'])
            ->get();
    }

    /**
     * Get items by branch.
     */
    public function getByBranch(int $branchId): Collection
    {
        return $this->query()
            ->where('branch_id', $branchId)
            ->with(['collection', 'collection.collectionType'])
            ->get();
    }

    /**
     * Get available items by branch.
     */
    public function getAvailableByBranch(int $branchId): Collection
    {
        return $this->query()
            ->where('branch_id', $branchId)
            ->where('status', 'available')
            ->with(['collection', 'collection.collectionType'])
            ->get();
    }

    /**
     * Search items by barcode.
     */
    public function searchByBarcode(string $barcode): Collection
    {
        return $this->query()
            ->where('barcode', 'like', '%' . $barcode . '%')
            ->with(['collection', 'collection.collectionType', 'branch'])
            ->limit(20)
            ->get();
    }

    /**
     * Search items by collection title.
     */
    public function searchByCollectionTitle(string $title): Collection
    {
        return $this->query()
            ->whereHas('collection', function ($q) use ($title) {
                $q->where('title', 'like', '%' . $title . '%');
            })
            ->with(['collection', 'branch'])
            ->limit(50)
            ->get();
    }

    /**
     * Apply filters for pagination.
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['collection_id'])) {
            $query->where('collection_id', $filters['collection_id']);
        }

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['call_number'])) {
            $query->where('call_number', 'like', '%' . $filters['call_number'] . '%');
        }

        if (isset($filters['barcode'])) {
            $query->where('barcode', 'like', '%' . $filters['barcode'] . '%');
        }

        return $query;
    }

    /**
     * Get item statistics.
     */
    public function getStatistics(?int $branchId = null): array
    {
        $query = $this->model->newQuery();

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $total = (clone $query)->count();
        $available = (clone $query)->where('status', 'available')->count();
        $borrowed = (clone $query)->where('status', 'borrowed')->count();
        $lost = (clone $query)->where('status', 'lost')->count();
        $damaged = (clone $query)->where('status', 'damaged')->count();

        return [
            'total' => $total,
            'available' => $available,
            'borrowed' => $borrowed,
            'lost' => $lost,
            'damaged' => $damaged,
        ];
    }

    /**
     * Update item status.
     */
    public function updateStatus(CollectionItem $item, string $status): CollectionItem
    {
        $item->update(['status' => $status]);
        return $item->fresh();
    }

    /**
     * Transfer item to another branch.
     */
    public function transferToBranch(CollectionItem $item, int $branchId): CollectionItem
    {
        $item->update(['branch_id' => $branchId]);
        return $item->fresh();
    }

    /**
     * Get items needing inventory check.
     */
    public function getItemsNeedingInventoryCheck(int $daysSinceCheck = 365): Collection
    {
        return $this->query()
            ->where(function ($q) use ($daysSinceCheck) {
                $q->whereNull('metadata->last_inventory_date')
                    ->orWhere('metadata->last_inventory_date', '<=', now()->subDays($daysSinceCheck));
            })
            ->with(['collection', 'branch'])
            ->get();
    }

    /**
     * Mark item as inventoried.
     */
    public function markAsInventoried(CollectionItem $item, ?string $notes = null): CollectionItem
    {
        $metadata = $item->metadata ?? [];
        $metadata['last_inventory_date'] = now()->toDateString();
        $metadata['last_inventory_notes'] = $notes;

        $item->update(['metadata' => $metadata]);
        return $item->fresh();
    }

    /**
     * Search available items for loan.
     */
    public function searchAvailableForLoan(string $search, ?int $branchId = null): Collection
    {
        $query = $this->query()
            ->where('status', 'available')
            ->where(function ($q) use ($search) {
                $q->where('barcode', 'like', '%' . $search . '%')
                    ->orWhereHas('collection', function ($c) use ($search) {
                        $c->where('title', 'like', '%' . $search . '%');
                    });
            })
            ->with(['collection', 'collection.collectionType', 'branch']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->limit(20)->get();
    }

    /**
     * Generate unique barcode for item.
     */
    public function generateBarcode(int $collectionId): string
    {
        $prefix = 'ITEM';
        $year = now()->format('Y');

        $lastItem = $this->query()
            ->where('collection_id', $collectionId)
            ->orderBy('barcode', 'desc')
            ->first();

        if ($lastItem) {
            $parts = explode('-', $lastItem->barcode);
            $lastSequence = (int) end($parts);
            $newSequence = str_pad($lastSequence + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '000001';
        }

        return "{$prefix}{$year}-{$collectionId}-{$newSequence}";
    }
}
