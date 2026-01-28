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
            'overdueLoans'
        ));
    }
}
