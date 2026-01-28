<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:branches.view')->only(['index', 'show']);
        $this->middleware('permission:branches.create')->only(['create', 'store']);
        $this->middleware('permission:branches.edit')->only(['edit', 'update']);
        $this->middleware('permission:branches.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            $branches = Branch::withTrashed()->withCount('members', 'collectionItems')->get();
        } else {
            $branches = Branch::where('id', $user->branch_id)->withCount('members', 'collectionItems')->get();
        }

        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:branches',
            'name' => 'required|string|max:255',
            'type' => 'required|in:central,faculty,study_program',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $branch = Branch::create($validated);

        return redirect()
            ->route('branches.show', $branch)
            ->with('success', 'Branch berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        $branch->load(['members' => fn($q) => $q->latest()->limit(5), 'collectionItems' => fn($q) => $q->latest()->limit(5)]);

        // Get statistics
        $stats = [
            'total_members' => $branch->members()->count(),
            'active_members' => $branch->members()->where('status', 'active')->count(),
            'total_items' => $branch->collectionItems()->count(),
            'available_items' => $branch->collectionItems()->where('status', 'available')->count(),
            'active_loans' => $branch->loansAsLoanBranch()->where('status', 'active')->count(),
        ];

        return view('admin.branches.show', compact('branch', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:branches,code,' . $branch->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:central,faculty,study_program',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        return redirect()
            ->route('branches.show', $branch)
            ->with('success', 'Branch berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch berhasil dihapus.');
    }

    /**
     * Restore a soft deleted branch.
     */
    public function restore($id)
    {
        $branch = Branch::withTrashed()->findOrFail($id);
        $branch->restore();

        return redirect()
            ->route('branches.show', $branch)
            ->with('success', 'Branch berhasil dipulihkan.');
    }
}
