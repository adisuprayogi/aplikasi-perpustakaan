<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Member;
use App\Models\CollectionItem;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoanService
{
    /**
     * Create a new loan.
     */
    public function createLoan(array $data): Loan
    {
        $member = Member::findOrFail($data['member_id']);
        $item = CollectionItem::with('collection.collectionType')->findOrFail($data['item_id']);
        $branch = Branch::findOrFail($data['loan_branch_id']);

        // Validate member eligibility
        if (!$member->isEligibleForBorrowing()) {
            throw new \InvalidArgumentException('Anggota tidak eligible untuk meminjam. Status: ' . $member->status);
        }

        // Check if item is available
        if (!$item->isAvailable()) {
            throw new \InvalidArgumentException('Item tidak tersedia. Status: ' . $item->status);
        }

        // Get loan period from collection type
        $loanPeriod = $item->collection->collectionType->loan_period ?? 7;
        $dueDate = now()->addDays($loanPeriod);

        // Create loan
        $loan = Loan::create([
            'member_id' => $member->id,
            'item_id' => $item->id,
            'loan_branch_id' => $branch->id,
            'processed_by' => Auth::id(),
            'loan_date' => now(),
            'due_date' => $dueDate,
            'renewal_count' => 0,
            'status' => 'active',
        ]);

        // Update item status
        $item->update(['status' => 'borrowed']);

        // Update collection statistics
        $item->collection->increment('borrowed_items');
        $item->collection->decrement('available_items');

        // Update member statistics
        $member->increment('total_loans');

        return $loan;
    }

    /**
     * Renew a loan.
     */
    public function renewLoan(Loan $loan): Loan
    {
        if ($loan->status !== 'active') {
            throw new \InvalidArgumentException('Tidak dapat memperpanjang peminjaman yang tidak aktif.');
        }

        if (!$loan->canBeRenewed()) {
            throw new \InvalidArgumentException('Peminjaman tidak dapat diperpanjang. Mungkin sudah terlambat atau sudah mencapai batas perpanjangan.');
        }

        // Get loan period
        $loanPeriod = $loan->item->collection->collectionType->loan_period ?? 7;

        $loan->update([
            'due_date' => $loan->due_date->addDays($loanPeriod),
            'renewal_count' => $loan->renewal_count + 1,
        ]);

        return $loan->fresh();
    }

    /**
     * Validate if a member can borrow.
     */
    public function canMemberBorrow(Member $member): bool
    {
        // Check if member is active
        if ($member->status !== 'active') {
            return false;
        }

        // Check if member is expired
        if ($member->expire_date && $member->expire_date->isPast()) {
            return false;
        }

        // Check if member has unpaid fines
        $fineCalculator = app(FineCalculator::class);
        $totalFines = $fineCalculator->calculateMemberTotalFines($member->id);
        if ($totalFines > 0) {
            return false;
        }

        // Check if member has reached max loans
        $maxLoans = $this->getMaxLoansForMember($member);
        $activeLoans = Loan::where('member_id', $member->id)
            ->where('status', 'active')
            ->count();

        if ($activeLoans >= $maxLoans) {
            return false;
        }

        return true;
    }

    /**
     * Get maximum number of loans allowed for a member.
     */
    public function getMaxLoansForMember(Member $member): int
    {
        // Default max loans based on member type
        return match ($member->type) {
            'student' => 3,
            'lecturer' => 5,
            'staff' => 3,
            default => 3,
        };
    }

    /**
     * Get active loans count for a member.
     */
    public function getActiveLoansCount(int $memberId): int
    {
        return Loan::where('member_id', $memberId)
            ->where('status', 'active')
            ->count();
    }

    /**
     * Get overdue loans for a member.
     */
    public function getMemberOverdueLoans(int $memberId): \Illuminate\Database\Eloquent\Collection
    {
        return Loan::where('member_id', $memberId)
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->with(['item.collection', 'item.branch'])
            ->get();
    }

    /**
     * Calculate due date based on collection type.
     */
    public function calculateDueDate(CollectionItem $item): Carbon
    {
        $loanPeriod = $item->collection->collectionType->loan_period ?? 7;
        return now()->addDays($loanPeriod);
    }
}
