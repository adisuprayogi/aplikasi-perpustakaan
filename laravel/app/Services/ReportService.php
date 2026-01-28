<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Member;
use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\Reservation;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(?int $branchId = null): array
    {
        return [
            'total_members' => $this->getTotalMembers($branchId),
            'active_members' => $this->getActiveMembers($branchId),
            'total_collections' => $this->getTotalCollections($branchId),
            'total_items' => $this->getTotalItems($branchId),
            'active_loans' => $this->getActiveLoans($branchId),
            'overdue_loans' => $this->getOverdueLoans($branchId),
            'pending_reservations' => $this->getPendingReservations($branchId),
            'total_fines' => $this->getTotalUnpaidFines($branchId),
            'loans_today' => $this->getLoansToday($branchId),
            'returns_today' => $this->getReturnsToday($branchId),
            'new_members_this_month' => $this->getNewMembersThisMonth($branchId),
            'popular_items' => $this->getPopularItems($branchId, 10),
        ];
    }

    /**
     * Get loan statistics for a date range.
     */
    public function getLoanStats(string $startDate, string $endDate, ?int $branchId = null): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = Loan::whereBetween('loan_date', [$start, $end]);

        if ($branchId) {
            $query->whereHas('member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $loans = $query->get();

        return [
            'total_loans' => $loans->count(),
            'active_loans' => $loans->where('status', 'active')->count(),
            'completed_loans' => $loans->where('status', 'returned')->count(),
            'overdue_loans' => $loans->where('status', 'overdue')->count(),
            'total_renewals' => $loans->sum('renewal_count'),
            'average_loan_duration' => $loans->whereNotNull('return_date')
                ->avg(fn($loan) => $loan->loan_date->diffInDays($loan->return_date)),
            'loans_by_member_type' => $this->groupLoansByMemberType($loans),
            'loans_by_collection_type' => $this->groupLoansByCollectionType($loans),
            'daily_loans' => $this->getDailyLoans($start, $end, $branchId),
        ];
    }

    /**
     * Get overdue report for a date range.
     */
    public function getOverdueReport(string $startDate, string $endDate, ?int $branchId = null): array
    {
        $query = Loan::where('status', 'overdue')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->with(['member', 'item.collection']);

        if ($branchId) {
            $query->whereHas('member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $overdueLoans = $query->get();

        return [
            'total_overdue' => $overdueLoans->count(),
            'total_fine_amount' => $overdueLoans->sum('calculated_fine'),
            'total_paid_fines' => $overdueLoans->sum('paid_fine'),
            'total_remaining_fines' => $overdueLoans->sum('remaining_fine'),
            'average_overdue_days' => $overdueLoans->avg('days_overdue'),
            'overdue_by_member_type' => $this->groupOverdueByMemberType($overdueLoans),
            'overdue_loans' => $overdueLoans,
        ];
    }

    /**
     * Get fine report for a date range.
     */
    public function getFineReport(string $startDate, string $endDate, ?int $branchId = null): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = Payment::whereBetween('created_at', [$start, $end]);

        if ($branchId) {
            $query->whereHas('loan.member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $payments = $query->get();

        return [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'payments_by_method' => $payments->groupBy('payment_method')
                ->map(fn($group) => [
                    'count' => $group->count(),
                    'amount' => $group->sum('amount'),
                ]),
            'payments_by_date' => $payments->groupBy(fn($p) => $p->created_at->format('Y-m-d'))
                ->map(fn($group) => [
                    'count' => $group->count(),
                    'amount' => $group->sum('amount'),
                ]),
        ];
    }

    /**
     * Get collection statistics.
     */
    public function getCollectionStats(?int $branchId = null): array
    {
        $query = Collection::query();

        if ($branchId) {
            $query->whereHas('items', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $collections = $query->withCount('items')->get();

        return [
            'total_collections' => $collections->count(),
            'total_items' => $collections->sum('items_count'),
            'by_type' => $collections->groupBy('type')
                ->map(fn($group) => [
                    'count' => $group->count(),
                    'items' => $group->sum('items_count'),
                ]),
            'by_category' => $this->getCollectionsByCategory($branchId),
            'most_borrowed' => $this->getMostBorrowedCollections($branchId, 10),
        ];
    }

    /**
     * Get member statistics.
     */
    public function getMemberStats(?int $branchId = null): array
    {
        $query = Member::query();

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $members = $query->get();

        return [
            'total_members' => $members->count(),
            'active_members' => $members->where('status', 'active')->count(),
            'inactive_members' => $members->where('status', 'inactive')->count(),
            'suspended_members' => $members->where('status', 'suspended')->count(),
            'by_type' => $members->groupBy('type')
                ->map(fn($group) => $group->count()),
            'new_this_month' => $members->where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'expired_members' => $members->where('valid_until', '<', Carbon::now())->count(),
        ];
    }

    /**
     * Get circulation trends for the last N months.
     */
    public function getCirculationTrends(int $months = 12, ?int $branchId = null): array
    {
        $trends = [];
        $startDate = Carbon::now()->subMonths($months - 1)->startOfMonth();

        for ($i = 0; $i < $months; $i++) {
            $date = $startDate->copy()->addMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $query = Loan::whereBetween('loan_date', [$monthStart, $monthEnd]);

            if ($branchId) {
                $query->whereHas('member', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }

            $loans = $query->get();

            $trends[] = [
                'month' => $date->format('M Y'),
                'loans' => $loans->count(),
                'returns' => $loans->whereNotNull('return_date')->count(),
                'overdue' => $loans->where('status', 'overdue')->count(),
            ];
        }

        return $trends;
    }

    // Helper methods

    private function getTotalMembers(?int $branchId): int
    {
        $query = Member::query();
        if ($branchId) $query->where('branch_id', $branchId);
        return $query->count();
    }

    private function getActiveMembers(?int $branchId): int
    {
        $query = Member::where('status', 'active');
        if ($branchId) $query->where('branch_id', $branchId);
        return $query->count();
    }

    private function getTotalCollections(?int $branchId): int
    {
        $query = Collection::query();
        if ($branchId) {
            $query->whereHas('items', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        return $query->count();
    }

    private function getTotalItems(?int $branchId): int
    {
        $query = CollectionItem::query();
        if ($branchId) $query->where('branch_id', $branchId);
        return $query->count();
    }

    private function getActiveLoans(?int $branchId): int
    {
        $query = Loan::where('status', 'active');
        if ($branchId) {
            $query->whereHas('member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        return $query->count();
    }

    private function getOverdueLoans(?int $branchId): int
    {
        $query = Loan::where('status', 'overdue');
        if ($branchId) {
            $query->whereHas('member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        return $query->count();
    }

    private function getPendingReservations(?int $branchId): int
    {
        $query = Reservation::where('status', 'pending');
        if ($branchId) $query->where('branch_id', $branchId);
        return $query->count();
    }

    private function getTotalUnpaidFines(?int $branchId): float
    {
        $query = Loan::whereColumn('fine', '>', 'paid_fine');
        if ($branchId) {
            $query->whereHas('member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        // Use raw expression to sum (fine - paid_fine)
        return (float) $query->selectRaw('SUM(fine - paid_fine) as total_unpaid')
            ->value('total_unpaid');
    }

    private function getLoansToday(?int $branchId): int
    {
        $query = Loan::whereDate('loan_date', Carbon::today());
        if ($branchId) {
            $query->whereHas('member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        return $query->count();
    }

    private function getReturnsToday(?int $branchId): int
    {
        $query = Loan::whereDate('return_date', Carbon::today());
        if ($branchId) {
            $query->whereHas('member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        return $query->count();
    }

    private function getNewMembersThisMonth(?int $branchId): int
    {
        $query = Member::where('created_at', '>=', Carbon::now()->startOfMonth());
        if ($branchId) $query->where('branch_id', $branchId);
        return $query->count();
    }

    private function getPopularItems(?int $branchId, int $limit = 10): array
    {
        $query = Loan::selectRaw('item_id, COUNT(*) as loan_count')
            ->with('item.collection')
            ->groupBy('item_id')
            ->orderByDesc('loan_count')
            ->limit($limit);

        if ($branchId) {
            $query->whereHas('member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query->get()->map(fn($loan) => [
            'title' => $loan->item?->collection?->title ?? 'Unknown',
            'loan_count' => $loan->loan_count,
        ])->toArray();
    }

    private function groupLoansByMemberType($loans): array
    {
        return $loans->groupBy('member.type')->map(fn($group) => [
            'count' => $group->count(),
            'percentage' => round(($group->count() / $loans->count()) * 100, 2),
        ])->toArray();
    }

    private function groupLoansByCollectionType($loans): array
    {
        return $loans->groupBy('item.collection.type')->map(fn($group) => [
            'count' => $group->count(),
            'percentage' => round(($group->count() / $loans->count()) * 100, 2),
        ])->toArray();
    }

    private function getDailyLoans(Carbon $start, Carbon $end, ?int $branchId): array
    {
        $query = Loan::selectRaw('DATE(loan_date) as date, COUNT(*) as count')
            ->whereBetween('loan_date', [$start, $end])
            ->groupBy('date')
            ->orderBy('date');

        if ($branchId) {
            $query->whereHas('member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query->get()->pluck('count', 'date')->toArray();
    }

    private function groupOverdueByMemberType($overdueLoans): array
    {
        return $overdueLoans->groupBy('member.type')->map(fn($group) => [
            'count' => $group->count(),
            'total_fine' => $group->sum('calculated_fine'),
        ])->toArray();
    }

    private function getCollectionsByCategory(?int $branchId): array
    {
        $query = Collection::select('classification_id')
            ->with('classification')
            ->selectRaw('COUNT(*) as count');

        if ($branchId) {
            $query->whereHas('items', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query->groupBy('classification_id')
            ->get()
            ->filter(fn($c) => $c->classification)
            ->map(fn($c) => [
                'classification' => $c->classification->code,
                'name' => $c->classification->name,
                'count' => $c->count,
            ])
            ->sortByDesc('count')
            ->values()
            ->toArray();
    }

    private function getMostBorrowedCollections(?int $branchId, int $limit): array
    {
        $subQuery = Loan::selectRaw('collection_items.collection_id, COUNT(*) as loan_count')
            ->join('collection_items', 'loans.item_id', '=', 'collection_items.id')
            ->with('item.collection')
            ->whereHas('item.collection')
            ->groupBy('collection_items.collection_id')
            ->orderByDesc('loan_count')
            ->limit($limit);

        if ($branchId) {
            $subQuery->whereHas('member', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $subQuery->get()->map(fn($loan) => [
            'title' => $loan->item?->collection?->title ?? 'Unknown',
            'author' => $loan->item?->collection?->author?->name ?? '-',
            'loan_count' => $loan->loan_count,
        ])->toArray();
    }
}
