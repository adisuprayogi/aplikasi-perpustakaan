<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    /**
     * Display listing of users.
     */
    public function index(Request $request): View
    {
        $query = User::with('branch');

        // Filter by branch
        if ($request->has('branch') && $request->branch !== '') {
            $query->where('branch_id', $request->branch);
        }

        // Filter by role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $branches = Branch::all();
        $roles = ['super_admin', 'branch_admin', 'circulation_staff', 'catalog_staff', 'report_viewer'];

        return view('admin.users.index', compact('users', 'branches', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $branches = Branch::all();
        $roles = [
            'super_admin' => 'Super Admin - Akses penuh ke semua fitur',
            'branch_admin' => 'Admin Cabang - Kelola cabang tertentu',
            'circulation_staff' => 'Staff Sirkulasi - Kelola peminjaman/pengembalian',
            'catalog_staff' => 'Staff Katalog - Kelola koleksi',
            'report_viewer' => 'Viewer Laporan - Lihat laporan saja',
        ];

        return view('admin.users.create', compact('branches', 'roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
            'role' => 'required|in:super_admin,branch_admin,circulation_staff,catalog_staff,report_viewer',
            'phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'branch_id' => $validated['branch_id'] ?? null,
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Assign role using Spatie Permission
        $user->assignRole($validated['role']);

        return redirect()
            ->route('users.index')
            ->with('success', "User {$user->name} berhasil dibuat.");
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        $user->load(['branch', 'permissions', 'roles']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $branches = Branch::all();
        $roles = [
            'super_admin' => 'Super Admin - Akses penuh ke semua fitur',
            'branch_admin' => 'Admin Cabang - Kelola cabang tertentu',
            'circulation_staff' => 'Staff Sirkulasi - Kelola peminjaman/pengembalian',
            'catalog_staff' => 'Staff Katalog - Kelola koleksi',
            'report_viewer' => 'Viewer Laporan - Lihat laporan saja',
        ];

        return view('admin.users.edit', compact('user', 'branches', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
            'role' => 'required|in:super_admin,branch_admin,circulation_staff,catalog_staff,report_viewer',
            'phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'branch_id' => $validated['branch_id'] ?? null,
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Sync roles
        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('users.index')
            ->with('success', "User {$user->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $name = $user->name;

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        // Prevent deactivating self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "User {$user->name} berhasil {$status}.");
    }
}
