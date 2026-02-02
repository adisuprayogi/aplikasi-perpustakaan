@extends('layouts.admin')

@section('title', 'Transfer Antar Branch')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4 4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Transfer Antar Branch</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola transfer item antar cabang</p>
                </div>
            </div>
        </div>
        <a href="{{ route('transfers.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Transfer Baru
        </a>
    </div>
</div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 gap-3 sm:gap-6 mb-6">
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-3 sm:p-6 text-white shadow-lg">
                <p class="text-yellow-100 text-[10px] sm:text-sm">Pending Transfers</p>
                <p class="text-xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ number_format($statistics['pending_transfers']) }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-3 sm:p-6 text-white shadow-lg">
                <p class="text-blue-100 text-[10px] sm:text-sm">Shipped (In Transit)</p>
                <p class="text-xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ number_format($statistics['shipped_transfers']) }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('transfers.index') }}" class="flex flex-col gap-3 sm:gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition text-sm">
                            <option value="">Semua</option>
                            <option value="pending" {{ $filters['status'] === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="shipped" {{ $filters['status'] === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="received" {{ $filters['status'] === 'received' ? 'selected' : '' }}>Received</option>
                            <option value="cancelled" {{ $filters['status'] === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari Branch</label>
                        <select name="from_branch_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition text-sm">
                            <option value="">Semua</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $filters['from_branch_id'] == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ke Branch</label>
                        <select name="to_branch_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition text-sm">
                            <option value="">Semua</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $filters['to_branch_id'] == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Barcode atau judul..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition text-sm">
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition font-medium text-sm whitespace-nowrap">Filter</button>
                    <a href="{{ route('transfers.index') }}" class="px-5 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition font-medium text-sm whitespace-nowrap">Reset</a>
                </div>
            </form>
        </div>

        <!-- Transfers Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dari Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ke Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transfers as $transfer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ optional($transfer->requested_at)->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $transfer->item->barcode ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $transfer->item->collection->title ?? 'Unknown Collection' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transfer->fromBranch->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transfer->toBranch->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'shipped' => 'bg-blue-100 text-blue-800',
                                            'received' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$transfer->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($transfer->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('transfers.show', $transfer->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                    Tidak ada data transfer.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($transfers as $transfer)
                <div class="p-4 hover:bg-gray-50/50 transition">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="text-xs text-gray-500 mb-1">{{ optional($transfer->requested_at)->format('d M Y') ?? '-' }}</div>
                            <div class="text-sm font-semibold text-gray-900">{{ $transfer->item->barcode ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 line-clamp-1">{{ $transfer->item->collection->title ?? 'Unknown Collection' }}</div>
                        </div>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'shipped' => 'bg-blue-100 text-blue-800',
                                'received' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-[10px] font-medium rounded-full {{ $statusColors[$transfer->status] ?? 'bg-gray-100 text-gray-800' }} whitespace-nowrap">
                            {{ ucfirst($transfer->status) }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2 text-xs text-gray-600 mb-3 pb-3 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Dari:</span>
                        <span class="truncate">{{ $transfer->fromBranch->name ?? '-' }}</span>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        <span class="font-medium text-gray-700">Ke:</span>
                        <span class="truncate">{{ $transfer->toBranch->name ?? '-' }}</span>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('transfers.show', $transfer->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Detail
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4 4"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Tidak ada data transfer</p>
                        <p class="text-xs text-gray-400">Mulai dengan membuat transfer baru</p>
                    </div>
                </div>
                @endforelse
            </div>

            @if($transfers->hasPages())
                <div class="bg-gray-50 px-4 sm:px-6 py-4">
                    {{ $transfers->appends(array_filter($filters, fn($v) => $v !== null && $v !== ''))->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
