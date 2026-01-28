@extends('layouts.admin')

@section('title', 'Buat Transfer Baru')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Buat Transfer Baru</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Scan barcode item untuk membuat permintaan transfer</p>
                </div>
            </div>
        </div>
        <a href="{{ route('transfers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="max-w-4xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form method="POST" action="{{ route('transfers.store') }}" x-data="transferForm()" class="p-6">
            @csrf

            <!-- Scan Barcode Section -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Scan Barcode Item
                </label>
                <div class="flex gap-4">
                    <input type="text"
                           id="barcode"
                           name="barcode"
                           x-model="barcode"
                           @keydown.enter.prevent="searchItem"
                           autofocus
                           class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition"
                           placeholder="Scan atau ketik barcode...">
                    <button type="button"
                            @click="searchItem"
                            class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl transition font-medium">
                        Cari Item
                    </button>
                </div>
            </div>

            <!-- Item Result (shown via AJAX) -->
            <div id="item-result" class="mb-6 hidden"></div>

            <!-- Destination Branch -->
            <div class="mb-6">
                <label for="to_branch_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Branch Tujuan
                </label>
                <select name="to_branch_id"
                        id="to_branch_id"
                        required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                    <option value="">Pilih branch tujuan</option>
                    @foreach($branches as $branch)
                        @if($fromBranch && $branch->id == $fromBranch->id)
                            @continue
                        @endif
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
                @if($fromBranch)
                    <p class="mt-2 text-sm text-gray-500">
                        Branch asal: <span class="font-medium text-gray-900">{{ $fromBranch->name }}</span>
                    </p>
                @endif
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan (Opsional)
                </label>
                <textarea name="notes"
                          id="notes"
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition"
                          placeholder="Tambahkan catatan untuk transfer ini..."></textarea>
            </div>

            <!-- Hidden item_id field -->
            <input type="hidden" name="item_id" id="item_id" x-model="itemId">

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('transfers.index') }}" class="px-5 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition font-medium">
                    Batal
                </a>
                <button type="submit"
                        :disabled="!itemId"
                        class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition font-medium disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl">
                    Buat Permintaan Transfer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function transferForm() {
    return {
        barcode: '',
        itemId: null,

        async searchItem() {
            if (!this.barcode) return;

            const toBranchId = document.getElementById('to_branch_id').value;

            try {
                const response = await fetch(`{{ route('transfers.search-items') }}?barcode=${encodeURIComponent(this.barcode)}&to_branch_id=${toBranchId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const html = await response.text();
                const resultDiv = document.getElementById('item-result');
                resultDiv.innerHTML = html;
                resultDiv.classList.remove('hidden');

                // Extract item_id from the response if item is found
                const itemIdField = document.getElementById('selected-item-id');
                if (itemIdField) {
                    this.itemId = itemIdField.value;
                } else {
                    this.itemId = null;
                }
            } catch (error) {
                console.error('Error searching item:', error);
            }
        }
    }
}
</script>
@endpush
@endsection
