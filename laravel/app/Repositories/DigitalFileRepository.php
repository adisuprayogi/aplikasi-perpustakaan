<?php

namespace App\Repositories;

use App\Models\DigitalFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class DigitalFileRepository extends BaseRepository
{
    public function __construct(DigitalFile $digitalFile)
    {
        parent::__construct($digitalFile);
    }

    /**
     * Search digital files by title.
     */
    public function searchByTitle(string $title): EloquentCollection
    {
        return $this->query()
            ->where('title', 'like', '%' . $title . '%')
            ->with(['collection', 'uploader'])
            ->get();
    }

    /**
     * Get files by collection.
     */
    public function getByCollection(int $collectionId, bool $activeOnly = false): EloquentCollection
    {
        $query = $this->query()
            ->where('collection_id', $collectionId)
            ->with(['collection', 'uploader']);

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->latest()->get();
    }

    /**
     * Get files by access level.
     */
    public function getByAccessLevel(string $accessLevel): EloquentCollection
    {
        return $this->query()
            ->where('access_level', $accessLevel)
            ->where('is_active', true)
            ->with(['collection', 'uploader'])
            ->latest()
            ->get();
    }

    /**
     * Get published files.
     */
    public function getPublished(?string $accessLevel = null): EloquentCollection
    {
        $query = $this->query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['collection']);

        if ($accessLevel) {
            $query->where('access_level', $accessLevel);
        }

        return $query->latest('published_at')->get();
    }

    /**
     * Get popular files (most downloaded).
     */
    public function getPopular(int $limit = 10, ?string $accessLevel = null): EloquentCollection
    {
        $query = $this->query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['collection']);

        if ($accessLevel) {
            $query->where('access_level', $accessLevel);
        }

        return $query->orderBy('download_count', 'desc')
            ->orderBy('view_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent files.
     */
    public function getRecent(int $limit = 10, ?string $accessLevel = null): EloquentCollection
    {
        $query = $this->query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['collection']);

        if ($accessLevel) {
            $query->where('access_level', $accessLevel);
        }

        return $query->latest('published_at')->limit($limit)->get();
    }

    /**
     * Advanced search with filters.
     */
    public function advancedSearch(array $filters): LengthAwarePaginator
    {
        $query = $this->query()->with(['collection', 'uploader']);

        if (isset($filters['q'])) {
            $searchTerm = $filters['q'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        if (isset($filters['access_level'])) {
            $query->where('access_level', $filters['access_level']);
        }

        if (isset($filters['collection_id'])) {
            $query->where('collection_id', $filters['collection_id']);
        }

        if (isset($filters['file_type'])) {
            $query->where('file_type', $filters['file_type']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['published_only']) && $filters['published_only']) {
            $query->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }

        $perPage = $filters['per_page'] ?? 20;

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get accessible files for user.
     */
    public function getAccessibleFiles(?\Illuminate\Contracts\Auth\Authenticatable $user, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['collection']);

        // Filter by access level
        if (!$user) {
            // Guest users can only access public files
            $query->where('access_level', 'public');
        } else {
            // Logged in users can access public and registered files
            $query->where(function ($q) {
                $q->where('access_level', 'public')
                    ->orWhere('access_level', 'registered');
            });
        }

        return $query->latest('published_at')->paginate($perPage);
    }

    /**
     * Get file statistics.
     */
    public function getStatistics(?int $collectionId = null): array
    {
        $query = $this->query();

        if ($collectionId) {
            $query->where('collection_id', $collectionId);
        }

        $totalFiles = (clone $query)->count();
        $activeFiles = (clone $query)->where('is_active', true)->count();
        $publishedFiles = (clone $query)->whereNotNull('published_at')
            ->where('published_at', '<=', now())->count();
        $totalDownloads = (clone $query)->sum('download_count');
        $totalViews = (clone $query)->sum('view_count');
        $totalFileSize = (clone $query)->sum('file_size');

        // Count by access level
        $publicFiles = (clone $query)->where('access_level', 'public')->count();
        $registeredFiles = (clone $query)->where('access_level', 'registered')->count();
        $campusOnlyFiles = (clone $query)->where('access_level', 'campus_only')->count();

        // Count by file type
        $pdfFiles = (clone $query)->where('file_type', 'pdf')->count();
        $imageFiles = (clone $query)->where('mime_type', 'like', 'image%')->count();

        return [
            'total_files' => $totalFiles,
            'active_files' => $activeFiles,
            'published_files' => $publishedFiles,
            'total_downloads' => $totalDownloads,
            'total_views' => $totalViews,
            'total_file_size' => $totalFileSize,
            'total_file_size_human' => $this->formatFileSize($totalFileSize),
            'public_files' => $publicFiles,
            'registered_files' => $registeredFiles,
            'campus_only_files' => $campusOnlyFiles,
            'pdf_files' => $pdfFiles,
            'image_files' => $imageFiles,
        ];
    }

    /**
     * Format file size to human readable.
     */
    protected function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get related files by collection.
     */
    public function getRelatedFiles(DigitalFile $digitalFile, int $limit = 6): EloquentCollection
    {
        return $this->query()
            ->where('id', '!=', $digitalFile->id)
            ->where('collection_id', $digitalFile->collection_id)
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['collection'])
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}
