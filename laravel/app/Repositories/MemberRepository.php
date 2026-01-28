<?php

namespace App\Repositories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberRepository extends BaseRepository
{
    public function __construct(Member $member)
    {
        parent::__construct($member);
    }

    /**
     * Find member by member number.
     */
    public function findByMemberNo(string $memberNo): ?Member
    {
        return $this->findBy('member_no', $memberNo);
    }

    /**
     * Find member by ID number.
     */
    public function findByIdNumber(string $idNumber): ?Member
    {
        return $this->findBy('id_number', $idNumber);
    }

    /**
     * Search members.
     */
    public function search(string $search): Collection
    {
        return $this->query()->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('member_no', 'like', '%' . $search . '%')
                ->orWhere('id_number', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');
        })->get();
    }

    /**
     * Get active members.
     */
    public function getActiveMembers(): Collection
    {
        return $this->query()->where('status', 'active')->get();
    }

    /**
     * Get members by type.
     */
    public function getByType(string $type): Collection
    {
        return $this->query()->where('type', $type)->get();
    }

    /**
     * Get members by branch.
     */
    public function getByBranch(int $branchId): Collection
    {
        return $this->query()->where('branch_id', $branchId)->get();
    }

    /**
     * Get members expiring soon.
     */
    public function getExpiringSoon(int $days = 30): Collection
    {
        return $this->query()
            ->where('status', 'active')
            ->whereBetween('expire_date', [now(), now()->addDays($days)])
            ->with('branch')
            ->get();
    }

    /**
     * Get expired members.
     */
    public function getExpiredMembers(): Collection
    {
        return $this->query()
            ->where('status', 'active')
            ->where('expire_date', '<', now())
            ->with('branch')
            ->get();
    }

    /**
     * Get suspended members.
     */
    public function getSuspendedMembers(): Collection
    {
        return $this->query()->where('status', 'suspended')->get();
    }

    /**
     * Apply filters for pagination.
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('member_no', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query;
    }

    /**
     * Get member statistics.
     */
    public function getStatistics(?int $branchId = null): array
    {
        $query = $this->model->newQuery();

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $total = (clone $query)->count();
        $active = (clone $query)->where('status', 'active')->count();
        $suspended = (clone $query)->where('status', 'suspended')->count();
        $expired = (clone $query)
            ->where('status', 'active')
            ->where('expire_date', '<', now())
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'suspended' => $suspended,
            'expired' => $expired,
        ];
    }

    /**
     * Generate unique member number.
     */
    public function generateMemberNumber(string $type): string
    {
        $prefix = match ($type) {
            'student' => 'MHS',
            'lecturer' => 'DSN',
            'staff' => 'STF',
            default => 'MBR',
        };

        $year = now()->format('Y');
        $lastMember = $this->query()
            ->where('member_no', 'like', "{$prefix}{$year}%")
            ->orderBy('member_no', 'desc')
            ->first();

        if ($lastMember) {
            $lastNumber = (int) substr($lastMember->member_no, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$year}{$newNumber}";
    }
}
