<?php

namespace App\Repositories;

use App\Models\InRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class InRepositoryRepository extends BaseRepository
{
    protected array $with = ['member', 'branch', 'classification', 'approvedBy', 'rejectedBy'];

    public function __construct(InRepository $model)
    {
        parent::__construct($model);
    }

    /**
     * Get published repositories.
     */
    public function getPublished(): Collection
    {
        return $this->query()->published()->get();
    }

    /**
     * Get paginated published repositories.
     */
    public function paginatePublished(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()->published();

        if (!empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        return $query->latest('published_at')->paginate($perPage);
    }

    /**
     * Get pending moderation repositories.
     */
    public function getPendingModeration(): Collection
    {
        return $this->query()->pendingModeration()->get();
    }

    /**
     * Get paginated pending moderation repositories.
     */
    public function paginatePendingModeration(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->pendingModeration()->latest('submitted_at')->paginate($perPage);
    }

    /**
     * Find by slug.
     */
    public function findBySlug(string $slug): ?InRepository
    {
        return $this->query()->where('slug', $slug)->first();
    }

    /**
     * Find by slug or throw exception.
     */
    public function findBySlugOrFail(string $slug): InRepository
    {
        return $this->query()->where('slug', $slug)->firstOrFail();
    }

    /**
     * Search repositories.
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->published()
            ->search($query)
            ->latest('published_at')
            ->paginate($perPage);
    }

    /**
     * Get repositories by document type.
     */
    public function getByDocumentType(string $type, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->published()
            ->byDocumentType($type)
            ->latest('published_at')
            ->paginate($perPage);
    }

    /**
     * Get repositories by year.
     */
    public function getByYear(int $year, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->published()
            ->byYear($year)
            ->latest('published_at')
            ->paginate($perPage);
    }

    /**
     * Get popular repositories (by download count).
     */
    public function getPopular(int $limit = 10): Collection
    {
        return $this->query()
            ->published()
            ->orderByDesc('download_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent repositories.
     */
    public function getRecent(int $limit = 10): Collection
    {
        return $this->query()
            ->published()
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get statistics.
     */
    public function getStatistics(): array
    {
        $total = $this->model->count();
        $published = $this->model->where('status', 'published')->count();
        $pending = $this->model->where('status', 'pending_moderation')->count();
        $approved = $this->model->where('status', 'approved')->count();
        $rejected = $this->model->where('status', 'rejected')->count();
        $totalDownloads = $this->model->sum('download_count');
        $totalViews = $this->model->sum('view_count');

        return [
            'total' => $total,
            'published' => $published,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'total_downloads' => $totalDownloads,
            'total_views' => $totalViews,
        ];
    }

    /**
     * Get repositories by member.
     */
    public function getByMember(int $memberId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->where('member_id', $memberId)
            ->latest('submitted_at')
            ->paginate($perPage);
    }

    /**
     * Get repositories by branch.
     */
    public function getByBranch(int $branchId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->where('branch_id', $branchId)
            ->latest('submitted_at')
            ->paginate($perPage);
    }

    /**
     * Apply filters to query.
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        // Document type filter
        if (!empty($filters['document_type'])) {
            $query->byDocumentType($filters['document_type']);
        }

        // Year filter
        if (!empty($filters['year'])) {
            $query->byYear($filters['year']);
        }

        // Access level filter
        if (!empty($filters['access_level'])) {
            $query->where('access_level', $filters['access_level']);
        }

        // Classification filter
        if (!empty($filters['classification_id'])) {
            $query->where('classification_id', $filters['classification_id']);
        }

        // Branch filter
        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        // Search filter
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        return $query;
    }

    /**
     * Get paginated repositories with filters.
     */
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->query();

        if (!empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        return $query->latest('submitted_at')->paginate($perPage);
    }
}
