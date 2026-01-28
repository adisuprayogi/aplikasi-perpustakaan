<?php

namespace App\Services;

use App\Models\Branch;
use App\Repositories\BranchRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BranchService
{
    protected BranchRepository $branchRepository;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    /**
     * Get all branches with statistics.
     */
    public function getAllBranches(): Collection
    {
        return $this->branchRepository->getWithStatistics();
    }

    /**
     * Get all branches including soft deleted.
     */
    public function getAllBranchesWithTrashed(): Collection
    {
        return $this->branchRepository->getAllWithTrashed();
    }

    /**
     * Get active branches only.
     */
    public function getActiveBranches(): Collection
    {
        return $this->branchRepository->getActive();
    }

    /**
     * Get branches for dropdown.
     */
    public function getBranchesForDropdown(): Collection
    {
        return $this->branchRepository->getForDropdown();
    }

    /**
     * Get branch by ID with statistics.
     */
    public function getBranchById(int $id): Branch
    {
        $branch = $this->branchRepository->findOrFail($id);
        $branch->load(['members' => fn($q) => $q->latest()->limit(5), 'collectionItems' => fn($q) => $q->latest()->limit(5)]);

        return $branch;
    }

    /**
     * Get branch statistics.
     */
    public function getBranchStatistics(Branch $branch): array
    {
        return $this->branchRepository->getStatistics($branch);
    }

    /**
     * Create a new branch.
     */
    public function createBranch(array $data): Branch
    {
        return DB::transaction(function () use ($data) {
            $branch = $this->branchRepository->create($data);

            // Log activity if needed
            // activity()->performedOn($branch)->causedBy(auth()->user())->log('Branch created');

            return $branch;
        });
    }

    /**
     * Update an existing branch.
     */
    public function updateBranch(Branch $branch, array $data): Branch
    {
        return DB::transaction(function () use ($branch, $data) {
            $branch = $this->branchRepository->update($branch, $data);

            // Log activity if needed
            // activity()->performedOn($branch)->causedBy(auth()->user())->log('Branch updated');

            return $branch;
        });
    }

    /**
     * Delete a branch (soft delete).
     */
    public function deleteBranch(Branch $branch): bool
    {
        return DB::transaction(function () use ($branch) {
            $result = $this->branchRepository->delete($branch);

            // Log activity if needed
            // activity()->performedOn($branch)->causedBy(auth()->user())->log('Branch deleted');

            return $result;
        });
    }

    /**
     * Restore a soft deleted branch.
     */
    public function restoreBranch(int $id): Branch
    {
        return DB::transaction(function () use ($id) {
            $branch = $this->branchRepository->restore($id);

            // Log activity if needed
            // activity()->performedOn($branch)->causedBy(auth()->user())->log('Branch restored');

            return $branch;
        });
    }

    /**
     * Permanently delete a branch.
     */
    public function forceDeleteBranch(Branch $branch): bool
    {
        return DB::transaction(function () use ($branch) {
            // Check if branch has related data
            if ($branch->members()->count() > 0) {
                throw new \Exception('Cannot delete branch with existing members.');
            }

            if ($branch->collectionItems()->count() > 0) {
                throw new \Exception('Cannot delete branch with existing collection items.');
            }

            return $branch->forceDelete();
        });
    }

    /**
     * Toggle branch active status.
     */
    public function toggleActiveStatus(Branch $branch): Branch
    {
        return $this->updateBranch($branch, [
            'is_active' => !$branch->is_active,
        ]);
    }

    /**
     * Get branches by type.
     */
    public function getBranchesByType(string $type): Collection
    {
        return $this->branchRepository->getByType($type);
    }

    /**
     * Find branch by code.
     */
    public function findBranchByCode(string $code): ?Branch
    {
        return $this->branchRepository->findByCode($code);
    }

    /**
     * Validate if branch can be deleted.
     */
    public function canDeleteBranch(Branch $branch): bool
    {
        return $branch->members()->count() === 0
            && $branch->collectionItems()->count() === 0
            && $branch->users()->count() === 0;
    }

    /**
     * Get branch type label.
     */
    public function getBranchTypeLabel(string $type): string
    {
        return match($type) {
            'central' => 'Pusat',
            'faculty' => 'Fakultas',
            'study_program' => 'Program Studi',
            default => ucfirst($type),
        };
    }

    /**
     * Get all branch types.
     */
    public function getBranchTypes(): array
    {
        return [
            'central' => 'Pusat',
            'faculty' => 'Fakultas',
            'study_program' => 'Program Studi',
        ];
    }
}
