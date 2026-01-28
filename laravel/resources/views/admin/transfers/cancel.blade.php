@extends('layouts.admin')

@section('title', 'Cancel Transfer')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Cancel Transfer</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Batalkan permintaan transfer ini.
            </p>
        </div>

        <!-- Transfer Summary -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Transfer</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Barcode</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->item->barcode }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Judul</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->item->collection->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Dari Branch</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->fromBranch->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Ke Branch</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->toBranch->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Tanggal Request</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->requested_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Cancel Form -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
            <form method="POST" action="{{ route('transfers.cancel', $transfer->id) }}">
                @csrf

                <div class="mb-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Alasan Pembatalan (Opsional)
                    </label>
                    <textarea name="reason"
                              id="reason"
                              rows="3"
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Jelaskan alasan pembatalan..."></textarea>
                </div>

                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-200">
                                Tindakan ini tidak dapat dibatalkan. Transfer yang dibatalkan tidak dapat dilanjutkan.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('transfers.show', $transfer->id) }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                        Konfirmasi Pembatalan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
