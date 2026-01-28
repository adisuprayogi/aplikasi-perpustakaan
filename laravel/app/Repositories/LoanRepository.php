<?php

namespace App\Repositories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LoanRepository extends BaseRepository
{
    public function __construct(Loan $loan)
    {
        parent::__construct($loan);
    }

    /**
     * Get active loans for a member.
     */
    public function getActiveLoansForMember(int $memberId): Collection
    {
        return $this->query()
            ->where('member_id', $memberId)
            ->where('status', 'active')
            ->with(['item.collection', 'item.branch'])
            ->get();
    }

    /**
     * Get overdue loans.
     */
    public function getOverdueLoans(?int $branchId = null): Collection
    {
        $query = $this->query()
            ->where('status', 'active')
            ->where('due_date', '<', now());

        if ($branchId) {
            $query->where('loan_branch_id', $branchId);
        }

        return $query->with(['member', 'item.collection', 'item.branch'])->get();
    }

    /**
     * Get loans due within days.
     */
    public function getLoansDueWithinDays(int $days, ?int $branchId = null): Collection
    {
        $query = $this->query()
            ->where('status', 'active')
            ->whereBetween('due_date', [now(), now()->addDays($days)]);

        if ($branchId) {
            $query->where('loan_branch_id', $branchId);
        }

        return $query->with(['member', 'item.collection'])->get();
    }

    /**
     * Search loans.
     */
    public function search(string $search, ?int $branchId = null): Collection
    {
        $query = $this->query()->where(function ($q) use ($search) {
            $q->whereHas('member', function ($member) use ($search) {
                $member->where('name', 'like', '%' . $search . '%')
                    ->orWhere('member_no', 'like', '%' . $search . '%');
            })->orWhereHas('item', function ($item) use ($search) {
                $item->where('barcode', 'like', '%' . $search . '%');
            });
        });

        if ($branchId) {
            $query->where('loan_branch_id', $branchId);
        }

        return $query->with(['member', 'item.collection'])->get();
    }

    /**
     * Apply filters for pagination.
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (isset($filters['loan_branch_id'])) {
            $query->where('loan_branch_id', $filters['loan_branch_id']);
        }

        if (isset($filters['from_date'])) {
            $query->where('loan_date', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('loan_date', '<=', $filters['to_date']);
        }

        if (isset($filters['overdue']) && $filters['overdue']) {
            $query->where('status', 'active')
                ->where('due_date', '<', now());
        }

        return $query;
    }

    /**
     * Get loan statistics.
     */
    public function getStatistics(?int $branchId = null): array
    {
        $query = $this->model->newQuery();

        if ($branchId) {
            $query->where('loan_branch_id', $branchId);
        }

        $active = (clone $query)->where('status', 'active')->count();
        $overdue = (clone $query)
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->count();
        $returned = (clone $query)->where('status', 'returned')->count();
        $totalFines = (clone $query)->sum('fine');

        return [
            'active' => $active,
            'overdue' => $overdue,
            'returned' => $returned,
            'total_fines' => $totalFines,
        ];
    }

    /**
     * Get recent loans.
     */
    public function getRecentLoans(int $limit = 10, ?int $branchId = null): Collection
    {
        $query = $this->query();

        if ($branchId) {
            $query->where('loan_branch_id', $branchId);
        }

        return $query->with(['member', 'item.collection', 'loanBranch'])
            ->latest('loan_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if member can borrow more items.
     */
    public function canMemberBorrow(int $memberId, int $maxLoans = 3): bool
    {
        $activeLoans = $this->query()
            ->where('member_id', $memberId)
            ->where('status', 'active')
            ->count();

        return $activeLoans < $maxLoans;
    }
}
