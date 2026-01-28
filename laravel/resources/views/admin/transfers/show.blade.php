@extends('layouts.admin')

@section('title', 'Detail Transfer')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Transfer</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    ID Transfer: #{{ $transfer->id }}
                </p>
            </div>
            <a href="{{ route('transfers.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                Kembali
            </a>
        </div>

        <!-- Status Card -->
        @php
            $statusColors = [
                'pending' => 'bg-yellow-500 to-yellow-600',
                'shipped' => 'bg-blue-500 to-blue-600',
                'received' => 'bg-green-500 to-green-600',
                'cancelled' => 'bg-red-500 to-red-600',
            ];
        @endphp
        <div class="bg-gradient-to-br {{ $statusColors[$transfer->status] ?? 'bg-gray-500 to-gray-600' }} rounded-2xl p-6 text-white shadow-lg mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">Status</p>
                    <p class="text-3xl font-bold mt-2">{{ ucfirst($transfer->status) }}</p>
                </div>
                @if($transfer->isPending())
                    <div class="flex gap-2">
                        <a href="{{ route('transfers.ship', $transfer->id) }}" class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-gray-100 font-medium">
                            Ship
                        </a>
                        <a href="{{ route('transfers.cancel-form', $transfer->id) }}" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            Cancel
                        </a>
                    </div>
                @elseif($transfer->isShipped())
                    <div>
                        <a href="{{ route('transfers.receive-form', $transfer->id) }}" class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-gray-100 font-medium">
                            Receive
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Transfer Details -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Transfer</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Tanggal Request</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ optional($transfer->requested_at)->format('d M Y H:i') ?? '-' }}</span>
                    </div>
                    @if($transfer->shipped_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Tanggal Shipped</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ optional($transfer->shipped_at)->format('d M Y H:i') ?? '-' }}</span>
                        </div>
                    @endif
                    @if($transfer->received_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Tanggal Diterima</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ optional($transfer->received_at)->format('d M Y H:i') ?? '-' }}</span>
                        </div>
                    @endif
                    @if($transfer->notes)
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Catatan</span>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $transfer->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Item Details -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Item</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Barcode</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->item->barcode ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Judul</span>
                        <span class="text-gray-900 dark:text-white font-medium text-right max-w-xs truncate">{{ $transfer->item->collection->title ?? 'Unknown Collection' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Call Number</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->item->call_number ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Branch Information -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Branch</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Dari</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $transfer->fromBranch->name ?? 'Unknown Branch' }}</p>
                        </div>
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ke</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $transfer->toBranch->name ?? 'Unknown Branch' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- People -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Personel</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Requested By</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->requestedBy->name ?? 'Unknown' }}</span>
                    </div>
                    @if($transfer->shipped_by && $transfer->shippedBy)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Shipped By</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->shippedBy->name ?? 'Unknown' }}</span>
                        </div>
                    @endif
                    @if($transfer->received_by && $transfer->receivedBy)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Received By</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->receivedBy->name ?? 'Unknown' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Transfer History -->
        @if($itemTransfers && $itemTransfers->count() > 0)
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Riwayat Transfer Item Ini</h3>
                <div class="space-y-3">
                    @foreach($itemTransfers as $history)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $history->fromBranch->name ?? 'Unknown' }} → {{ $history->toBranch->name ?? 'Unknown' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ optional($history->requested_at)->format('d M Y H:i') ?? '-' }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                @if($history->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                @elseif($history->status == 'shipped') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($history->status == 'received') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                                {{ ucfirst($history->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
