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

    /**
     * Export loan report to CSV.
     */
    public function exportLoanReportCsv(string $startDate, string $endDate, ?int $branchId = null): string
    {
        $stats = $this->getLoanStats($startDate, $endDate, $branchId);
        $filename = 'loan_report_' . $startDate . '_to_' . $endDate . '.csv';
        $filepath = storage_path('app/temp/' . $filename);

        $handle = fopen($filepath, 'w');
        fputcsv($handle, ['Loan Report', '']);
        fputcsv($handle, ['Period', $startDate . ' to ' . $endDate]);
        fputcsv($handle, ['Generated At', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, ['']);

        fputcsv($handle, ['Metric', 'Value']);
        fputcsv($handle, ['Total Loans', $stats['total_loans']]);
        fputcsv($handle, ['Active Loans', $stats['active_loans']]);
        fputcsv($handle, ['Completed Loans', $stats['completed_loans']]);
        fputcsv($handle, ['Overdue Loans', $stats['overdue_loans']]);
        fputcsv($handle, ['Total Renewals', $stats['total_renewals']]);
        fputcsv($handle, ['Avg Loan Duration (days)', number_format($stats['average_loan_duration'] ?? 0, 2)]);

        fputcsv($handle, ['']);
        fputcsv($handle, ['Loans by Member Type', '']);
        foreach ($stats['loans_by_member_type'] as $type => $data) {
            fputcsv($handle, [$type, $data['count'], $data['percentage'] . '%']);
        }

        fclose($handle);

        return $filepath;
    }

    /**
     * Export overdue report to CSV.
     */
    public function exportOverdueReportCsv(string $startDate, string $endDate, ?int $branchId = null): string
    {
        $stats = $this->getOverdueReport($startDate, $endDate, $branchId);
        $filename = 'overdue_report_' . $startDate . '_to_' . $endDate . '.csv';
        $filepath = storage_path('app/temp/' . $filename);

        $handle = fopen($filepath, 'w');
        fputcsv($handle, ['Overdue Report', '']);
        fputcsv($handle, ['Period', $startDate . ' to ' . $endDate]);
        fputcsv($handle, ['Generated At', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, ['']);

        fputcsv($handle, ['Metric', 'Value']);
        fputcsv($handle, ['Total Overdue', $stats['total_overdue']]);
        fputcsv($handle, ['Total Fine Amount', 'Rp ' . number_format($stats['total_fine_amount'], 0, ',', '.')]);
        fputcsv($handle, ['Total Paid Fines', 'Rp ' . number_format($stats['total_paid_fines'], 0, ',', '.')]);
        fputcsv($handle, ['Total Remaining Fines', 'Rp ' . number_format($stats['total_remaining_fines'], 0, ',', '.')]);
        fputcsv($handle, ['Average Overdue Days', number_format($stats['average_overdue_days'] ?? 0, 1)]);

        fputcsv($handle, ['']);
        fputcsv($handle, ['Member Name', 'Item Title', 'Due Date', 'Days Overdue', 'Fine Amount']);

        foreach ($stats['overdue_loans'] as $loan) {
            fputcsv($handle, [
                $loan->member->name,
                $loan->item?->collection?->title ?? 'Unknown',
                $loan->due_date->format('Y-m-d'),
                $loan->days_overdue ?? 0,
                'Rp ' . number_format($loan->calculated_fine ?? 0, 0, ',', '.'),
            ]);
        }

        fclose($handle);

        return $filepath;
    }

    /**
     * Export fine report to CSV.
     */
    public function exportFineReportCsv(string $startDate, string $endDate, ?int $branchId = null): string
    {
        $stats = $this->getFineReport($startDate, $endDate, $branchId);
        $filename = 'fine_report_' . $startDate . '_to_' . $endDate . '.csv';
        $filepath = storage_path('app/temp/' . $filename);

        $handle = fopen($filepath, 'w');
        fputcsv($handle, ['Fine Report', '']);
        fputcsv($handle, ['Period', $startDate . ' to ' . $endDate]);
        fputcsv($handle, ['Generated At', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, ['']);

        fputcsv($handle, ['Metric', 'Value']);
        fputcsv($handle, ['Total Payments', $stats['total_payments']]);
        fputcsv($handle, ['Total Amount', 'Rp ' . number_format($stats['total_amount'], 0, ',', '.')]);

        fputcsv($handle, ['']);
        fputcsv($handle, ['Payments by Method', '']);
        foreach ($stats['payments_by_method'] as $method => $data) {
            fputcsv($handle, [ucfirst($method), $data['count'], 'Rp ' . number_format($data['amount'], 0, ',', '.')]);
        }

        fclose($handle);

        return $filepath;
    }

    /**
     * Export collection report to CSV.
     */
    public function exportCollectionReportCsv(?int $branchId = null): string
    {
        $stats = $this->getCollectionStats($branchId);
        $filename = 'collection_report_' . now()->format('Y-m-d') . '.csv';
        $filepath = storage_path('app/temp/' . $filename);

        $handle = fopen($filepath, 'w');
        fputcsv($handle, ['Collection Report', '']);
        fputcsv($handle, ['Generated At', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, ['']);

        fputcsv($handle, ['Metric', 'Value']);
        fputcsv($handle, ['Total Collections', $stats['total_collections']]);
        fputcsv($handle, ['Total Items', $stats['total_items']]);

        fputcsv($handle, ['']);
        fputcsv($handle, ['Collections by Type', '']);
        foreach ($stats['by_type'] as $type => $data) {
            fputcsv($handle, [ucfirst($type), $data['count'] . ' collections', $data['items'] . ' items']);
        }

        fclose($handle);

        return $filepath;
    }

    /**
     * Export member report to CSV.
     */
    public function exportMemberReportCsv(?int $branchId = null): string
    {
        $stats = $this->getMemberStats($branchId);
        $filename = 'member_report_' . now()->format('Y-m-d') . '.csv';
        $filepath = storage_path('app/temp/' . $filename);

        $handle = fopen($filepath, 'w');
        fputcsv($handle, ['Member Report', '']);
        fputcsv($handle, ['Generated At', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, ['']);

        fputcsv($handle, ['Metric', 'Value']);
        fputcsv($handle, ['Total Members', $stats['total_members']]);
        fputcsv($handle, ['Active Members', $stats['active_members']]);
        fputcsv($handle, ['Inactive Members', $stats['inactive_members']]);
        fputcsv($handle, ['Suspended Members', $stats['suspended_members']]);
        fputcsv($handle, ['New This Month', $stats['new_this_month']]);
        fputcsv($handle, ['Expired Members', $stats['expired_members']]);

        fputcsv($handle, ['']);
        fputcsv($handle, ['Members by Type', '']);
        foreach ($stats['by_type'] as $type => $count) {
            fputcsv($handle, [ucfirst($type), $count]);
        }

        fclose($handle);

        return $filepath;
    }

    /**
     * Get branch comparison report.
     */
    public function getBranchComparisonReport(): array
    {
        $branches = \App\Models\Branch::where('is_active', true)->get();

        $comparison = $branches->map(function ($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'type' => $branch->type,
                'total_members' => $this->getTotalMembers($branch->id),
                'active_members' => $this->getActiveMembers($branch->id),
                'total_collections' => $this->getTotalCollections($branch->id),
                'total_items' => $this->getTotalItems($branch->id),
                'active_loans' => $this->getActiveLoans($branch->id),
                'overdue_loans' => $this->getOverdueLoans($branch->id),
                'loans_this_month' => $this->getLoansByBranchThisMonth($branch->id),
                'returns_this_month' => $this->getReturnsByBranchThisMonth($branch->id),
            ];
        });

        return [
            'branches' => $comparison,
            'summary' => [
                'total_branches' => $branches->count(),
                'total_members_all' => $this->getTotalMembers(null),
                'total_items_all' => $this->getTotalItems(null),
                'total_loans_all' => \App\Models\Loan::count(),
            ],
        ];
    }

    /**
     * Get loans by branch this month.
     */
    private function getLoansByBranchThisMonth(int $branchId): int
    {
        return \App\Models\Loan::where('loan_branch_id', $branchId)
            ->whereMonth('loan_date', now()->month)
            ->whereYear('loan_date', now()->year)
            ->count();
    }

    /**
     * Get returns by branch this month.
     */
    private function getReturnsByBranchThisMonth(int $branchId): int
    {
        return \App\Models\Loan::where('return_branch_id', $branchId)
            ->whereNotNull('return_date')
            ->whereMonth('return_date', now()->month)
            ->whereYear('return_date', now()->year)
            ->count();
    }

    /**
     * Export branch comparison report to CSV.
     */
    public function exportBranchComparisonReportCsv(): string
    {
        $stats = $this->getBranchComparisonReport();
        $filename = 'branch_comparison_report_' . now()->format('Y-m-d') . '.csv';
        $filepath = storage_path('app/temp/' . $filename);

        $handle = fopen($filepath, 'w');
        fputcsv($handle, ['Branch Comparison Report', '']);
        fputcsv($handle, ['Generated At', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, ['']);

        fputcsv($handle, ['Branch Name', 'Type', 'Total Members', 'Active Members', 'Total Items', 'Active Loans', 'Overdue', 'Loans This Month', 'Returns This Month']);

        foreach ($stats['branches'] as $branch) {
            fputcsv($handle, [
                $branch['name'],
                $branch['type'],
                $branch['total_members'],
                $branch['active_members'],
                $branch['total_items'],
                $branch['active_loans'],
                $branch['overdue_loans'],
                $branch['loans_this_month'],
                $branch['returns_this_month'],
            ]);
        }

        fputcsv($handle, ['']);
        fputcsv($handle, ['Summary', '']);
        fputcsv($handle, ['Total Branches', $stats['summary']['total_branches']]);
        fputcsv($handle, ['Total Members (All Branches)', $stats['summary']['total_members_all']]);
        fputcsv($handle, ['Total Items (All Branches)', $stats['summary']['total_items_all']]);
        fputcsv($handle, ['Total Loans (All Branches)', $stats['summary']['total_loans_all']]);

        fclose($handle);

        return $filepath;
    }
}
