<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\CollectionItem;
use App\Repositories\ItemRepository;
use Illuminate\Support\Facades\DB;

class CollectionItemService
{
    protected ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Create a new collection item.
     */
    public function createItem(Collection $collection, array $data): CollectionItem
    {
        return DB::transaction(function () use ($collection, $data) {
            // Generate barcode if not provided
            if (empty($data['barcode'])) {
                $data['barcode'] = $this->itemRepository->generateBarcode($collection->id);
            }

            // Set collection_id
            $data['collection_id'] = $collection->id;

            $item = $this->itemRepository->create($data);

            // Log activity if needed
            // activity()->performedOn($item)->causedBy(auth()->user())->log('Collection item created');

            return $item;
        });
    }

    /**
     * Create multiple items for a collection.
     */
    public function createMultipleItems(Collection $collection, array $itemsData): array
    {
        return DB::transaction(function () use ($collection, $itemsData) {
            $createdItems = [];

            foreach ($itemsData as $data) {
                $data['collection_id'] = $collection->id;

                // Generate barcode if not provided
                if (empty($data['barcode'])) {
                    $data['barcode'] = $this->itemRepository->generateBarcode($collection->id);
                }

                $item = $this->itemRepository->create($data);
                $createdItems[] = $item;
            }

            return $createdItems;
        });
    }

    /**
     * Update an existing collection item.
     */
    public function updateItem(CollectionItem $item, array $data): CollectionItem
    {
        return DB::transaction(function () use ($item, $data) {
            $item = $this->itemRepository->update($item, $data);

            // Log activity if needed
            // activity()->performedOn($item)->causedBy(auth()->user())->log('Collection item updated');

            return $item;
        });
    }

    /**
     * Delete a collection item.
     */
    public function deleteItem(CollectionItem $item): bool
    {
        return DB::transaction(function () use ($item) {
            // Check if item is borrowed
            if ($item->status === 'borrowed') {
                throw new \Exception('Cannot delete borrowed item.');
            }

            // Check if item has active reservations
            if ($item->reservations()->where('status', 'pending')->exists()) {
                throw new \Exception('Cannot delete item with active reservations.');
            }

            $result = $this->itemRepository->delete($item);

            // Log activity if needed
            // activity()->performedOn($item)->causedBy(auth()->user())->log('Collection item deleted');

            return $result;
        });
    }

    /**
     * Update item status.
     */
    public function updateItemStatus(CollectionItem $item, string $status): CollectionItem
    {
        return DB::transaction(function () use ($item, $status) {
            // Validate status transition
            $this->validateStatusTransition($item->status, $status);

            $item = $this->itemRepository->updateStatus($item, $status);

            // Log activity if needed
            // activity()->performedOn($item)->causedBy(auth()->user())->log("Item status changed to {$status}");

            return $item;
        });
    }

    /**
     * Transfer item to another branch.
     */
    public function transferToBranch(CollectionItem $item, int $branchId): CollectionItem
    {
        return DB::transaction(function () use ($item, $branchId) {
            // Check if item is borrowed
            if ($item->status === 'borrowed') {
                throw new \Exception('Cannot transfer borrowed item.');
            }

            $item = $this->itemRepository->transferToBranch($item, $branchId);

            // Log activity if needed
            // activity()->performedOn($item)->causedBy(auth()->user())->log('Item transferred to branch');

            return $item;
        });
    }

    /**
     * Mark item as inventoried.
     */
    public function markAsInventoried(CollectionItem $item, ?string $notes = null): CollectionItem
    {
        return $this->itemRepository->markAsInventoried($item, $notes);
    }

    /**
     * Get available items for loan.
     */
    public function getAvailableItems(?int $branchId = null): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->itemRepository->getAvailable();
    }

    /**
     * Get borrowed items.
     */
    public function getBorrowedItems(): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->itemRepository->getBorrowed();
    }

    /**
     * Get items by collection.
     */
    public function getItemsByCollection(int $collectionId): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->itemRepository->getByCollection($collectionId);
    }

    /**
     * Get available items by collection.
     */
    public function getAvailableItemsByCollection(int $collectionId): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->itemRepository->getAvailableByCollection($collectionId);
    }

    /**
     * Search items for loan (available items only).
     */
    public function searchAvailableForLoan(string $search, ?int $branchId = null): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->itemRepository->searchAvailableForLoan($search, $branchId);
    }

    /**
     * Get items needing inventory check.
     */
    public function getItemsNeedingInventoryCheck(int $daysSinceCheck = 365): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->itemRepository->getItemsNeedingInventoryCheck($daysSinceCheck);
    }

    /**
     * Get item statistics.
     */
    public function getItemStatistics(?int $branchId = null): array
    {
        return $this->itemRepository->getStatistics($branchId);
    }

    /**
     * Validate status transition.
     */
    protected function validateStatusTransition(string $fromStatus, string $toStatus): void
    {
        $validTransitions = [
            'available' => ['borrowed', 'reserved', 'lost', 'damaged'],
            'borrowed' => ['available', 'lost', 'damaged'],
            'reserved' => ['available', 'borrowed'],
            'lost' => ['available'],
            'damaged' => ['available'],
        ];

        if (!isset($validTransitions[$fromStatus]) || !in_array($toStatus, $validTransitions[$fromStatus])) {
            throw new \Exception("Invalid status transition from {$fromStatus} to {$toStatus}");
        }
    }

    /**
     * Check if item can be deleted.
     */
    public function canDeleteItem(CollectionItem $item): bool
    {
        return $item->status !== 'borrowed'
            && !$item->reservations()->where('status', 'pending')->exists();
    }

    /**
     * Get status label.
     */
    public function getStatusLabel(string $status): string
    {
        return match($status) {
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'reserved' => 'Direservasi',
            'lost' => 'Hilang',
            'damaged' => 'Rusak',
            default => ucfirst($status),
        };
    }

    /**
     * Get condition label.
     */
    public function getConditionLabel(string $condition): string
    {
        return match($condition) {
            'good' => 'Baik',
            'fair' => 'Cukup',
            'poor' => 'Buruk',
            default => ucfirst($condition),
        };
    }

    /**
     * Get all item statuses.
     */
    public function getItemStatuses(): array
    {
        return [
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'reserved' => 'Direservasi',
            'lost' => 'Hilang',
            'damaged' => 'Rusak',
        ];
    }

    /**
     * Get all item conditions.
     */
    public function getItemConditions(): array
    {
        return [
            'good' => 'Baik',
            'fair' => 'Cukup',
            'poor' => 'Buruk',
        ];
    }
}
