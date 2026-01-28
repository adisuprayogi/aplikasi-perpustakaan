<?php

namespace App\Repositories;

use App\Models\Collection as CollectionModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class CollectionRepository extends BaseRepository
{
    public function __construct(CollectionModel $collection)
    {
        parent::__construct($collection);
    }

    /**
     * Search collections by title.
     */
    public function searchByTitle(string $title): EloquentCollection
    {
        return $this->query()
            ->where('title', 'like', '%' . $title . '%')
            ->with(['collectionType', 'authors'])
            ->get();
    }

    /**
     * Search by ISBN/ISSN.
     */
    public function searchByIsbnOrIssn(string $identifier): ?CollectionModel
    {
        return $this->query()
            ->where(function ($q) use ($identifier) {
                $q->where('isbn', $identifier)
                    ->orWhere('issn', $identifier);
            })
            ->first();
    }

    /**
     * Get collections by type.
     */
    public function getByCollectionType(int $collectionTypeId): EloquentCollection
    {
        return $this->query()
            ->where('collection_type_id', $collectionTypeId)
            ->with(['collectionType', 'authors'])
            ->get();
    }

    /**
     * Get collections by author.
     */
    public function getByAuthor(int $authorId): EloquentCollection
    {
        return $this->query()
            ->whereJsonContains('author_ids', $authorId)
            ->with(['collectionType', 'authors'])
            ->get();
    }

    /**
     * Get collections by subject.
     */
    public function getBySubject(int $subjectId): EloquentCollection
    {
        return $this->query()
            ->whereHas('subjects', function ($q) use ($subjectId) {
                $q->where('id', $subjectId);
            })
            ->with(['collectionType', 'authors', 'subjects'])
            ->get();
    }

    /**
     * Get collections by publisher.
     */
    public function getByPublisher(int $publisherId): EloquentCollection
    {
        return $this->query()
            ->where('publisher_id', $publisherId)
            ->with(['collectionType', 'authors', 'publisher'])
            ->get();
    }

    /**
     * Get collections with available items.
     */
    public function getWithAvailableItems(): EloquentCollection
    {
        return $this->query()
            ->where('available_items', '>', 0)
            ->with(['collectionType', 'authors'])
            ->get();
    }

    /**
     * Get popular collections (most borrowed).
     */
    public function getPopular(int $limit = 10): EloquentCollection
    {
        return $this->query()
            ->where('borrowed_items', '>', 0)
            ->orderBy('borrowed_items', 'desc')
            ->with(['collectionType', 'authors'])
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent collections.
     */
    public function getRecent(int $limit = 10): EloquentCollection
    {
        return $this->query()
            ->latest()
            ->with(['collectionType', 'authors'])
            ->limit($limit)
            ->get();
    }

    /**
     * Advanced search with filters.
     */
    public function advancedSearch(array $filters): LengthAwarePaginator
    {
        $query = $this->query()->with(['collectionType', 'authors', 'subjects', 'publisher']);

        if (isset($filters['q'])) {
            $searchTerm = $filters['q'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('isbn', 'like', '%' . $searchTerm . '%')
                    ->orWhere('issn', 'like', '%' . $searchTerm . '%')
                    ->orWhere('abstract', 'like', '%' . $searchTerm . '%');
            });
        }

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

        if (isset($filters['publisher_id'])) {
            $query->where('publisher_id', $filters['publisher_id']);
        }

        if (isset($filters['language'])) {
            $query->where('language', $filters['language']);
        }

        if (isset($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (isset($filters['from_year'])) {
            $query->where('year', '>=', $filters['from_year']);
        }

        if (isset($filters['to_year'])) {
            $query->where('year', '<=', $filters['to_year']);
        }

        if (isset($filters['available_only']) && $filters['available_only']) {
            $query->where('available_items', '>', 0);
        }

        $perPage = $filters['per_page'] ?? 15;

        return $query->latest()->paginate($perPage);
    }

    /**
     * Apply filters for pagination.
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['collection_type_id'])) {
            $query->where('collection_type_id', $filters['collection_type_id']);
        }

        if (isset($filters['gmd_id'])) {
            $query->where('gmd_id', $filters['gmd_id']);
        }

        if (isset($filters['language'])) {
            $query->where('language', $filters['language']);
        }

        if (isset($filters['available_only']) && $filters['available_only']) {
            $query->where('available_items', '>', 0);
        }

        return $query;
    }

    /**
     * Get collection statistics.
     */
    public function getStatistics(): array
    {
        $totalCollections = $this->query()->count();
        $totalItems = (clone $this->query())->sum('total_items');
        $availableItems = (clone $this->query())->sum('available_items');
        $borrowedItems = (clone $this->query())->sum('borrowed_items');

        return [
            'total_collections' => $totalCollections,
            'total_items' => $totalItems,
            'available_items' => $availableItems,
            'borrowed_items' => $borrowedItems,
        ];
    }

    /**
     * Get related collections by subject.
     */
    public function getRelatedCollections(CollectionModel $collection, int $limit = 6): EloquentCollection
    {
        $subjectIds = $collection->subjects->pluck('id');

        if ($subjectIds->isEmpty()) {
            return $this->query()
                ->where('id', '!=', $collection->id)
                ->where('collection_type_id', $collection->collection_type_id)
                ->with(['collectionType', 'authors'])
                ->limit($limit)
                ->get();
        }

        return $this->query()
            ->where('id', '!=', $collection->id)
            ->whereHas('subjects', function ($q) use ($subjectIds) {
                $q->whereIn('id', $subjectIds);
            })
            ->with(['collectionType', 'authors', 'subjects'])
            ->limit($limit)
            ->get();
    }
}
