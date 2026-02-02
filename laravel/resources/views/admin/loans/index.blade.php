@extends('layouts.admin')

@section('title', 'Sirkulasi')

@section('content')
<!-- Page Header -->
<div class="mb-6 lg:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4 4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Sirkulasi Peminjaman</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola data peminjaman dan pengembalian</p>
                </div>
            </div>
        </div>
        @can('loans.create')
        <a href="{{ route('loans.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl min-h-[48px]">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="hidden sm:inline">Peminjaman Baru</span>
            <span class="sm:hidden">Baru</span>
        </a>
        @endcan
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-3 gap-3 lg:gap-4 mb-6">
    <a href="{{ route('loans.index') }}" class="group relative overflow-hidden @if(request('status') == '') bg-gradient-to-br from-blue-500 to-blue-600 @else bg-white border border-gray-100 @endif rounded-2xl p-3 lg:p-5 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 w-16 h-16 lg:w-24 lg:h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2 lg:mb-3">
                <p class="text-xs lg:text-sm @if(request('status') == '') text-blue-100 @else text-gray-500 @endif font-medium truncate">Semua</p>
                <div class="w-8 h-8 lg:w-10 lg:h-10 @if(request('status') == '') bg-white/20 @else bg-blue-100 @endif rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 @if(request('status') == '') text-white @else text-blue-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl lg:text-3xl font-bold @if(request('status') == '') text-white @else text-gray-900 @endif">{{ $stats['active'] + $stats['overdue'] + $stats['returned'] ?? 0 }}</p>
        </div>
    </a>

    <a href="{{ route('loans.index', ['status' => 'active']) }}" class="group relative overflow-hidden @if(request('status') == 'active') bg-gradient-to-br from-emerald-500 to-emerald-600 @else bg-white border border-gray-100 @endif rounded-2xl p-3 lg:p-5 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 w-16 h-16 lg:w-24 lg:h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2 lg:mb-3">
                <p class="text-xs lg:text-sm @if(request('status') == 'active') text-emerald-100 @else text-gray-500 @endif font-medium truncate">Aktif</p>
                <div class="w-8 h-8 lg:w-10 lg:h-10 @if(request('status') == 'active') bg-white/20 @else bg-emerald-100 @endif rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 @if(request('status') == 'active') text-white @else text-emerald-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4 4"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl lg:text-3xl font-bold @if(request('status') == 'active') text-white @else text-gray-900 @endif">{{ $stats['active'] ?? 0 }}</p>
        </div>
    </a>

    <a href="{{ route('loans.index', ['status' => 'overdue']) }}" class="group relative overflow-hidden @if(request('status') == 'overdue') bg-gradient-to-br from-red-500 to-rose-600 @else bg-white border border-gray-100 @endif rounded-2xl p-3 lg:p-5 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 w-16 h-16 lg:w-24 lg:h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2 lg:mb-3">
                <p class="text-xs lg:text-sm @if(request('status') == 'overdue') text-red-100 @else text-gray-500 @endif font-medium truncate">Terlambat</p>
                <div class="w-8 h-8 lg:w-10 lg:h-10 @if(request('status') == 'overdue') bg-white/20 @else bg-red-100 @endif rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 @if(request('status') == 'overdue') text-white @else text-red-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl lg:text-3xl font-bold @if(request('status') == 'overdue') text-white @else text-gray-900 @endif">{{ $stats['overdue'] ?? 0 }}</p>
        </div>
    </a>
</div>

<!-- Search Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-5 mb-6">
    <form method="GET" action="{{ route('loans.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <div class="relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / barcode..." inputmode="search"
                    class="w-full pl-10 pr-4 py-3 text-base border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 transition min-h-[48px]">
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 sm:flex-none px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition font-medium min-h-[48px]">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('loans.index') }}" class="flex-1 sm:flex-none px-5 py-3 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition font-medium min-h-[48px]">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Mobile Cards Layout -->
<div class="md:hidden space-y-4 mb-6">
    @forelse($loans as $loan)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden {{ $loan->isOverdue() ? 'ring-2 ring-red-200' : '' }}">
        <!-- Header -->
        <div class="{{ $loan->isOverdue() ? 'bg-red-50' : 'bg-gray-50' }} px-4 py-3 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md flex-shrink-0">
                        {{ strtoupper(substr($loan->member->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $loan->member->name }}</p>
                        <p class="text-xs text-gray-500 font-mono">{{ $loan->member->member_no }}</p>
                    </div>
                </div>
                @if($loan->status === 'active')
                    @if($loan->isOverdue())
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Terlambat</span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                    @endif
                @elseif($loan->status === 'returned')
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Dikembalikan</span>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 space-y-3">
            <!-- Book Info -->
            <div>
                <p class="text-sm font-medium text-gray-900 line-clamp-2">{{ $loan->item->collection->title }}</p>
                @if($loan->item->collection->authors)
                <p class="text-xs text-gray-500 mt-1">{{ is_array($loan->item->collection->authors) ? implode(', ', array_column($loan->item->collection->authors, 'name')) : $loan->item->collection->authors }}</p>
                @endif
                <p class="text-xs text-gray-500 font-mono mt-1">{{ $loan->item->barcode }}</p>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-xl px-3 py-2">
                    <p class="text-xs text-gray-500">Tgl Pinjam</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $loan->loan_date->format('d/m/Y') }}</p>
                </div>
                <div class="bg-{{ $loan->isOverdue() ? 'red' : 'gray' }}-50 rounded-xl px-3 py-2">
                    <p class="text-xs text-gray-500">Jatuh Tempo</p>
                    <p class="text-sm font-semibold {{ $loan->isOverdue() ? 'text-red-700' : 'text-gray-900' }}">{{ $loan->due_date->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Overdue Info -->
            @if($loan->isOverdue())
            <div class="bg-red-50 rounded-xl px-3 py-2">
                <p class="text-xs text-red-600">{{ $loan->days_overdue }} hari terlambat</p>
                @if($loan->calculated_fine > 0)
                <p class="text-sm font-semibold text-red-700">Denda: Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Action -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
            @can('loans.view')
            <a href="{{ route('loans.show', $loan) }}" class="block w-full text-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition">
                Lihat Detail / Proses Pengembalian
            </a>
            @endcan
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4 4"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-gray-600 mb-1">Belum ada data peminjaman</p>
        <p class="text-xs text-gray-400">Mulai dengan membuat peminjaman baru</p>
    </div>
    @endforelse
</div>

<!-- Desktop Table Layout -->
<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggota</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Koleksi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Barcode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl Pinjam</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($loans as $loan)
                <tr class="{{ $loan->isOverdue() ? 'bg-red-50/50' : 'hover:bg-gray-50/50' }} transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md flex-shrink-0">
                                {{ strtoupper(substr($loan->member->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $loan->member->name }}</div>
                                <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $loan->member->member_no }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 line-clamp-1">{{ $loan->item->collection->title }}</div>
                        @if($loan->item->collection->authors)
                        <div class="text-xs text-gray-500 mt-0.5">{{ is_array($loan->item->collection->authors) ? implode(', ', array_column($loan->item->collection->authors, 'name')) : $loan->item->collection->authors }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">
                        {{ $loan->item->barcode }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $loan->loan_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm {{ $loan->isOverdue() ? 'font-semibold text-red-600' : 'text-gray-700' }}">
                            {{ $loan->due_date->format('d/m/Y') }}
                        </span>
                        @if($loan->isOverdue())
                        <div class="text-xs text-red-500 mt-1 font-medium">
                            {{ $loan->days_overdue }} hari terlambat
                            @if($loan->calculated_fine > 0)
                            <span class="block mt-0.5">Denda: Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($loan->status === 'active')
                            @if($loan->isOverdue())
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Terlambat</span>
                            </div>
                            @else
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                            </div>
                            @endif
                        @elseif($loan->status === 'returned')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Dikembalikan</span>
                        @else
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">{{ ucfirst($loan->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @can('loans.view')
                        <a href="{{ route('loans.show', $loan) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Detail
                        </a>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4 4"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Belum ada data peminjaman</p>
                            <p class="text-xs text-gray-400">Mulai dengan membuat peminjaman baru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $loans->appends(request()->except('page'))->links() }}
</div>
@endsection
