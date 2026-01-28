<?php

namespace App\Http\Controllers;

use App\Models\LoanRule;
use App\Models\CollectionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanRuleController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', LoanRule::class);

        $memberTypes = [
            'student' => 'Mahasiswa',
            'lecturer' => 'Dosen',
            'staff' => 'Staf',
            'external' => 'Eksternal',
        ];

        $query = LoanRule::with('collectionType');

        // Filter by member type
        if ($request->filled('member_type')) {
            $query->where('member_type', $request->member_type);
        }

        $loanRules = $query->latest()->get();

        return view('admin.loan-rules.index', compact('loanRules', 'memberTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', LoanRule::class);

        $collectionTypes = CollectionType::all();

        $memberTypes = [
            'student' => 'Mahasiswa',
            'lecturer' => 'Dosen',
            'staff' => 'Staf',
            'external' => 'Eksternal',
        ];

        return view('admin.loan-rules.create', compact('collectionTypes', 'memberTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', LoanRule::class);

        $validated = $request->validate([
            'member_type' => 'required|in:student,lecturer,staff,external',
            'collection_type_id' => 'nullable|exists:collection_types,id',
            'loan_period' => 'required|integer|min:1|max:365',
            'max_loans' => 'required|integer|min:1|max:50',
            'fine_per_day' => 'required|numeric|min:0|max:999999.99',
            'is_renewable' => 'boolean',
            'renew_limit' => 'required|integer|min:0|max:10',
            'is_fine_by_calendar' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Check if rule already exists
        $existing = LoanRule::where('member_type', $validated['member_type'])
            ->where('collection_type_id', $validated['collection_type_id'] ?? null)
            ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Aturan peminjaman untuk tipe anggota dan tipe koleksi ini sudah ada.']);
        }

        LoanRule::create($validated);

        return redirect()
            ->route('loan-rules.index')
            ->with('success', 'Aturan peminjaman berhasil ditambahkan.');
    }

    /**
     * Show the specified resource.
     */
    public function show(LoanRule $loanRule)
    {
        $this->authorize('view', $loanRule);

        $loanRule->load('collectionType');

        return view('admin.loan-rules.show', compact('loanRule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanRule $loanRule)
    {
        $this->authorize('update', $loanRule);

        $collectionTypes = CollectionType::all();

        $memberTypes = [
            'student' => 'Mahasiswa',
            'lecturer' => 'Dosen',
            'staff' => 'Staf',
            'external' => 'Eksternal',
        ];

        return view('admin.loan-rules.edit', compact('loanRule', 'collectionTypes', 'memberTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanRule $loanRule)
    {
        $this->authorize('update', $loanRule);

        $validated = $request->validate([
            'member_type' => 'required|in:student,lecturer,staff,external',
            'collection_type_id' => 'nullable|exists:collection_types,id',
            'loan_period' => 'required|integer|min:1|max:365',
            'max_loans' => 'required|integer|min:1|max:50',
            'fine_per_day' => 'required|numeric|min:0|max:999999.99',
            'is_renewable' => 'boolean',
            'renew_limit' => 'required|integer|min:0|max:10',
            'is_fine_by_calendar' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Check if rule already exists (excluding current)
        $existing = LoanRule::where('member_type', $validated['member_type'])
            ->where('collection_type_id', $validated['collection_type_id'] ?? null)
            ->where('id', '!=', $loanRule->id)
            ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Aturan peminjaman untuk tipe anggota dan tipe koleksi ini sudah ada.']);
        }

        $loanRule->update($validated);

        return redirect()
            ->route('loan-rules.show', $loanRule)
            ->with('success', 'Aturan peminjaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanRule $loanRule)
    {
        $this->authorize('delete', $loanRule);

        $loanRule->delete();

        return redirect()
            ->route('loan-rules.index')
            ->with('success', 'Aturan peminjaman berhasil dihapus.');
    }
}
