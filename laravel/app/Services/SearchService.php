<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\Author;
use App\Models\Subject;
use App\Models\Publisher;
use App\Models\Gmd;
use App\Models\CollectionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    /**
     * Simple search collections.
     */
    public function search(string $query, int $perPage = 20): LengthAwarePaginator
    {
        return Collection::with(['subjects', 'publisher', 'collectionType', 'gmd'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('isbn', 'like', '%' . $query . '%')
                    ->orWhere('issn', 'like', '%' . $query . '%')
                    ->orWhere('abstract', 'like', '%' . $query . '%');
            })
            ->paginate($perPage);
    }

    /**
     * Advanced search with filters.
     */
    public function advancedSearch(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Collection::with(['subjects', 'publisher', 'collectionType', 'gmd']);

        // Search query
        if (!empty($filters['q'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['q'] . '%')
                    ->orWhere('isbn', 'like', '%' . $filters['q'] . '%')
                    ->orWhere('issn', 'like', '%' . $filters['q'] . '%')
                    ->orWhere('abstract', 'like', '%' . $filters['q'] . '%');
            });
        }

        // Apply filters
        if (!empty($filters['collection_type'])) {
            $query->where('collection_type_id', $filters['collection_type']);
        }

        if (!empty($filters['gmd'])) {
            $query->where('gmd_id', $filters['gmd']);
        }

        if (!empty($filters['author'])) {
            $query->whereJsonContains('author_ids', $filters['author']);
        }

        if (!empty($filters['subject'])) {
            $query->whereHas('subjects', function ($q) use ($filters) {
                $q->where('subjects.id', $filters['subject']);
            });
        }

        if (!empty($filters['publisher'])) {
            $query->where('publisher_id', $filters['publisher']);
        }

        if (!empty($filters['language'])) {
            $query->where('language', $filters['language']);
        }

        if (!empty($filters['year_from'])) {
            $query->where('year', '>=', $filters['year_from']);
        }

        if (!empty($filters['year_to'])) {
            $query->where('year', '<=', $filters['year_to']);
        }

        if (!empty($filters['available_only'])) {
            $query->where('available_items', '>', 0);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get autocomplete suggestions.
     */
    public function autocomplete(string $query): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        $results = [];

        // Search collections
        $collections = Collection::select('id', 'title', 'cover_image')
            ->where('title', 'like', '%' . $query . '%')
            ->limit(8)
            ->get();

        foreach ($collections as $collection) {
            $results[] = [
                'type' => 'collection',
                'id' => $collection->id,
                'title' => $collection->title,
                'url' => route('opac.show', $collection->id),
                'cover' => $collection->cover_image ? asset('storage/' . $collection->cover_image) : null,
            ];
        }

        // Search authors
        $authors = Author::select('id', 'name')
            ->where('name', 'like', '%' . $query . '%')
            ->limit(5)
            ->get();

        foreach ($authors as $author) {
            $results[] = [
                'type' => 'author',
                'id' => $author->id,
                'title' => $author->name,
                'url' => route('opac.search', ['author' => $author->id]),
            ];
        }

        // Search subjects
        $subjects = Subject::select('id', 'name')
            ->where('name', 'like', '%' . $query . '%')
            ->limit(5)
            ->get();

        foreach ($subjects as $subject) {
            $results[] = [
                'type' => 'subject',
                'id' => $subject->id,
                'title' => $subject->name,
                'url' => route('opac.search', ['subject' => $subject->id]),
            ];
        }

        return array_slice($results, 0, 15);
    }

    /**
     * Get related collections by subjects.
     */
    public function getRelatedCollections(Collection $collection, int $limit = 6): \Illuminate\Database\Eloquent\Collection
    {
        return Collection::with(['collectionType', 'publisher'])
            ->where('id', '!=', $collection->id)
            ->whereHas('subjects', function ($q) use ($collection) {
                $subjectIds = $collection->subjects->pluck('id');
                $q->whereIn('subjects.id', $subjectIds);
            })
            ->limit($limit)
            ->get();
    }

    /**
     * Get popular collections (most borrowed).
     */
    public function getPopularCollections(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Collection::with(['collectionType', 'publisher', 'gmd'])
            ->orderBy('borrowed_items', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent collections.
     */
    public function getRecentCollections(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Collection::with(['collectionType', 'publisher', 'gmd'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get filter options for advanced search.
     */
    public function getFilterOptions(): array
    {
        return [
            'collection_types' => CollectionType::orderBy('name')->get(),
            'gmds' => Gmd::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
            'languages' => Collection::select('language')
                ->distinct()
                ->whereNotNull('language')
                ->pluck('language')
                ->sort()
                ->values(),
        ];
    }

    /**
     * Get OPAC statistics.
     */
    public function getOpacStatistics(): array
    {
        return [
            'total_collections' => Collection::count(),
            'total_items' => CollectionItem::count(),
            'available_items' => CollectionItem::where('status', 'available')->count(),
            'total_authors' => Author::count(),
        ];
    }

    /**
     * Search by ISBN.
     */
    public function searchByIsbn(string $isbn): ?Collection
    {
        return Collection::where('isbn', $isbn)->first();
    }

    /**
     * Search by ISSN.
     */
    public function searchByIssn(string $issn): ?Collection
    {
        return Collection::where('issn', $issn)->first();
    }

    /**
     * Get collections by author.
     */
    public function getCollectionsByAuthor(int $authorId, int $perPage = 20): LengthAwarePaginator
    {
        return Collection::with(['subjects', 'publisher', 'collectionType', 'gmd'])
            ->whereJsonContains('author_ids', $authorId)
            ->paginate($perPage);
    }

    /**
     * Get collections by subject.
     */
    public function getCollectionsBySubject(int $subjectId, int $perPage = 20): LengthAwarePaginator
    {
        return Collection::with(['subjects', 'publisher', 'collectionType', 'gmd'])
            ->whereHas('subjects', function ($q) use ($subjectId) {
                $q->where('subjects.id', $subjectId);
            })
            ->paginate($perPage);
    }

    /**
     * Get collections by publisher.
     */
    public function getCollectionsByPublisher(int $publisherId, int $perPage = 20): LengthAwarePaginator
    {
        return Collection::with(['subjects', 'publisher', 'collectionType', 'gmd'])
            ->where('publisher_id', $publisherId)
            ->paginate($perPage);
    }

    /**
     * Get new arrivals (recent collections with available items).
     */
    public function getNewArrivals(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Collection::with(['collectionType', 'publisher', 'gmd'])
            ->where('available_items', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
