@extends('layouts.admin')

@section('title', 'Anggota')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Anggota Perpustakaan</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola data anggota perpustakaan</p>
                </div>
            </div>
        </div>
        @can('members.create')
        <a href="{{ route('members.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Registrasi Anggota
        </a>
        @endcan
    </div>
</div>

<!-- Filters Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 mb-6">
    <form method="GET" action="{{ route('members.index') }}" class="flex flex-col gap-3">
        <div class="w-full">
            <div class="relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/no. anggota..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition text-sm">
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            <div class="flex-1">
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 bg-white transition text-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Disuspend</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>Blacklist</option>
                </select>
            </div>
            <div class="flex-1">
                <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 bg-white transition text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="student" {{ request('type') == 'student' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="lecturer" {{ request('type') == 'lecturer' ? 'selected' : '' }}>Dosen</option>
                    <option value="staff" {{ request('type') == 'staff' ? 'selected' : '' }}>Staf</option>
                    <option value="external" {{ request('type') == 'external' ? 'selected' : '' }}>Eksternal</option>
                </select>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 sm:flex-none px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition font-medium text-sm whitespace-nowrap">
                Filter
            </button>
            @if(request()->hasAny('search', 'status', 'type'))
            <a href="{{ route('members.index') }}" class="flex-1 sm:flex-none px-5 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition font-medium text-sm whitespace-nowrap text-center">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggota</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pinjaman</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Masa Berlaku</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($members as $member)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md flex-shrink-0">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $member->name }}</div>
                                <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $member->member_no }}</div>
                                @if($member->email)
                                <div class="text-xs text-gray-400 mt-0.5">{{ $member->email }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                            @if($member->type === 'student') bg-blue-100 text-blue-800
                            @elseif($member->type === 'lecturer') bg-emerald-100 text-emerald-800
                            @elseif($member->type === 'staff') bg-amber-100 text-amber-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($member->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $member->branch->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900">{{ $member->active_loans ?? 0 }}</div>
                        <div class="text-xs text-gray-500">aktif</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($member->status === 'active')
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                <span class="text-sm font-medium text-emerald-700">Aktif</span>
                            @elseif($member->status === 'suspended')
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                <span class="text-sm font-medium text-red-700">Disuspend</span>
                            @elseif($member->status === 'expired')
                                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                                <span class="text-sm font-medium text-amber-700">Kadaluarsa</span>
                            @else
                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                <span class="text-sm font-medium text-gray-600">{{ ucfirst($member->status) }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($member->valid_until)
                            @if($member->valid_until->isPast())
                                <span class="text-sm font-semibold text-red-600">{{ $member->valid_until->format('d/m/Y') }}</span>
                                <span class="text-xs text-red-500 block mt-0.5">Kadaluarsa</span>
                            @else
                                <span class="text-sm text-gray-700">{{ $member->valid_until->format('d/m/Y') }}</span>
                            @endif
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end space-x-2">
                            @can('members.view')
                            <a href="{{ route('members.show', $member) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                            @endcan
                            @can('members.edit')
                            <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            @endcan
                            @can('members.delete')
                            <form method="POST" action="{{ route('members.destroy', $member) }}" class="inline" onsubmit="return confirm('Hapus anggota ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium rounded-lg transition">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Belum ada data anggota</p>
                            <p class="text-xs text-gray-400">Mulai dengan menambahkan anggota baru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($members as $member)
        <div class="p-4 hover:bg-gray-50/50 transition">
            <div class="flex items-start gap-3 mb-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md flex-shrink-0">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="text-sm font-semibold text-gray-900">{{ $member->name }}</span>
                        @if($member->status === 'active')
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-emerald-100 text-emerald-700 whitespace-nowrap">Aktif</span>
                        @elseif($member->status === 'suspended')
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-red-100 text-red-700 whitespace-nowrap">Disuspend</span>
                        @elseif($member->status === 'expired')
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700 whitespace-nowrap">Kadaluarsa</span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500 font-mono">{{ $member->member_no }}</div>
                    @if($member->email)
                    <div class="text-xs text-gray-400 mt-0.5 truncate">{{ $member->email }}</div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 mb-3 flex-wrap">
                <span class="px-2 py-1 text-[10px] font-semibold rounded-full
                    @if($member->type === 'student') bg-blue-100 text-blue-700
                    @elseif($member->type === 'lecturer') bg-emerald-100 text-emerald-700
                    @elseif($member->type === 'staff') bg-amber-100 text-amber-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ ucfirst($member->type) }}
                </span>
                @if($member->branch)
                <span class="text-xs text-gray-500">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $member->branch->name }}
                </span>
                @endif
                <span class="text-xs text-gray-500">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    {{ $member->active_loans ?? 0 }} Pinjaman
                </span>
            </div>

            @if($member->valid_until)
            <div class="flex items-center gap-1 mb-3 pb-3 border-b border-gray-100">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs @if($member->valid_until->isPast()) font-semibold text-red-600 @else text-gray-600 @endif">
                    @if($member->valid_until->isPast())
                        Kadaluarsa: {{ $member->valid_until->format('d/m/Y') }}
                    @else
                        Berlaku sampai: {{ $member->valid_until->format('d/m/Y') }}
                    @endif
                </span>
            </div>
            @endif

            <div class="flex items-center gap-2 flex-wrap">
                @can('members.view')
                <a href="{{ route('members.show', $member) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat
                </a>
                @endcan
                @can('members.edit')
                <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                @endcan
                @can('members.delete')
                <form method="POST" action="{{ route('members.destroy', $member) }}" class="inline" onsubmit="return confirm('Hapus anggota ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium rounded-lg transition">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
                @endcan
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Belum ada data anggota</p>
                <p class="text-xs text-gray-400">Mulai dengan menambahkan anggota baru</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $members->appends(request()->except('page'))->links() }}
</div>
@endsection
