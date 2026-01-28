<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Show payment form for a loan fine.
     */
    public function create(Loan $loan)
    {
        $this->authorize('create', Payment::class);

        // Recalculate fine before showing payment form
        $loan->updateFine();

        $loan->load(['member', 'item.collection']);

        return view('admin.fines.create', compact('loan'));
    }

    /**
     * Process a fine payment.
     */
    public function store(Request $request, Loan $loan)
    {
        $this->authorize('create', Payment::class);

        // Recalculate fine
        $loan->updateFine();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $loan->remaining_fine,
            'payment_method' => 'required|in:cash,transfer,edc',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Generate payment number
        $paymentNo = 'PAY-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

        // Create payment record
        $payment = Payment::create([
            'payment_no' => $paymentNo,
            'loan_id' => $loan->id,
            'member_id' => $loan->member_id,
            'branch_id' => $loan->loan_branch_id,
            'received_by' => Auth::id(),
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'],
            'status' => 'paid',
            'notes' => $validated['notes'],
        ]);

        // Update loan paid_fine
        $loan->increment('paid_fine', $validated['amount']);

        // If fully paid and returned, update status
        if ($loan->remaining_fine <= 0 && $loan->status === 'overdue') {
            $loan->update(['status' => 'returned']);
        }

        return redirect()
            ->route('loans.show', $loan)
            ->with('success', 'Pembayaran denda berhasil dicatat. Rp ' . number_format($validated['amount'], 0, ',', '.'));
    }

    /**
     * Show payment history for a loan.
     */
    public function history(Loan $loan)
    {
        $this->authorize('viewAny', Payment::class);

        $loan->load(['payments.processedBy', 'member']);

        $payments = $loan->payments()->latest()->get();

        return view('admin.fines.history', compact('loan', 'payments'));
    }

    /**
     * Get all unpaid fines for a member.
     */
    public function memberFines(int $memberId)
    {
        $this->authorize('viewAny', Payment::class);

        $loans = Loan::where('member_id', $memberId)
            ->where(function ($query) {
                $query->where('status', 'active')
                    ->where('due_date', '<', now())
                    ->orWhere('status', 'overdue');
            })
            ->where(function ($query) {
                $query->whereRaw('(fine - paid_fine) > 0')
                    ->orWhere('fine', '>', 0);
            })
            ->with(['item.collection', 'payments'])
            ->get()
            ->map(function ($loan) {
                $loan->updateFine();
                return $loan;
            });

        $totalUnpaid = $loans->sum(function ($loan) {
            return $loan->remaining_fine;
        });

        return view('admin.fines.member-fines', compact('loans', 'totalUnpaid', 'memberId'));
    }

    /**
     * Waive a fine (discount/forgive).
     */
    public function waive(Request $request, Loan $loan)
    {
        $this->authorize('waive', Payment::class);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $loan->remaining_fine,
            'reason' => 'required|string|max:500',
        ]);

        // Generate payment number
        $paymentNo = 'WAIVE-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

        // Update fine (reduce the fine amount)
        $loan->decrement('fine', $validated['amount']);

        // Create a record of the waiver
        Payment::create([
            'payment_no' => $paymentNo,
            'loan_id' => $loan->id,
            'member_id' => $loan->member_id,
            'branch_id' => $loan->loan_branch_id,
            'received_by' => Auth::id(),
            'amount' => $validated['amount'],
            'payment_method' => 'cash', // waived payments use cash method
            'payment_reference' => null,
            'status' => 'paid',
            'notes' => 'Denda dihapus: ' . $validated['reason'],
        ]);

        return redirect()
            ->route('loans.show', $loan)
            ->with('success', 'Denda berhasil dihapus sebesar Rp ' . number_format($validated['amount'], 0, ',', '.'));
    }
}
