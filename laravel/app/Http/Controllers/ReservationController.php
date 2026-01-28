<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Member;
use App\Models\CollectionItem;
use App\Models\Branch;
use App\Models\Loan;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    protected NotificationService $notificationService;

    /**
     * Create a new controller instance.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->middleware('permission:reservations.view')->only(['index', 'show']);
        $this->middleware('permission:reservations.create')->only(['create', 'store']);
        $this->middleware('permission:reservations.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Reservation::query();

        // Filter by branch if not super admin
        if (!$user->hasRole('super_admin')) {
            $query->where('branch_id', $user->branch_id);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search by member name or item barcode/title
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('member', function ($member) use ($request) {
                    $member->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('member_no', 'like', '%' . $request->search . '%');
                })->orWhereHas('item', function ($item) use ($request) {
                    $item->where('barcode', 'like', '%' . $request->search . '%');
                })->orWhereHas('item.collection', function ($collection) use ($request) {
                    $collection->where('title', 'like', '%' . $request->search . '%');
                });
            });
        }

        $reservations = $query->with(['member', 'item.collection', 'branch', 'processedBy'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Get statistics for quick filters
        $stats = [
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'ready' => (clone $query)->where('status', 'ready')->count(),
            'fulfilled' => (clone $query)->where('status', 'fulfilled')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'expired' => (clone $query)->where('status', 'expired')->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.reservations.create', compact('branches'));
    }

    /**
     * Store a newly created reservation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'item_id' => 'required|exists:collection_items,id',
            'branch_id' => 'required|exists:branches,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $member = Member::findOrFail($validated['member_id']);
        $item = CollectionItem::with('collection')->findOrFail($validated['item_id']);
        $branch = Branch::findOrFail($validated['branch_id']);

        // Validate member eligibility
        if (!$member->isEligibleForBorrowing()) {
            return redirect()
                ->back()
                ->with('error', 'Anggota tidak eligible untuk reservasi. Status: ' . $member->status)
                ->withInput();
        }

        // Check if item is available (only borrowed items can be reserved)
        if ($item->status === 'available') {
            return redirect()
                ->back()
                ->with('error', 'Item sedang tersedia. Tidak perlu reservasi, langsung pinjam saja.')
                ->withInput();
        }

        // Check if member already has active reservation for this item
        $existingReservation = Reservation::where('member_id', $member->id)
            ->where('item_id', $item->id)
            ->where('status', 'pending')
            ->first();

        if ($existingReservation) {
            return redirect()
                ->back()
                ->with('error', 'Anggota sudah memiliki reservasi aktif untuk item ini.')
                ->withInput();
        }

        // Check member's active reservation count (max 3)
        $activeReservations = Reservation::where('member_id', $member->id)
            ->where('status', 'pending')
            ->count();

        if ($activeReservations >= 3) {
            return redirect()
                ->back()
                ->with('error', 'Anggota sudah mencapai batas maksimal reservasi (3).')
                ->withInput();
        }

        // Calculate expiry date (7 days from now)
        $expiryDate = now()->addDays(7);

        // Create reservation
        $reservation = Reservation::create([
            'member_id' => $member->id,
            'item_id' => $item->id,
            'branch_id' => $branch->id,
            'processed_by' => Auth::id(),
            'reservation_date' => now(),
            'expiry_date' => $expiryDate,
            'notification_sent' => false,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil dibuat. Berlaku hingga: ' . $expiryDate->format('d/m/Y'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['member', 'item.collection', 'item.branch', 'branch', 'processedBy']);

        $branches = Branch::orderBy('name')->get();

        return view('admin.reservations.show', compact('reservation', 'branches'));
    }

    /**
     * Mark reservation as ready (item is available).
     */
    public function markAsReady(Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'Hanya reservasi dengan status pending yang dapat ditandai siap.');
        }

        if ($reservation->isExpired()) {
            $reservation->update(['status' => 'expired']);
            return redirect()
                ->back()
                ->with('error', 'Reservasi sudah kedaluwarsa.');
        }

        $reservation->update([
            'status' => 'ready',
            'notification_sent' => true,
            'ready_at' => now(),
        ]);

        // Send notification to member
        $this->notificationService->sendReservationReadyNotification($reservation);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservasi ditandai sebagai siap diambil. Anggota akan dinotifikasi.');
    }

    /**
     * Fulfill reservation (convert to loan).
     */
    public function fulfill(Request $request, Reservation $reservation)
    {
        if ($reservation->status !== 'ready') {
            return redirect()
                ->back()
                ->with('error', 'Hanya reservasi dengan status ready yang dapat dipenuhi.');
        }

        $validated = $request->validate([
            'loan_branch_id' => 'required|exists:branches,id',
        ]);

        $member = $reservation->member;
        $item = $reservation->item;
        $branch = Branch::findOrFail($validated['loan_branch_id']);

        // Validate member eligibility again
        if (!$member->isEligibleForBorrowing()) {
            return redirect()
                ->back()
                ->with('error', 'Anggota tidak eligible untuk meminjam. Status: ' . $member->status);
        }

        // Check if item is available
        if (!$item->isAvailable()) {
            return redirect()
                ->back()
                ->with('error', 'Item tidak tersedia. Status: ' . $item->status);
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

        // Mark reservation as fulfilled
        $reservation->update([
            'status' => 'fulfilled',
            'fulfilled_at' => now(),
        ]);

        return redirect()
            ->route('loans.show', $loan)
            ->with('success', 'Reservasi berhasil dipenuhi. Peminjaman dibuat. Jatuh tempo: ' . $dueDate->format('d/m/Y'));
    }

    /**
     * Cancel a reservation.
     */
    public function cancel(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        if (!in_array($reservation->status, ['pending', 'ready'])) {
            return redirect()
                ->back()
                ->with('error', 'Hanya reservasi dengan status pending atau ready yang dapat dibatalkan.');
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'notes' => $reservation->notes . "\n\nDibatalkan pada: " . now()->format('d/m/Y H:i') .
                ($validated['cancellation_reason'] ? "\nAlasan: " . $validated['cancellation_reason'] : ''),
        ]);

        // Send notification to member
        $this->notificationService->sendReservationCancelledNotification($reservation, $validated['cancellation_reason'] ?? null);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil dibatalkan. Anggota akan dinotifikasi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        // Only allow deletion of cancelled or fulfilled reservations
        if (!in_array($reservation->status, ['cancelled', 'fulfilled', 'expired'])) {
            return redirect()
                ->back()
                ->with('error', 'Hanya reservasi dengan status cancelled/fulfilled/expired yang dapat dihapus.');
        }

        $reservation->delete();

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservasi berhasil dihapus.');
    }

    /**
     * Search member by ID/number for quick reservation.
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
     * Search item by barcode for quick reservation.
     */
    public function searchItem(Request $request)
    {
        $search = $request->get('search');

        $items = CollectionItem::where('barcode', 'like', '%' . $search . '%')
            ->whereIn('status', ['borrowed', 'lost'])
            ->with(['collection', 'collection.collectionType', 'branch'])
            ->limit(10)
            ->get();

        return response()->json($items);
    }

    /**
     * Get available items for a collection (when fulfilling reservation).
     */
    public function getAvailableItems(Request $request)
    {
        $collectionId = $request->get('collection_id');

        $items = CollectionItem::where('collection_id', $collectionId)
            ->where('status', 'available')
            ->with(['branch'])
            ->get();

        return response()->json($items);
    }

    /**
     * Display authenticated user's reservations.
     */
    public function myReservations(Request $request)
    {
        $user = Auth::user();

        // Get member associated with user (if any)
        $member = $user->member;

        if (!$member) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Anda tidak terhubung dengan data anggota.');
        }

        $query = Reservation::where('member_id', $member->id);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $reservations = $query->with(['item.collection', 'item.branch', 'branch'])
            ->latest('reservation_date')
            ->paginate(10)
            ->withQueryString();

        // Get statistics
        $stats = [
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'ready' => (clone $query)->where('status', 'ready')->count(),
            'fulfilled' => (clone $query)->where('status', 'fulfilled')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'expired' => (clone $query)->where('status', 'expired')->count(),
        ];

        return view('reservations.my-reservations', compact('reservations', 'stats', 'member'));
    }

    /**
     * Cancel own reservation (by member).
     */
    public function cancelMyReservation(Request $request, Reservation $reservation)
    {
        $user = Auth::user();

        // Verify the reservation belongs to the authenticated user's member
        if (!$user->member || $reservation->member_id !== $user->member->id) {
            return redirect()
                ->route('reservations.my')
                ->with('error', 'Anda tidak memiliki akses ke reservasi ini.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        if (!in_array($reservation->status, ['pending', 'ready'])) {
            return redirect()
                ->route('reservations.my')
                ->with('error', 'Hanya reservasi dengan status pending atau ready yang dapat dibatalkan.');
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'notes' => $reservation->notes . "\n\nDibatalkan oleh anggota pada: " . now()->format('d/m/Y H:i') .
                ($validated['cancellation_reason'] ? "\nAlasan: " . $validated['cancellation_reason'] : ''),
        ]);

        return redirect()
            ->route('reservations.my')
            ->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
