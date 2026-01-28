@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <!-- User Profile Card -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-8 mb-6 text-white">
            <div class="flex items-center">
                <div class="h-24 w-24 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="ml-6 flex-1">
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    <p class="text-blue-100 mt-1">{{ $user->email }}</p>
                    <div class="mt-3 flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                        @if($user->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500/20 text-green-100">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-500/20 text-red-100">
                                Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>
                @if($user->id !== auth()->id())
                    <div class="flex gap-3">
                        <a href="{{ route('users.edit', $user) }}"
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Dasar</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cabang</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->branch?->name ?? 'Tidak ada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terdaftar</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Login</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $user->last_login_at?->format('d/m/Y H:i') ?? 'Belum pernah' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Roles & Permissions -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Role & Permissions</h3>
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium
                            {{ match($user->role) {
                                'super_admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                'branch_admin' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'circulation_staff' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'catalog_staff' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'report_viewer' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                default => 'bg-gray-100 text-gray-800',
                            } }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                    @if($user->permissions && $user->permissions->count() > 0)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Additional Permissions</dt>
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->permissions as $permission)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi</h3>
                    <div class="space-y-3">
                        @if($user->id !== auth()->id())
                            <a href="{{ route('users.edit', $user) }}"
                               class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                Edit User
                            </a>

                            <form method="POST" action="{{ route('users.toggle-status', $user) }}"
                                  onsubmit="return confirm('Apakah Anda yakin ingin mengubah status user ini?');">
                                @csrf
                                @if($user->is_active)
                                    <button type="submit"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        Nonaktifkan
                                    </button>
                                @else
                                    <button type="submit"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        Aktifkan
                                    </button>
                                @endif
                            </form>

                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    Hapus User
                                </button>
                            </form>
                        @else
                            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    Anda tidak dapat mengedit atau menghapus akun sendiri dari halaman ini.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
