@if($item)
    <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->collection->title }}</h4>
                <div class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    <p><span class="font-medium">Barcode:</span> {{ $item->barcode }}</p>
                    <p><span class="font-medium">Call Number:</span> {{ $item->call_number }}</p>
                    <p><span class="font-medium">Lokasi Saat Ini:</span> {{ $item->branch->name }}</p>
                    <p><span class="font-medium">Status:</span>
                        <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                            @if($item->status === 'available') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                            @elseif($item->status === 'borrowed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                            {{ ucfirst($item->status) }}
                        </span>
                    </p>
                </div>

                @if($item->status !== 'available')
                    <p class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
                        <span class="font-medium">Perhatian:</span> Item ini sedang tidak tersedia.
                    </p>
                @endif

                @if($item->branch_id === $toBranch->id)
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                        <span class="font-medium">Error:</span> Item sudah berada di branch tujuan.
                    </p>
                @endif

                @if($item->branch_id !== $toBranch->id && $item->status === 'available')
                    <input type="hidden" id="selected-item-id" value="{{ $item->id }}">
                @endif
            </div>
        </div>
    </div>
@else
    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div>
                <h4 class="font-medium text-red-900 dark:text-red-200">Item Tidak Ditemukan</h4>
                <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                    Barcode tidak ditemukan dalam sistem. Silakan periksa kembali.
                </p>
            </div>
        </div>
    </div>
@endif
