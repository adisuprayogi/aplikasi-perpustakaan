<?php

namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BranchRepository extends BaseRepository
{
    public function __construct(Branch $branch)
    {
        parent::__construct($branch);
    }

    /**
     * Find branch by code.
     */
    public function findByCode(string $code): ?Branch
    {
        return $this->findBy('code', $code);
    }

    /**
     * Get active branches.
     */
    public function getActive(): Collection
    {
        return $this->query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get branches by type.
     */
    public function getByType(string $type): Collection
    {
        return $this->query()
            ->where('type', $type)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get branches with statistics.
     */
    public function getWithStatistics(): Collection
    {
        return $this->query()
            ->withCount(['members', 'collectionItems'])
            ->get();
    }

    /**
     * Get branch statistics.
     */
    public function getStatistics(Branch $branch): array
    {
        return [
            'total_members' => $branch->members()->count(),
            'active_members' => $branch->members()->where('status', 'active')->count(),
            'suspended_members' => $branch->members()->where('status', 'suspended')->count(),
            'total_items' => $branch->collectionItems()->count(),
            'available_items' => $branch->collectionItems()->where('status', 'available')->count(),
            'borrowed_items' => $branch->collectionItems()->where('status', 'borrowed')->count(),
            'active_loans' => $branch->loansAsLoanBranch()->where('status', 'active')->count(),
            'overdue_loans' => $branch->loansAsLoanBranch()->where('status', 'active')->where('due_date', '<', now())->count(),
        ];
    }

    /**
     * Get branches for dropdown (select option).
     */
    public function getForDropdown(): Collection
    {
        return $this->query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'code', 'name']);
    }

    /**
     * Get branch with recent members.
     */
    public function getWithRecentMembers(Branch $branch, int $limit = 5): Branch
    {
        return $branch->load(['members' => function ($query) use ($limit) {
            $query->latest()->limit($limit);
        }]);
    }

    /**
     * Get branch with recent items.
     */
    public function getWithRecentItems(Branch $branch, int $limit = 5): Branch
    {
        return $branch->load(['collectionItems' => function ($query) use ($limit) {
            $query->latest()->limit($limit);
        }]);
    }

    /**
     * Apply filters for pagination.
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('code', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('name', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query;
    }

    /**
     * Restore a soft deleted branch.
     */
    public function restore(int $id): Branch
    {
        $branch = $this->model->withTrashed()->findOrFail($id);
        $branch->restore();
        return $branch;
    }

    /**
     * Get all branches including soft deleted.
     */
    public function getAllWithTrashed(): Collection
    {
        return $this->query()
            ->withTrashed()
            ->withCount(['members', 'collectionItems'])
            ->get();
    }
}
