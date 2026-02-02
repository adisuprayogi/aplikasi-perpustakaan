<?php

namespace App\Services;

use App\Models\DigitalFile;
use App\Models\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class DigitalFileService
{
    /**
     * Create a new digital file.
     */
    public function createDigitalFile(array $data, ?UploadedFile $file = null): DigitalFile
    {
        return DB::transaction(function () use ($data, $file) {
            // Handle file upload
            if ($file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('digital-files', $fileName, 'local');

                $data['file_path'] = $filePath;
                $data['file_name'] = $file->getClientOriginalName();
                $data['file_size'] = $file->getSize();
                $data['file_type'] = $file->getClientOriginalExtension();
                $data['mime_type'] = $file->getMimeType();
            }

            // Set defaults
            $data['download_count'] = 0;
            $data['view_count'] = 0;
            $data['is_active'] = $data['is_active'] ?? true;

            // If published_at is not set and is_active is true, set it to now
            if (empty($data['published_at']) && $data['is_active']) {
                $data['published_at'] = now();
            }

            return DigitalFile::create($data);
        });
    }

    /**
     * Update a digital file.
     */
    public function updateDigitalFile(DigitalFile $digitalFile, array $data, ?UploadedFile $file = null): DigitalFile
    {
        return DB::transaction(function () use ($digitalFile, $data, $file) {
            // Handle file replacement
            if ($file) {
                // Delete old file
                if ($digitalFile->file_path && Storage::exists($digitalFile->file_path)) {
                    Storage::delete($digitalFile->file_path);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('digital-files', $fileName, 'local');

                $data['file_path'] = $filePath;
                $data['file_name'] = $file->getClientOriginalName();
                $data['file_size'] = $file->getSize();
                $data['file_type'] = $file->getClientOriginalExtension();
                $data['mime_type'] = $file->getMimeType();
            }

            // If published_at is not set and is_active is being changed to true, set it to now
            if (empty($data['published_at']) && isset($data['is_active']) && $data['is_active'] && !$digitalFile->is_active) {
                $data['published_at'] = now();
            }

            $digitalFile->update($data);

            return $digitalFile->fresh();
        });
    }

    /**
     * Delete a digital file.
     */
    public function deleteDigitalFile(DigitalFile $digitalFile): bool
    {
        return DB::transaction(function () use ($digitalFile) {
            // Delete physical file
            if ($digitalFile->file_path && Storage::exists($digitalFile->file_path)) {
                Storage::delete($digitalFile->file_path);
            }

            return $digitalFile->delete();
        });
    }

    /**
     * Get digital files for a collection.
     */
    public function getFilesForCollection(Collection $collection, bool $activeOnly = true): \Illuminate\Database\Eloquent\Collection
    {
        $query = $collection->digitalFiles();

        if ($activeOnly) {
            $query->active()->published();
        }

        return $query->latest()->get();
    }

    /**
     * Get accessible files for user.
     */
    public function getAccessibleFiles(?\Illuminate\Contracts\Auth\Authenticatable $user, int $limit = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = DigitalFile::query()->active()->published()->with(['collection']);

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

        return $query->latest('published_at')->paginate($limit);
    }

    /**
     * Get popular digital files.
     */
    public function getPopularFiles(int $limit = 10, ?string $accessLevel = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = DigitalFile::active()->published()->with(['collection']);

        if ($accessLevel) {
            $query->where('access_level', $accessLevel);
        }

        return $query->orderBy('download_count', 'desc')
            ->orderBy('view_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent digital files.
     */
    public function getRecentFiles(int $limit = 10, ?string $accessLevel = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = DigitalFile::active()->published()->with(['collection']);

        if ($accessLevel) {
            $query->where('access_level', $accessLevel);
        }

        return $query->latest('published_at')->limit($limit)->get();
    }

    /**
     * Search digital files.
     */
    public function searchFiles(string $search, ?array $filters = null): \Illuminate\Database\Eloquent\Builder
    {
        $query = DigitalFile::active()->published()->with(['collection']);

        // Search in title and description
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });

        // Apply filters
        if ($filters) {
            if (isset($filters['access_level'])) {
                $query->where('access_level', $filters['access_level']);
            }

            if (isset($filters['collection_id'])) {
                $query->where('collection_id', $filters['collection_id']);
            }

            if (isset($filters['file_type'])) {
                $query->where('file_type', $filters['file_type']);
            }
        }

        return $query;
    }

    /**
     * Check if user can access file.
     */
    public function canAccessFile(DigitalFile $digitalFile, ?\Illuminate\Contracts\Auth\Authenticatable $user): bool
    {
        return $digitalFile->isAccessibleBy($user);
    }

    /**
     * Increment download count.
     */
    public function incrementDownloadCount(DigitalFile $digitalFile): void
    {
        $digitalFile->incrementDownloadCount();
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount(DigitalFile $digitalFile): void
    {
        $digitalFile->incrementViewCount();
    }

    /**
     * Get file statistics.
     */
    public function getFileStatistics(?int $collectionId = null): array
    {
        $query = DigitalFile::query();

        if ($collectionId) {
            $query->where('collection_id', $collectionId);
        }

        $totalFiles = (clone $query)->count();
        $activeFiles = (clone $query)->where('is_active', true)->count();
        $totalDownloads = (clone $query)->sum('download_count');
        $totalViews = (clone $query)->sum('view_count');
        $totalFileSize = (clone $query)->sum('file_size');

        // Count by access level
        $publicFiles = (clone $query)->where('access_level', 'public')->count();
        $registeredFiles = (clone $query)->where('access_level', 'registered')->count();
        $campusOnlyFiles = (clone $query)->where('access_level', 'campus_only')->count();

        return [
            'total_files' => $totalFiles,
            'active_files' => $activeFiles,
            'total_downloads' => $totalDownloads,
            'total_views' => $totalViews,
            'total_file_size' => $totalFileSize,
            'total_file_size_human' => $this->formatFileSize($totalFileSize),
            'public_files' => $publicFiles,
            'registered_files' => $registeredFiles,
            'campus_only_files' => $campusOnlyFiles,
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
}
