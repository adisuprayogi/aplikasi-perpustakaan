<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\Author;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CollectionService
{
    /**
     * Create a new collection.
     */
    public function createCollection(array $data): Collection
    {
        return DB::transaction(function () use ($data) {
            // Handle authors
            if (isset($data['authors']) && is_array($data['authors'])) {
                $authorIds = [];
                foreach ($data['authors'] as $authorData) {
                    $author = Author::firstOrCreate(
                        ['name' => $authorData['name']],
                        [
                            'bio' => $authorData['bio'] ?? null,
                            'birth_year' => $authorData['birth_year'] ?? null,
                        ]
                    );
                    $authorIds[] = $author->id;
                }
                $data['author_ids'] = $authorIds;
                unset($data['authors']);
            }

            // Initialize statistics
            $data['total_items'] = $data['total_items'] ?? 0;
            $data['available_items'] = $data['total_items'];
            $data['borrowed_items'] = 0;

            $collection = Collection::create($data);

            // Handle subjects
            if (isset($data['subject_ids']) && is_array($data['subject_ids'])) {
                $collection->subjects()->sync($data['subject_ids']);
            }

            return $collection;
        });
    }

    /**
     * Update a collection.
     */
    public function updateCollection(Collection $collection, array $data): Collection
    {
        return DB::transaction(function () use ($collection, $data) {
            // Handle authors
            if (isset($data['authors']) && is_array($data['authors'])) {
                $authorIds = [];
                foreach ($data['authors'] as $authorData) {
                    if (is_numeric($authorData)) {
                        $authorIds[] = $authorData;
                    } else {
                        $author = Author::firstOrCreate(
                            ['name' => $authorData['name'] ?? $authorData],
                            [
                                'bio' => $authorData['bio'] ?? null,
                                'birth_year' => $authorData['birth_year'] ?? null,
                            ]
                        );
                        $authorIds[] = $author->id;
                    }
                }
                $data['author_ids'] = $authorIds;
                unset($data['authors']);
            }

            // Handle cover image
            if (isset($data['cover_image']) && is_file($data['cover_image'])) {
                // Delete old image
                if ($collection->cover_image) {
                    Storage::disk('public')->delete($collection->cover_image);
                }
                // Store new image
                $path = $data['cover_image']->store('collections/covers', 'public');
                $data['cover_image'] = $path;
            }

            $collection->update($data);

            // Handle subjects
            if (isset($data['subject_ids']) && is_array($data['subject_ids'])) {
                $collection->subjects()->sync($data['subject_ids']);
            }

            return $collection->fresh();
        });
    }

    /**
     * Delete a collection.
     */
    public function deleteCollection(Collection $collection): bool
    {
        return DB::transaction(function () use ($collection) {
            // Check if collection has items
            if ($collection->items()->count() > 0) {
                throw new \InvalidArgumentException('Tidak dapat menghapus koleksi yang memiliki item. Hapus item terlebih dahulu.');
            }

            // Delete cover image
            if ($collection->cover_image) {
                Storage::disk('public')->delete($collection->cover_image);
            }

            // Detach subjects
            $collection->subjects()->detach();

            return $collection->delete();
        });
    }

    /**
     * Add item to collection.
     */
    public function addItem(Collection $collection, array $data): CollectionItem
    {
        return DB::transaction(function () use ($collection, $data) {
            // Generate barcode if not provided
            if (!isset($data['barcode'])) {
                $data['barcode'] = $this->generateBarcode($collection);
            }

            // Set collection_id
            $data['collection_id'] = $collection->id;
            $data['status'] = $data['status'] ?? 'available';

            $item = CollectionItem::create($data);

            // Update collection statistics
            $collection->increment('total_items');
            if ($data['status'] === 'available') {
                $collection->increment('available_items');
            }

            return $item;
        });
    }

    /**
     * Remove item from collection.
     */
    public function removeItem(CollectionItem $item): bool
    {
        return DB::transaction(function () use ($item) {
            $collection = $item->collection;

            // Check if item is borrowed
            if ($item->status === 'borrowed') {
                throw new \InvalidArgumentException('Tidak dapat menghapus item yang sedang dipinjam.');
            }

            $item->delete();

            // Update collection statistics
            $collection->decrement('total_items');
            if ($item->status === 'available') {
                $collection->decrement('available_items');
            }

            return true;
        });
    }

    /**
     * Generate a unique barcode for collection item.
     */
    public function generateBarcode(Collection $collection): string
    {
        $prefix = 'CLN';
        $year = now()->format('Y');

        // Get last item for this collection
        $lastItem = CollectionItem::where('collection_id', $collection->id)
            ->orderBy('barcode', 'desc')
            ->first();

        if ($lastItem) {
            // Extract sequence from last barcode
            $parts = explode('-', $lastItem->barcode);
            $lastSequence = (int) end($parts);
            $newSequence = str_pad($lastSequence + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '000001';
        }

        return "{$prefix}{$year}-{$collection->id}-{$newSequence}";
    }

    /**
     * Update item statistics.
     */
    public function updateItemStatistics(Collection $collection): Collection
    {
        $totalItems = $collection->items()->count();
        $availableItems = $collection->items()->where('status', 'available')->count();
        $borrowedItems = $collection->items()->where('status', 'borrowed')->count();

        $collection->update([
            'total_items' => $totalItems,
            'available_items' => $availableItems,
            'borrowed_items' => $borrowedItems,
        ]);

        return $collection->fresh();
    }

    /**
     * Get popular collections.
     */
    public function getPopularCollections(int $limit = 10, ?int $branchId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Collection::with(['collectionType', 'authors'])
            ->where('total_items', '>', 0)
            ->orderBy('borrowed_items', 'desc');

        if ($branchId) {
            $query->whereHas('items', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get recent collections.
     */
    public function getRecentCollections(int $limit = 10, ?int $branchId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Collection::with(['collectionType', 'authors'])
            ->latest();

        if ($branchId) {
            $query->whereHas('items', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query->limit($limit)->get();
    }

    /**
     * Search collections.
     */
    public function searchCollections(string $search, ?array $filters = null): \Illuminate\Database\Eloquent\Builder
    {
        $query = Collection::with(['collectionType', 'authors', 'subjects', 'publisher']);

        // Search in title, isbn, issn
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('isbn', 'like', '%' . $search . '%')
                ->orWhere('issn', 'like', '%' . $search . '%');
        });

        // Apply filters
        if ($filters) {
            if (isset($filters['collection_type_id'])) {
                $query->where('collection_type_id', $filters['collection_type_id']);
            }

            if (isset($filters['gmd_id'])) {
                $query->where('gmd_id', $filters['gmd_id']);
            }

            if (isset($filters['author_id'])) {
                $query->whereJsonContains('author_ids', $filters['author_id']);
            }

            if (isset($filters['subject_id'])) {
                $query->whereHas('subjects', function ($q) use ($filters) {
                    $q->where('id', $filters['subject_id']);
                });
            }

            if (isset($filters['language'])) {
                $query->where('language', $filters['language']);
            }

            if (isset($filters['year'])) {
                $query->where('year', $filters['year']);
            }

            if (isset($filters['available_only']) && $filters['available_only']) {
                $query->where('available_items', '>', 0);
            }
        }

        return $query;
    }

    /**
     * Get collection statistics.
     */
    public function getCollectionStatistics(?int $branchId = null): array
    {
        $query = Collection::query();

        if ($branchId) {
            $query->whereHas('items', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $totalCollections = (clone $query)->count();
        $totalItems = (clone $query)->sum('total_items');
        $availableItems = (clone $query)->sum('available_items');
        $borrowedItems = (clone $query)->sum('borrowed_items');

        return [
            'total_collections' => $totalCollections,
            'total_items' => $totalItems,
            'available_items' => $availableItems,
            'borrowed_items' => $borrowedItems,
        ];
    }
}
