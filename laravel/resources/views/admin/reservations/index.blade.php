@extends('layouts.admin')

@section('title', 'Reservasi')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Reservasi Koleksi</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola reservasi anggota untuk koleksi yang sedang dipinjam</p>
                </div>
            </div>
        </div>
        @can('reservations.create')
        <a href="{{ route('reservations.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-amber-700 hover:to-orange-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Reservasi Baru
        </a>
        @endcan
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 sm:gap-4 mb-6">
    <a href="{{ route('reservations.index') }}" class="group relative overflow-hidden @if(request('status') == '') bg-gradient-to-br from-blue-500 to-blue-600 @else bg-white border border-gray-100 @endif rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <p class="text-[10px] sm:text-xs @if(request('status') == '') text-blue-100 @else text-gray-500 @endif font-medium mb-1">Semua</p>
            <p class="text-xl sm:text-2xl font-bold @if(request('status') == '') text-white @else text-gray-900 @endif">{{ $stats['pending'] + $stats['ready'] + $stats['fulfilled'] + $stats['cancelled'] + $stats['expired'] }}</p>
        </div>
    </a>

    <a href="{{ route('reservations.index', ['status' => 'pending']) }}" class="group relative overflow-hidden @if(request('status') == 'pending') bg-gradient-to-br from-amber-500 to-yellow-500 @else bg-white border border-gray-100 @endif rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <p class="text-[10px] sm:text-xs @if(request('status') == 'pending') text-amber-100 @else text-gray-500 @endif font-medium mb-1">Pending</p>
            <p class="text-xl sm:text-2xl font-bold @if(request('status') == 'pending') text-white @else text-gray-900 @endif">{{ $stats['pending'] ?? 0 }}</p>
        </div>
    </a>

    <a href="{{ route('reservations.index', ['status' => 'ready']) }}" class="group relative overflow-hidden @if(request('status') == 'ready') bg-gradient-to-br from-emerald-500 to-green-500 @else bg-white border border-gray-100 @endif rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <p class="text-[10px] sm:text-xs @if(request('status') == 'ready') text-emerald-100 @else text-gray-500 @endif font-medium mb-1">Siap Diambil</p>
            <p class="text-xl sm:text-2xl font-bold @if(request('status') == 'ready') text-white @else text-gray-900 @endif">{{ $stats['ready'] ?? 0 }}</p>
        </div>
    </a>

    <a href="{{ route('reservations.index', ['status' => 'fulfilled']) }}" class="group relative overflow-hidden @if(request('status') == 'fulfilled') bg-gradient-to-br from-blue-500 to-indigo-500 @else bg-white border border-gray-100 @endif rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <p class="text-[10px] sm:text-xs @if(request('status') == 'fulfilled') text-blue-100 @else text-gray-500 @endif font-medium mb-1">Dipenuhi</p>
            <p class="text-xl sm:text-2xl font-bold @if(request('status') == 'fulfilled') text-white @else text-gray-900 @endif">{{ $stats['fulfilled'] ?? 0 }}</p>
        </div>
    </a>

    <a href="{{ route('reservations.index', ['status' => 'cancelled']) }}" class="group relative overflow-hidden @if(request('status') == 'cancelled') bg-gradient-to-br from-gray-500 to-gray-600 @else bg-white border border-gray-100 @endif rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <p class="text-[10px] sm:text-xs @if(request('status') == 'cancelled') text-gray-100 @else text-gray-500 @endif font-medium mb-1">Batal/Exp</p>
            <p class="text-xl sm:text-2xl font-bold @if(request('status') == 'cancelled') text-white @else text-gray-900 @endif">{{ $stats['cancelled'] + $stats['expired'] }}</p>
        </div>
    </a>
</div>

<!-- Search Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 mb-6">
    <form method="GET" action="{{ route('reservations.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 w-full">
            <div class="relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anggota / barcode / judul..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-600/20 focus:border-amber-600 transition text-sm">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition font-medium text-sm whitespace-nowrap">
                Cari
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route('reservations.index') }}" class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition font-medium text-sm whitespace-nowrap text-center">
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
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Koleksi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Barcode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl Reservasi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kadaluarsa</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reservations as $reservation)
                <tr class="hover:bg-gray-50/50 @if($reservation->isExpired()) bg-red-50/50 @endif transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md flex-shrink-0">
                                {{ strtoupper(substr($reservation->member->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $reservation->member->name }}</div>
                                <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $reservation->member->member_no }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 line-clamp-1">{{ $reservation->item->collection->title }}</div>
                        @if($reservation->item->collection->authors)
                        <div class="text-xs text-gray-500 mt-0.5">{{ is_array($reservation->item->collection->authors) ? implode(', ', array_column($reservation->item->collection->authors, 'name')) : $reservation->item->collection->authors }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">
                        {{ $reservation->item->barcode }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $reservation->reservation_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm @if($reservation->isExpired()) font-semibold text-red-600 @else text-gray-700 @endif">
                            {{ $reservation->expiry_date->format('d/m/Y') }}
                        </span>
                        @if($reservation->isExpired())
                        <div class="text-xs text-red-500 mt-1 font-medium">Kedaluwarsa</div>
                        @elseif($reservation->expiry_date->diffInDays(now()) <= 2 && $reservation->status !== 'fulfilled')
                        <div class="text-xs text-amber-600 mt-1 font-medium">
                            {{ $reservation->expiry_date->diffInDays(now()) == 0 ? 'Hari ini' : ($reservation->expiry_date->diffInDays(now()) . ' hari lagi') }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($reservation->status === 'pending')
                            @if($reservation->isExpired())
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Kedaluwarsa</span>
                            </div>
                            @else
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Pending</span>
                            </div>
                            @endif
                        @elseif($reservation->status === 'ready')
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Siap Diambil</span>
                        </div>
                        @elseif($reservation->status === 'fulfilled')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Dipenuhi</span>
                        @elseif($reservation->status === 'cancelled')
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Dibatalkan</span>
                        </div>
                        @else
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">{{ ucfirst($reservation->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @can('reservations.view')
                        <a href="{{ route('reservations.show', $reservation) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg transition">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Belum ada data reservasi</p>
                            <p class="text-xs text-gray-400">Mulai dengan membuat reservasi baru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($reservations as $reservation)
        <div class="p-4 @if($reservation->isExpired()) bg-red-50/50 @endif hover:bg-gray-50/50 transition">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md flex-shrink-0">
                        {{ strtoupper(substr($reservation->member->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-semibold text-gray-900 truncate">{{ $reservation->member->name }}</div>
                        <div class="text-xs text-gray-500 font-mono">{{ $reservation->member->member_no }}</div>
                    </div>
                </div>
                @if($reservation->status === 'pending')
                    @if($reservation->isExpired())
                    <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-red-100 text-red-700 whitespace-nowrap">Kedaluwarsa</span>
                    @else
                    <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700 whitespace-nowrap">Pending</span>
                    @endif
                @elseif($reservation->status === 'ready')
                <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-emerald-100 text-emerald-700 whitespace-nowrap">Siap Diambil</span>
                @elseif($reservation->status === 'fulfilled')
                <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-blue-100 text-blue-700 whitespace-nowrap">Dipenuhi</span>
                @elseif($reservation->status === 'cancelled')
                <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-600 whitespace-nowrap">Dibatalkan</span>
                @endif
            </div>

            <div class="space-y-2 mb-3">
                <div class="text-sm font-medium text-gray-900 line-clamp-2">{{ $reservation->item->collection->title }}</div>
                @if($reservation->item->collection->authors)
                <div class="text-xs text-gray-500">{{ is_array($reservation->item->collection->authors) ? implode(', ', array_column($reservation->item->collection->authors, 'name')) : $reservation->item->collection->authors }}</div>
                @endif
                <div class="text-xs text-gray-600 font-mono">{{ $reservation->item->barcode }}</div>
            </div>

            <div class="flex items-center justify-between gap-3 pt-3 border-t border-gray-100">
                <div class="flex gap-4 text-xs">
                    <div>
                        <span class="text-gray-500">Reservasi:</span>
                        <span class="ml-1 text-gray-900 font-medium">{{ $reservation->reservation_date->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Kadaluarsa:</span>
                        <span class="ml-1 @if($reservation->isExpired()) font-semibold text-red-600 @else text-gray-900 @endif">{{ $reservation->expiry_date->format('d/m/Y') }}</span>
                    </div>
                </div>
                @can('reservations.view')
                <a href="{{ route('reservations.show', $reservation) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg transition flex-shrink-0">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Detail
                </a>
                @endcan
            </div>

            @if($reservation->isExpired())
            <div class="mt-2 text-xs text-red-500 font-medium">Kedaluwarsa</div>
            @elseif($reservation->expiry_date->diffInDays(now()) <= 2 && $reservation->status !== 'fulfilled')
            <div class="mt-2 text-xs text-amber-600 font-medium">
                {{ $reservation->expiry_date->diffInDays(now()) == 0 ? 'Hari ini' : ($reservation->expiry_date->diffInDays(now()) . ' hari lagi') }}
            </div>
            @endif
        </div>
        @empty
        <div class="p-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Belum ada data reservasi</p>
                <p class="text-xs text-gray-400">Mulai dengan membuat reservasi baru</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $reservations->appends(request()->except('page'))->links() }}
</div>
@endsection
