@extends('layouts.admin')

@section('title', 'Cetak Label')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Cetak Label</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Cetak barcode dan QR code label untuk item</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('collections.labels') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6 flex flex-wrap gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cabang</label>
        <select name="branch_id" class="px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-600/20 focus:border-amber-600 transition bg-white">
            <option value="">Semua Cabang</option>
            @foreach(auth()->user()->branch_id ? [auth()->user()->branch] : \App\Models\Branch::all() as $branch)
            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-600/20 focus:border-amber-600 transition bg-white">
            <option value="">Semua Status</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
            <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
            <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reservasi</option>
        </select>
    </div>
    <div class="flex items-end gap-2">
        <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl transition font-medium">Filter</button>
        <a href="{{ route('collections.labels') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition font-medium">Reset</a>
    </div>
</form>

<!-- Items List -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Item</h3>
        <button onclick="window.print()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Label Terpilih
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">
                        <input type="checkbox" id="selectAll" onchange="toggleAll(this)" class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-600">
                    </th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Kode</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Judul</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Call Number</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 item-row" data-item-id="{{ $item->id }}">
                        <td class="py-3 px-4">
                            <input type="checkbox" class="item-checkbox w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-600" value="{{ $item->id }}">
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-900 font-mono">{{ $item->barcode }}</td>
                        <td class="py-3 px-4">
                            <p class="text-sm text-gray-900 line-clamp-1">{{ $item->collection->title }}</p>
                            <p class="text-xs text-gray-500">{{ $item->collection->authors[0] ?? '-' }}</p>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600 font-mono">{{ $item->collection->call_number ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($item->status === 'available')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-green-50 text-green-700">Tersedia</span>
                            @elseif($item->status === 'borrowed')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-blue-50 text-blue-700">Dipinjam</span>
                            @elseif($item->status === 'reserved')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-amber-50 text-amber-700">Reservasi</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-gray-50 text-gray-700">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('collection-items.label', $item) }}" target="_blank" class="text-amber-600 hover:text-amber-700 text-sm font-medium">Preview Label</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-500">Tidak ada item</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
    <div class="mt-6">
        {{ $items->appends(request()->except('page'))->links() }}
    </div>
    @endif
</div>

<!-- Hidden Labels Container -->
<div id="labelsContainer" class="hidden print:block"></div>

<style>
@media print {
    body > *:not(#labelsContainer) {
        display: none !important;
    }
    #labelsContainer {
        display: block !important;
    }
    .label {
        page-break-inside: avoid;
        margin: 5px;
        float: left;
    }
}
</style>

@push('scripts')
<script>
function toggleAll(checkbox) {
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.checked = checkbox.checked;
    });
}

// Generate labels for selected items
document.querySelector('button[onclick="window.print()"]')?.addEventListener('click', function(e) {
    const selectedIds = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);

    if (selectedIds.length === 0) {
        e.preventDefault();
        alert('Pilih minimal satu item untuk dicetak');
        return;
    }

    // Fetch labels and load into container
    const container = document.getElementById('labelsContainer');
    container.innerHTML = '<p class="text-center mb-4">Memuat label...</p>';

    // Fetch each label (in production, batch this)
    let loadedCount = 0;
    selectedIds.forEach((id, index) => {
        fetch(`/collection-items/${id}/label`)
            .then(response => response.text())
            .then(html => {
                const temp = document.createElement('div');
                temp.innerHTML = html;
                const label = temp.querySelector('.label');
                if (label) {
                    container.appendChild(label);
                }
                loadedCount++;
                if (loadedCount === selectedIds.length) {
                    // All labels loaded, ready to print
                    container.querySelector('p')?.remove();
                }
            });
    });
});
</script>
@endpush
@endsection