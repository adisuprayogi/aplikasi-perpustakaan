<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanRule;
use App\Models\Holiday;
use Carbon\Carbon;

class FineCalculator
{
    /**
     * Calculate overdue days for a loan.
     */
    public function calculateOverdueDays(Loan $loan): int
    {
        if ($loan->status !== 'active' || !$loan->isOverdue()) {
            return 0;
        }

        $dueDate = $loan->due_date;
        $returnDate = now();

        // Get applicable loan rule
        $rule = $this->getLoanRule($loan);
        if (!$rule) {
            // Default: count all days
            return $dueDate->diffInDays($returnDate);
        }

        if ($rule->is_fine_by_calendar) {
            // Count all days including holidays
            return $dueDate->diffInDays($returnDate);
        } else {
            // Count only working days (excluding holidays)
            return $this->calculateWorkingDays($dueDate, $returnDate);
        }
    }

    /**
     * Calculate fine amount for a loan.
     */
    public function calculateFine(Loan $loan): float
    {
        $overdueDays = $this->calculateOverdueDays($loan);

        if ($overdueDays <= 0) {
            return 0;
        }

        // Get applicable loan rule
        $rule = $this->getLoanRule($loan);
        if (!$rule) {
            return 0;
        }

        return $overdueDays * $rule->fine_per_day;
    }

    /**
     * Calculate total fine for all active overdue loans for a member.
     */
    public function calculateMemberTotalFines(int $memberId): float
    {
        $loans = Loan::where('member_id', $memberId)
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->get();

        $totalFine = 0;
        foreach ($loans as $loan) {
            $totalFine += $this->calculateFine($loan);
        }

        return $totalFine;
    }

    /**
     * Get applicable loan rule for a loan.
     */
    protected function getLoanRule(Loan $loan): ?LoanRule
    {
        $memberType = $loan->member->type ?? null;
        $collectionTypeId = $loan->item->collection->collection_type_id ?? null;

        return LoanRule::getApplicableRule($memberType, $collectionTypeId);
    }

    /**
     * Calculate working days between two dates (excluding holidays and weekends).
     */
    protected function calculateWorkingDays(Carbon $startDate, Carbon $endDate): int
    {
        $days = 0;
        $current = $startDate->copy()->addDay();

        // Get holidays
        $holidays = Holiday::whereBetween('date', [$current->toDateString(), $endDate->toDateString()])
            ->pluck('date')
            ->toArray();

        while ($current->lte($endDate)) {
            // Skip weekends (Saturday = 6, Sunday = 0)
            if ($current->isWeekday()) {
                // Skip holidays
                if (!in_array($current->toDateString(), $holidays)) {
                    $days++;
                }
            }
            $current->addDay();
        }

        return $days;
    }

    /**
     * Check if a specific date is a holiday.
     */
    public function isHoliday(Carbon $date): bool
    {
        return Holiday::where('date', $date->toDateString())->exists();
    }

    /**
     * Get holidays for a date range.
     */
    public function getHolidaysInRange(Carbon $startDate, Carbon $endDate): array
    {
        return Holiday::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date')
            ->pluck('date', 'name')
            ->toArray();
    }
}
