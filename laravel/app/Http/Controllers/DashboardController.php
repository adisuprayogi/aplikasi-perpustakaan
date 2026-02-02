<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\Collection;
use App\Models\Loan;
use App\Models\Reservation;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $branch = $user->branch;

        // Get statistics based on user role
        $stats = [];

        // Get quick actions based on user role
        $quickActions = $this->getQuickActions($user);

        if ($user->hasRole('super_admin')) {
            // Super admin sees all data
            $stats = [
                'total_members' => Member::count(),
                'active_members' => Member::where('status', 'active')->count(),
                'total_collections' => Collection::count(),
                'total_items' => \App\Models\CollectionItem::count(),
                'available_items' => \App\Models\CollectionItem::where('status', 'available')->count(),
                'active_loans' => Loan::where('status', 'active')->count(),
                'overdue_loans' => Loan::where('status', 'active')->where('due_date', '<', now())->count(),
                'pending_reservations' => Reservation::where('status', 'pending')->count(),
                'total_branches' => \App\Models\Branch::count(),
            ];

            // Recent activity
            $recentLoans = Loan::with(['member', 'item', 'loanBranch'])
                ->latest()
                ->limit(5)
                ->get();

            $overdueLoans = Loan::with(['member', 'item'])
                ->where('status', 'active')
                ->where('due_date', '<', now())
                ->latest()
                ->limit(10)
                ->get();

        } elseif ($user->hasRole(['branch_admin', 'circulation_staff'])) {
            // Branch-specific stats
            $branchId = $user->branch_id;

            $stats = [
                'total_members' => Member::where('branch_id', $branchId)->count(),
                'active_members' => Member::where('branch_id', $branchId)->where('status', 'active')->count(),
                'total_items' => \App\Models\CollectionItem::where('branch_id', $branchId)->count(),
                'available_items' => \App\Models\CollectionItem::where('branch_id', $branchId)->where('status', 'available')->count(),
                'active_loans' => Loan::where('loan_branch_id', $branchId)->where('status', 'active')->count(),
                'overdue_loans' => Loan::where('loan_branch_id', $branchId)->where('status', 'active')->where('due_date', '<', now())->count(),
                'pending_reservations' => Reservation::where('branch_id', $branchId)->where('status', 'pending')->count(),
            ];

            // Recent activity for this branch
            $recentLoans = Loan::with(['member', 'item', 'loanBranch'])
                ->where('loan_branch_id', $branchId)
                ->latest()
                ->limit(5)
                ->get();

            $overdueLoans = Loan::with(['member', 'item'])
                ->where('loan_branch_id', $branchId)
                ->where('status', 'active')
                ->where('due_date', '<', now())
                ->latest()
                ->limit(10)
                ->get();

        } elseif ($user->hasRole('catalog_staff')) {
            $stats = [
                'total_collections' => Collection::count(),
                'total_items' => \App\Models\CollectionItem::count(),
                'available_items' => \App\Models\CollectionItem::where('status', 'available')->count(),
            ];

            $recentLoans = collect();
            $overdueLoans = collect();

        } else {
            // Report viewer or member
            $stats = [];
            $recentLoans = collect();
            $overdueLoans = collect();
        }

        return view('dashboard', compact(
            'user',
            'branch',
            'stats',
            'recentLoans',
            'overdueLoans',
            'quickActions'
        ));
    }

    /**
     * Get quick actions based on user role.
     */
    protected function getQuickActions($user): array
    {
        $actions = [];

        if ($user->hasRole('super_admin')) {
            $actions = [
                [
                    'title' => 'Peminjaman Baru',
                    'icon' => 'M12 4v16m8-8H4',
                    'route' => 'loans.create',
                    'color' => 'blue',
                    'permission' => 'loans.create',
                ],
                [
                    'title' => 'Koleksi',
                    'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    'route' => 'collections.index',
                    'color' => 'purple',
                    'permission' => 'collections.view',
                ],
                [
                    'title' => 'Anggota',
                    'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
                    'route' => 'members.index',
                    'color' => 'violet',
                    'permission' => 'members.view',
                ],
                [
                    'title' => 'Users',
                    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                    'route' => 'users.index',
                    'color' => 'teal',
                    'permission' => 'users.view',
                ],
                [
                    'title' => 'Laporan',
                    'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'route' => 'reports.index',
                    'color' => 'amber',
                    'permission' => 'reports.view',
                ],
                [
                    'title' => 'Perpustakaan Digital',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'route' => 'digital-files.index',
                    'color' => 'emerald',
                    'permission' => 'digital_files.view',
                ],
            ];
        } elseif ($user->hasRole('branch_admin')) {
            $actions = [
                [
                    'title' => 'Peminjaman Baru',
                    'icon' => 'M12 4v16m8-8H4',
                    'route' => 'loans.create',
                    'color' => 'blue',
                    'permission' => 'loans.create',
                ],
                [
                    'title' => 'Pengembalian',
                    'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                    'route' => 'loans.index',
                    'color' => 'emerald',
                    'permission' => 'loans.view',
                ],
                [
                    'title' => 'Anggota',
                    'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
                    'route' => 'members.index',
                    'color' => 'violet',
                    'permission' => 'members.view',
                ],
                [
                    'title' => 'Koleksi',
                    'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    'route' => 'collections.index',
                    'color' => 'purple',
                    'permission' => 'collections.view',
                ],
                [
                    'title' => 'Laporan',
                    'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'route' => 'reports.index',
                    'color' => 'amber',
                    'permission' => 'reports.view',
                ],
            ];
        } elseif ($user->hasRole('circulation_staff')) {
            $actions = [
                [
                    'title' => 'Peminjaman Baru',
                    'icon' => 'M12 4v16m8-8H4',
                    'route' => 'loans.create',
                    'color' => 'blue',
                    'permission' => 'loans.create',
                ],
                [
                    'title' => 'Pengembalian',
                    'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                    'route' => 'loans.index',
                    'color' => 'emerald',
                    'permission' => 'loans.view',
                ],
                [
                    'title' => 'Anggota',
                    'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
                    'route' => 'members.index',
                    'color' => 'violet',
                    'permission' => 'members.view',
                ],
                [
                    'title' => 'Cari Koleksi',
                    'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
                    'route' => 'collections.index',
                    'color' => 'purple',
                    'permission' => 'collections.view',
                ],
            ];
        } elseif ($user->hasRole('catalog_staff')) {
            $actions = [
                [
                    'title' => 'Koleksi',
                    'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    'route' => 'collections.index',
                    'color' => 'purple',
                    'permission' => 'collections.view',
                ],
                [
                    'title' => 'Item',
                    'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                    'route' => 'items.index',
                    'color' => 'blue',
                    'permission' => 'items.view',
                ],
                [
                    'title' => 'Perpustakaan Digital',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'route' => 'digital-files.index',
                    'color' => 'emerald',
                    'permission' => 'digital_files.view',
                ],
            ];
        }

        // Filter actions by user permissions
        return collect($actions)->filter(function ($action) use ($user) {
            // If no permission specified, show to all
            if (!isset($action['permission'])) {
                return true;
            }
            return $user->can($action['permission']);
        })->values()->all();
    }
}
