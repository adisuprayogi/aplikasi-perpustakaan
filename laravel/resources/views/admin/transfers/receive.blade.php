@extends('layouts.admin')

@section('title', 'Receive Transfer')

@section('content')
<div class="py-6">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Receive Transfer</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Konfirmasi penerimaan item yang ditransfer.
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
                    <span class="text-gray-600 dark:text-gray-400">Tanggal Shipped</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->shipped_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Receive Form -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
            <form method="POST" action="{{ route('transfers.receive', $transfer->id) }}">
                @csrf

                <div class="mb-6">
                    <label for="condition" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kondisi Item Saat Diterima (Opsional)
                    </label>
                    <select name="condition"
                            id="condition"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih kondisi...</option>
                        <option value="good">Baik</option>
                        <option value="fair">Cukup</option>
                        <option value="damaged">Rusak Ringan</option>
                        <option value="badly_damaged">Rusak Berat</option>
                    </select>
                </div>

                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                Setelah dikonfirmasi, lokasi item akan diubah ke branch Anda dan status akan menjadi available.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('transfers.show', $transfer->id) }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        Konfirmasi Penerimaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
