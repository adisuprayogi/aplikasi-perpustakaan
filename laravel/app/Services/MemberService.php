<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class MemberService
{
    /**
     * Register a new member.
     */
    public function registerMember(array $data): Member
    {
        // Generate unique member number
        if (!isset($data['member_no'])) {
            $data['member_no'] = $this->generateMemberNumber($data['type'] ?? 'student');
        }

        // Set default status
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }

        // Set default expire date based on type
        if (!isset($data['expire_date'])) {
            $data['expire_date'] = $this->calculateExpireDate($data['type'] ?? 'student');
        }

        return DB::transaction(function () use ($data) {
            return Member::create($data);
        });
    }

    /**
     * Update member information.
     */
    public function updateMember(Member $member, array $data): Member
    {
        return DB::transaction(function () use ($member, $data) {
            $member->update($data);
            return $member->fresh();
        });
    }

    /**
     * Suspend a member.
     */
    public function suspendMember(Member $member, ?string $reason = null): Member
    {
        // Check if member has active loans
        $activeLoans = Loan::where('member_id', $member->id)
            ->where('status', 'active')
            ->count();

        if ($activeLoans > 0) {
            throw new \InvalidArgumentException('Tidak dapat menangguhkan anggota dengan peminjaman aktif.');
        }

        $member->update([
            'status' => 'suspended',
            'metadata->suspension_reason' => $reason,
            'metadata->suspended_at' => now(),
        ]);

        return $member->fresh();
    }

    /**
     * Reactivate a suspended member.
     */
    public function reactivateMember(Member $member): Member
    {
        if ($member->status === 'active') {
            throw new \InvalidArgumentException('Anggota sudah aktif.');
        }

        $member->update([
            'status' => 'active',
            'metadata->reactivated_at' => now(),
        ]);

        return $member->fresh();
    }

    /**
     * Generate a unique member number.
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
        $lastMember = Member::where('member_no', 'like', "{$prefix}{$year}%")
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

    /**
     * Calculate expire date based on member type.
     */
    public function calculateExpireDate(string $type): \Carbon\Carbon
    {
        return match ($type) {
            'student' => now()->addYears(4), // 4 years for students
            'lecturer' => now()->addYears(5), // 5 years for lecturers
            'staff' => now()->addYear(), // 1 year for staff
            default => now()->addYear(),
        };
    }

    /**
     * Extend member membership.
     */
    public function extendMembership(Member $member, int $years = 1): Member
    {
        $currentExpire = $member->expire_date ?? now();
        $newExpire = $currentExpire->copy()->addYears($years);

        $member->update([
            'expire_date' => $newExpire,
        ]);

        return $member->fresh();
    }

    /**
     * Check if member is eligible for borrowing.
     */
    public function isEligibleForBorrowing(Member $member): bool
    {
        // Check if member is active
        if ($member->status !== 'active') {
            return false;
        }

        // Check if membership is expired
        if ($member->expire_date && $member->expire_date->isPast()) {
            return false;
        }

        // Check if member has unpaid fines
        $fineCalculator = app(FineCalculator::class);
        $totalFines = $fineCalculator->calculateMemberTotalFines($member->id);
        if ($totalFines > 0) {
            return false;
        }

        return true;
    }

    /**
     * Get member borrowing summary.
     */
    public function getBorrowingSummary(int $memberId): array
    {
        $member = Member::with(['loans' => function ($query) {
            $query->with(['item.collection', 'loanBranch', 'returnBranch']);
        }])->findOrFail($memberId);

        $activeLoans = $member->loans->where('status', 'active');
        $returnedLoans = $member->loans->whereIn('status', ['returned', 'overdue']);

        // Calculate total fines
        $fineCalculator = app(FineCalculator::class);
        $totalUnpaidFines = $fineCalculator->calculateMemberTotalFines($memberId);

        return [
            'member' => $member,
            'total_loans' => $member->total_loans,
            'active_loans' => $activeLoans->count(),
            'returned_loans' => $returnedLoans->count(),
            'total_unpaid_fines' => $totalUnpaidFines,
            'is_eligible' => $this->isEligibleForBorrowing($member),
            'can_borrow_reason' => $this->getCannotBorrowReason($member),
        ];
    }

    /**
     * Get reason why member cannot borrow.
     */
    public function getCannotBorrowReason(Member $member): ?string
    {
        if ($member->status !== 'active') {
            return 'Status anggota: ' . $member->status;
        }

        if ($member->expire_date && $member->expire_date->isPast()) {
            return 'Keanggotaan telah berakhir pada ' . $member->expire_date->format('d/m/Y');
        }

        $fineCalculator = app(FineCalculator::class);
        $totalFines = $fineCalculator->calculateMemberTotalFines($member->id);
        if ($totalFines > 0) {
            return 'Memiliki denda belum dibayar: Rp ' . number_format($totalFines);
        }

        return null;
    }

    /**
     * Search members by various criteria.
     */
    public function searchMembers(string $search, int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        return Member::where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('member_no', 'like', '%' . $search . '%')
                ->orWhere('id_number', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');
        })
            ->with('branch')
            ->limit($limit)
            ->get();
    }

    /**
     * Get members expiring soon (within 30 days).
     */
    public function getMembersExpiringSoon(?int $branchId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Member::where('status', 'active')
            ->whereBetween('expire_date', [now(), now()->addDays(30)]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->with('branch')->get();
    }

    /**
     * Get expired members.
     */
    public function getExpiredMembers(?int $branchId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Member::where('status', 'active')
            ->where('expire_date', '<', now());

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->with('branch')->get();
    }

    /**
     * Get member statistics.
     */
    public function getMemberStatistics(?int $branchId = null): array
    {
        $query = Member::query();

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $total = (clone $query)->count();
        $active = (clone $query)->where('status', 'active')->count();
        $suspended = (clone $query)->where('status', 'suspended')->count();
        $expired = (clone $query)->where('status', 'active')
            ->where('expire_date', '<', now())
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'suspended' => $suspended,
            'expired' => $expired,
            'expiring_soon' => $this->getMembersExpiringSoon($branchId)->count(),
        ];
    }
}
