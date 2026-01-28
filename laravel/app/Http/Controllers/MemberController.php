<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:members.view')->only(['index', 'show']);
        $this->middleware('permission:members.create')->only(['create', 'store']);
        $this->middleware('permission:members.edit')->only(['edit', 'update']);
        $this->middleware('permission:members.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Member::query();

        // Filter by branch if not super admin
        if (!$user->hasRole('super_admin')) {
            $query->where('branch_id', $user->branch_id);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('member_no', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        $members = $query->with(['branch', 'activeLoans'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $branches = Branch::active()->get();

        return view('admin.members.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:student,lecturer,staff,external',
            'id_number' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
        ]);

        // Generate member number
        $branch = Branch::findOrFail($validated['branch_id']);
        $validated['member_no'] = $this->generateMemberNo($branch, $validated['type']);
        $validated['status'] = 'active';
        $validated['valid_from'] = $validated['valid_from'];
        $validated['valid_until'] = $validated['valid_until'];

        $member = Member::create($validated);

        return redirect()
            ->route('members.show', $member)
            ->with('success', 'Anggota berhasil didaftarkan dengan nomor: ' . $member->member_no);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        $member->load(['branch', 'activeLoans.item.collection', 'loans' => fn($q) => $q->latest()->limit(10)]);

        // Get statistics
        $stats = [
            'active_loans' => $member->activeLoans()->count(),
            'overdue_loans' => $member->activeLoans()->where('due_date', '<', now())->count(),
            'total_loans' => $member->loans()->count(),
            'total_fines' => $member->total_fines,
            'is_eligible' => $member->isEligibleForBorrowing(),
        ];

        // Get current loans
        $currentLoans = $member->activeLoans()->with('item.collection')->get();

        return view('admin.members.show', compact('member', 'stats', 'currentLoans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        $branches = Branch::active()->get();
        return view('admin.members.edit', compact('member', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'type' => 'required|in:student,lecturer,staff,external',
            'id_number' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'status' => 'required|in:active,suspended,expired,blacklisted',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date',
        ]);

        $member->update($validated);

        return redirect()
            ->route('members.show', $member)
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()
            ->route('members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    /**
     * Renew membership.
     */
    public function renew(Request $request, Member $member)
    {
        $validated = $request->validate([
            'valid_until' => 'required|date|after:today',
        ]);

        $member->update([
            'valid_until' => $validated['valid_until'],
            'status' => 'active',
        ]);

        return redirect()
            ->route('members.show', $member)
            ->with('success', 'Keanggotaan berhasil diperpanjang.');
    }

    /**
     * Suspend member.
     */
    public function suspend(Request $request, Member $member)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $member->update([
            'status' => 'suspended',
            'metadata->suspension_reason' => $validated['notes'] ?? 'Manual suspension',
            'metadata->suspended_at' => now()->toDateTimeString(),
        ]);

        return redirect()
            ->route('members.show', $member)
            ->with('success', 'Anggota berhasil disuspend.');
    }

    /**
     * Generate member number.
     */
    private function generateMemberNo($branch, $type)
    {
        $prefix = match($type) {
            'student' => 'MHS',
            'lecturer' => 'DSN',
            'staff' => 'STF',
            'external' => 'EXT',
        };

        $date = now()->format('Ym');
        $lastMember = Member::where('member_no', 'like', "{$prefix}-{$branch->code}-%")
            ->orderBy('member_no', 'desc')
            ->first();

        $sequence = $lastMember ? (int)substr($lastMember->member_no, -4) + 1 : 1;

        return sprintf("%s-%s-%s-%04d", $prefix, $branch->code, $date, $sequence);
    }
}
