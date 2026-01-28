<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Branch;
use Carbon\Carbon;

class ReturnService
{
    protected FineCalculator $fineCalculator;

    public function __construct(FineCalculator $fineCalculator)
    {
        $this->fineCalculator = $fineCalculator;
    }

    /**
     * Process a loan return.
     */
    public function processReturn(Loan $loan, Branch $returnBranch, ?string $condition = null): array
    {
        if ($loan->status !== 'active') {
            throw new \InvalidArgumentException('Peminjaman sudah tidak aktif.');
        }

        $item = $loan->item;

        // Calculate fine using FineCalculator
        $fineAmount = $this->fineCalculator->calculateFine($loan);
        $overdueDays = $this->fineCalculator->calculateOverdueDays($loan);

        // Determine loan status after return
        $status = $fineAmount > 0 ? 'overdue' : 'returned';

        // Update loan
        $loan->update([
            'return_date' => now(),
            'return_branch_id' => $returnBranch->id,
            'fine' => $fineAmount,
            'status' => $status,
            'metadata->return_condition' => $condition ?? 'good',
            'metadata->overdue_days' => $overdueDays,
        ]);

        // Update item status
        $item->update(['status' => 'available']);

        // Update collection statistics
        $item->collection->decrement('borrowed_items');
        $item->collection->increment('available_items');

        // Update item branch if returned to different branch
        if ($returnBranch->id != $item->branch_id) {
            $item->update(['branch_id' => $returnBranch->id]);
        }

        return [
            'loan' => $loan->fresh(),
            'fine_amount' => $fineAmount,
            'overdue_days' => $overdueDays,
            'is_overdue' => $fineAmount > 0,
        ];
    }

    /**
     * Get return summary for a loan.
     */
    public function getReturnSummary(Loan $loan): array
    {
        if ($loan->status !== 'active') {
            return [
                'already_returned' => true,
                'return_date' => $loan->return_date,
                'fine' => $loan->fine,
            ];
        }

        $fineAmount = $this->fineCalculator->calculateFine($loan);
        $overdueDays = $this->fineCalculator->calculateOverdueDays($loan);
        $isOverdue = $loan->isOverdue();

        return [
            'already_returned' => false,
            'due_date' => $loan->due_date->format('d/m/Y'),
            'days_until_due' => now()->diffInDays($loan->due_date, false),
            'is_overdue' => $isOverdue,
            'overdue_days' => $overdueDays,
            'fine_amount' => $fineAmount,
            'fine_formatted' => 'Rp ' . number_format($fineAmount, 0, ',', '.'),
        ];
    }

    /**
     * Get today's return statistics.
     */
    public function getTodayReturnStatistics(?int $branchId = null): array
    {
        $query = Loan::whereDate('return_date', today());

        if ($branchId) {
            $query->where('return_branch_id', $branchId);
        }

        $totalReturns = $query->count();
        $overdueReturns = (clone $query)->where('fine', '>', 0)->count();
        $totalFines = (clone $query)->sum('fine');

        return [
            'total_returns' => $totalReturns,
            'overdue_returns' => $overdueReturns,
            'total_fines' => $totalFines,
            'on_time_returns' => $totalReturns - $overdueReturns,
        ];
    }

    /**
     * Get return history for a member.
     */
    public function getMemberReturnHistory(int $memberId, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Loan::where('member_id', $memberId)
            ->whereIn('status', ['returned', 'overdue'])
            ->with(['item.collection', 'returnBranch'])
            ->latest('return_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Get items due for return today.
     */
    public function getItemsDueToday(?int $branchId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Loan::where('status', 'active')
            ->whereDate('due_date', today());

        if ($branchId) {
            $query->where('loan_branch_id', $branchId);
        }

        return $query->with(['member', 'item.collection', 'item.branch'])->get();
    }

    /**
     * Get items overdue for return.
     */
    public function getOverdueItems(?int $branchId = null, int $days = 0): \Illuminate\Database\Eloquent\Collection
    {
        $query = Loan::where('status', 'active')
            ->where('due_date', '<', now()->subDays($days));

        if ($branchId) {
            $query->where('loan_branch_id', $branchId);
        }

        return $query->with(['member', 'item.collection', 'item.branch'])
            ->oldest('due_date')
            ->get();
    }

    /**
     * Bulk update item status to available (for batch returns).
     */
    public function batchReturn(array $loanIds, ?int $returnBranchId = null): array
    {
        $results = [
            'success' => [],
            'failed' => [],
            'total_fines' => 0,
        ];

        foreach ($loanIds as $loanId) {
            try {
                $loan = Loan::findOrFail($loanId);
                $branchId = $returnBranchId ?? $loan->loan_branch_id;
                $branch = Branch::findOrFail($branchId);

                $result = $this->processReturn($loan, $branch);
                $results['success'][] = [
                    'loan_id' => $loanId,
                    'fine' => $result['fine_amount'],
                ];
                $results['total_fines'] += $result['fine_amount'];
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'loan_id' => $loanId,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
