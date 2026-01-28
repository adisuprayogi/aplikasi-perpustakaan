<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Member;
use App\Models\CollectionItem;
use App\Models\Branch;
use App\Services\FineCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:loans.view')->only(['index', 'show']);
        $this->middleware('permission:loans.create')->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Loan::query();

        // Filter by branch if not super admin
        if (!$user->hasRole('super_admin')) {
            $query->where('loan_branch_id', $user->branch_id);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search by member name or item barcode
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('member', function ($member) use ($request) {
                    $member->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('member_no', 'like', '%' . $request->search . '%');
                })->orWhereHas('item', function ($item) use ($request) {
                    $item->where('barcode', 'like', '%' . $request->search . '%');
                });
            });
        }

        $loans = $query->with(['member', 'item.collection', 'loanBranch', 'returnBranch'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Get statistics for quick filters
        $stats = [
            'active' => (clone $query)->where('status', 'active')->count(),
            'overdue' => (clone $query)->where('status', 'active')->where('due_date', '<', now())->count(),
            'returned' => (clone $query)->where('status', 'returned')->count(),
        ];

        return view('admin.loans.index', compact('loans', 'stats'));
    }

    /**
     * Show the form for creating a new resource (loan form).
     */
    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.loans.create', compact('branches'));
    }

    /**
     * Store a newly created loan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'item_id' => 'required|exists:collection_items,id',
            'loan_branch_id' => 'required|exists:branches,id',
        ]);

        $member = Member::findOrFail($validated['member_id']);
        $item = CollectionItem::with('collection.collectionType')->findOrFail($validated['item_id']);
        $branch = Branch::findOrFail($validated['loan_branch_id']);

        // Validate member eligibility
        if (!$member->isEligibleForBorrowing()) {
            return redirect()
                ->back()
                ->with('error', 'Anggota tidak eligible untuk meminjam. Status: ' . $member->status)
                ->withInput();
        }

        // Check if item is available
        if (!$item->isAvailable()) {
            return redirect()
                ->back()
                ->with('error', 'Item tidak tersedia. Status: ' . $item->status)
                ->withInput();
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

        return redirect()
            ->route('loans.show', $loan)
            ->with('success', 'Peminjaman berhasil dibuat. Jatuh tempo: ' . $dueDate->format('d/m/Y'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load(['member', 'item.collection', 'item.branch', 'loanBranch', 'returnBranch', 'processedBy']);

        $branches = Branch::orderBy('name')->get();

        return view('admin.loans.show', compact('loan', 'branches'));
    }

    /**
     * Return a loan.
     */
    public function return(Request $request, Loan $loan)
    {
        if ($loan->status !== 'active') {
            return redirect()
                ->back()
                ->with('error', 'Peminjaman sudah tidak aktif.');
        }

        $validated = $request->validate([
            'return_branch_id' => 'required|exists:branches,id',
            'condition' => 'nullable|in:good,damaged,lost',
        ]);

        $item = $loan->item;

        // Calculate fine using FineCalculator
        $fineCalculator = app(FineCalculator::class);
        $fineAmount = $fineCalculator->calculateFine($loan);

        // Update loan
        $loan->update([
            'return_date' => now(),
            'return_branch_id' => $validated['return_branch_id'],
            'fine' => $fineAmount,
            'status' => $fineAmount > 0 ? 'overdue' : 'returned',
            'metadata->return_condition' => $validated['condition'] ?? 'good',
        ]);

        // Update item status
        $item->update(['status' => 'available']);

        // Update collection statistics
        $item->collection->decrement('borrowed_items');
        $item->collection->increment('available_items');

        // Update item branch if returned to different branch
        if ($validated['return_branch_id'] != $item->branch_id) {
            $item->update(['branch_id' => $validated['return_branch_id']]);
        }

        $message = 'Peminjaman berhasil dikembalikan.';
        if ($fineAmount > 0) {
            $message .= ' Denda: Rp ' . number_format($fineAmount);
        }

        return redirect()
            ->route('loans.show', $loan)
            ->with('success', $message);
    }

    /**
     * Renew a loan.
     */
    public function renew(Loan $loan)
    {
        if ($loan->status !== 'active') {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat memperpanjang peminjaman yang tidak aktif.');
        }

        if (!$loan->canBeRenewed()) {
            return redirect()
                ->back()
                ->with('error', 'Peminjaman tidak dapat diperpanjang. Mungkin sudah terlambat atau sudah mencapai batas perpanjangan.');
        }

        // Get loan period
        $loanPeriod = $loan->item->collection->collectionType->loan_period ?? 7;

        $loan->update([
            'due_date' => $loan->due_date->addDays($loanPeriod),
            'renewal_count' => $loan->renewal_count + 1,
        ]);

        return redirect()
            ->route('loans.show', $loan)
            ->with('success', 'Peminjaman berhasil diperpanjang. Jatuh tempo baru: ' . $loan->due_date->format('d/m/Y'));
    }

    /**
     * Search member by ID/number for quick loan.
     */
    public function searchMember(Request $request)
    {
        $search = $request->get('search');

        $members = Member::where(function ($q) use ($search) {
            $q->where('member_no', 'like', '%' . $search . '%')
                ->orWhere('id_number', 'like', '%' . $search . '%');
        })
            ->where('status', 'active')
            ->with('branch')
            ->limit(10)
            ->get();

        return response()->json($members);
    }

    /**
     * Search item by barcode for quick loan.
     */
    public function searchItem(Request $request)
    {
        $search = $request->get('search');

        $items = CollectionItem::where('barcode', 'like', '%' . $search . '%')
            ->where('status', 'available')
            ->with(['collection', 'collection.collectionType', 'branch'])
            ->limit(10)
            ->get();

        return response()->json($items);
    }
}
