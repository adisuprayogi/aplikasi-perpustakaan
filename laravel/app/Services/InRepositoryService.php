<?php

namespace App\Services;

use App\Models\InRepository;
use App\Repositories\InRepositoryRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class InRepositoryService
{
    protected InRepositoryRepository $repository;
    protected string $storageDisk = 'public';
    protected string $storagePath = 'repository-files';

    public function __construct(InRepositoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new repository submission.
     */
    public function create(array $data, $file = null): InRepository
    {
        // Generate slug
        $data['slug'] = $this->generateUniqueSlug($data['title']);

        // Handle file upload
        if ($file) {
            $fileData = $this->uploadFile($file);
            $data = array_merge($data, $fileData);
        }

        // Set initial status and submitted date
        $data['status'] = 'pending_moderation';
        $data['submitted_at'] = now();

        // Set branch if not provided
        if (!isset($data['branch_id']) && auth()->check()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }

        return $this->repository->create($data);
    }

    /**
     * Update a repository.
     */
    public function update(InRepository $repository, array $data, $file = null): InRepository
    {
        // Update slug if title changed
        if (isset($data['title']) && $data['title'] !== $repository->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $repository->id);
        }

        // Handle file upload
        if ($file) {
            // Delete old file
            $this->deleteFile($repository->file_path);

            // Upload new file
            $fileData = $this->uploadFile($file);
            $data = array_merge($data, $fileData);
        }

        return $this->repository->update($repository, $data);
    }

    /**
     * Approve a repository submission.
     */
    public function approve(InRepository $repository, ?int $approvedBy = null): InRepository
    {
        $repository->update([
            'status' => 'approved',
            'approved_by' => $approvedBy ?? auth()->id(),
            'approved_at' => now(),
        ]);

        return $repository->fresh();
    }

    /**
     * Reject a repository submission.
     */
    public function reject(InRepository $repository, string $reason, ?int $rejectedBy = null): InRepository
    {
        $repository->update([
            'status' => 'rejected',
            'rejected_by' => $rejectedBy ?? auth()->id(),
            'rejected_at' => now(),
            'rejection_reason' => $reason,
        ]);

        return $repository->fresh();
    }

    /**
     * Publish a repository.
     */
    public function publish(InRepository $repository): InRepository
    {
        $repository->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return $repository->fresh();
    }

    /**
     * Archive a repository.
     */
    public function archive(InRepository $repository): InRepository
    {
        $repository->update([
            'status' => 'archived',
        ]);

        return $repository->fresh();
    }

    /**
     * Assign DOI to a repository.
     */
    public function assignDoi(InRepository $repository, string $doi): InRepository
    {
        $repository->update([
            'doi' => $doi,
            'doi_status' => 'assigned',
        ]);

        return $repository->fresh();
    }

    /**
     * Track file download.
     */
    public function trackDownload(InRepository $repository): void
    {
        $repository->incrementDownloadCount();
    }

    /**
     * Track view.
     */
    public function trackView(InRepository $repository): void
    {
        $repository->incrementViewCount();
    }

    /**
     * Upload file.
     */
    protected function uploadFile($file): array
    {
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($this->storagePath, $fileName, $this->storageDisk);

        return [
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ];
    }

    /**
     * Delete file.
     */
    protected function deleteFile(string $filePath): bool
    {
        return Storage::disk($this->storageDisk)->delete($filePath);
    }

    /**
     * Generate unique slug.
     */
    protected function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = InRepository::where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get file download URL.
     */
    public function getDownloadUrl(InRepository $repository): string
    {
        return Storage::disk($this->storageDisk)->url($repository->file_path);
    }

    /**
     * Check if user can access repository.
     */
    public function canAccess(InRepository $repository, $user = null): bool
    {
        // Public repositories are accessible to everyone
        if ($repository->access_level === 'public') {
            return true;
        }

        // Restricted repositories need authentication
        if (!$user) {
            return false;
        }

        // Super admins and branch admins can access everything
        if ($user->hasRole(['super_admin', 'branch_admin', 'report_viewer'])) {
            return true;
        }

        // Registered access
        if ($repository->access_level === 'registered') {
            return true;
        }

        // Campus only - check if user belongs to the same branch
        if ($repository->access_level === 'campus_only') {
            return $user->branch_id === $repository->branch_id;
        }

        // Restricted - only owner and admins
        if ($repository->access_level === 'restricted') {
            return $user->id === $repository->member_id || $user->hasRole(['super_admin', 'branch_admin']);
        }

        return false;
    }

    /**
     * Check if user can download repository.
     */
    public function canDownload(InRepository $repository, $user = null): bool
    {
        if (!$repository->is_downloadable) {
            return false;
        }

        return $this->canAccess($repository, $user);
    }

    /**
     * Delete repository.
     */
    public function delete(InRepository $repository): bool
    {
        // Delete file
        $this->deleteFile($repository->file_path);

        return $this->repository->delete($repository);
    }

    /**
     * Get repository with relationships.
     */
    public function getWithRelations(int $id, array $relations = []): ?InRepository
    {
        return $this->repository->with($relations)->find($id);
    }

    /**
     * Get paginated repositories with filters.
     */
    public function paginate(int $perPage = 15, array $filters = [])
    {
        return $this->repository->paginate($perPage, $filters);
    }

    /**
     * Get paginated published repositories.
     */
    public function paginatePublished(int $perPage = 15, array $filters = [])
    {
        return $this->repository->paginatePublished($perPage, $filters);
    }

    /**
     * Get paginated pending moderation repositories.
     */
    public function paginatePendingModeration(int $perPage = 15)
    {
        return $this->repository->paginatePendingModeration($perPage);
    }

    /**
     * Search repositories.
     */
    public function search(string $query, int $perPage = 15)
    {
        return $this->repository->search($query, $perPage);
    }

    /**
     * Get statistics.
     */
    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }

    /**
     * Get repositories by member.
     */
    public function getByMember(int $memberId, int $perPage = 15)
    {
        return $this->repository->getByMember($memberId, $perPage);
    }

    /**
     * Get recent repositories.
     */
    public function getRecent(int $limit = 5)
    {
        return $this->repository->getRecent($limit);
    }

    /**
     * Get popular repositories.
     */
    public function getPopular(int $limit = 5)
    {
        return $this->repository->getPopular($limit);
    }
}
