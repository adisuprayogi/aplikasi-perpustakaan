@extends('layouts.admin')

@section('title', 'Reservasi Baru')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reservasi Baru</h1>
            <p class="mt-1 text-sm text-gray-500">Buat reservasi untuk koleksi yang sedang dipinjam</p>
        </div>
        <a href="{{ route('reservations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="max-w-2xl">
    <form method="POST" action="{{ route('reservations.store') }}" class="bg-white shadow rounded-lg p-6">
        @csrf

        <!-- Member Selection -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Anggota</label>
            <div class="flex gap-2">
                <input type="text" id="member-search" placeholder="Scan kartu / masukkan nomor anggota..."
                    class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"
                    x-data="{}"
                    x-init="
                        $el.addEventListener('input', debounce(async (e) => {
                            const search = e.target.value;
                            if (search.length < 2) return;
                            const response = await fetch('{{ route('api.search.reservation-member') }}?search=' + search);
                            const data = await response.json();
                            memberResults = data;
                        }, 300));
                    "
                    @keyup.enter="if(memberResults.length === 1) selectMember(memberResults[0])">
                <button type="button" @click="document.getElementById('member-search').focus()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Cari
                </button>
            </div>
            <!-- Member Results Dropdown -->
            <div x-data="{ memberResults: [] }" x-show="memberResults.length > 0" class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                <template x-for="member in memberResults" :key="member.id">
                    <a href="#" @click.prevent="selectMember(member)" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900" x-text="member.name"></p>
                                <p class="text-xs text-gray-500" x-text="member.member_no + ' - ' + member.type"></p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800" x-text="member.status"></span>
                        </div>
                    </a>
                </template>
            </div>

            <!-- Selected Member Display -->
            <div x-data="{ selectedMember: null }" id="selected-member-display" class="hidden mt-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900" x-text="selectedMember?.name"></p>
                        <p class="text-xs text-gray-500" x-text="selectedMember?.member_no + ' - ' + selectedMember?.type"></p>
                    </div>
                    <button type="button" @click="clearMember()" class="text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <input type="hidden" name="member_id" id="member_id" x-model="selectedMember?.id">
            </div>
        </div>

        <!-- Item Selection -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Item (yang sedang dipinjam)</label>
            <div class="flex gap-2">
                <input type="text" id="item-search" placeholder="Scan barcode item..."
                    class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"
                    x-data="{}"
                    x-init="
                        $el.addEventListener('input', debounce(async (e) => {
                            const search = e.target.value;
                            if (search.length < 2) return;
                            const response = await fetch('{{ route('api.search.reservation-item') }}?search=' + search);
                            const data = await response.json();
                            itemResults = data;
                        }, 300));
                    "
                    @keyup.enter="if(itemResults.length === 1) selectItem(itemResults[0])">
                <button type="button" @click="document.getElementById('item-search').focus()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Cari
                </button>
            </div>
            <!-- Item Results Dropdown -->
            <div x-data="{ itemResults: [] }" x-show="itemResults.length > 0" class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                <template x-for="item in itemResults" :key="item.id">
                    <a href="#" @click.prevent="selectItem(item)" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900" x-text="item.collection?.title"></p>
                                <p class="text-xs text-gray-500" x-text="item.barcode + ' - ' + (item.branch?.name || '')"></p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800" x-text="item.status"></span>
                        </div>
                    </a>
                </template>
            </div>

            <!-- Selected Item Display -->
            <div x-data="{ selectedItem: null }" id="selected-item-display" class="hidden mt-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900" x-text="selectedItem?.collection?.title"></p>
                        <p class="text-xs text-gray-500" x-text="selectedItem?.barcode + ' - ' + (selectedItem?.branch?.name || '')"></p>
                    </div>
                    <button type="button" @click="clearItem()" class="text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <input type="hidden" name="item_id" id="item_id" x-model="selectedItem?.id">
            </div>
        </div>

        <!-- Branch Selection -->
        <div class="mb-6">
            <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch Pengambilan</label>
            <select name="branch_id" id="branch_id" required
                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                <option value="">Pilih Branch</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ auth()->user()->branch_id == $branch->id ? 'selected' : '' }}>
                    {{ $branch->name }}
                </option>
                @endforeach
            </select>
            @error('branch_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes -->
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
            <textarea name="notes" id="notes" rows="3"
                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"
                placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
            @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Info Box -->
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm text-yellow-800">
                        <strong>Info Reservasi:</strong>
                    </p>
                    <ul class="mt-1 text-sm text-yellow-700 list-disc list-inside">
                        <li>Reservasi berlaku selama 7 hari</li>
                        <li>Maksimal 3 reservasi aktif per anggota</li>
                        <li>Anggota akan dinotifikasi saat item tersedia</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('reservations.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-lg transition-all duration-200">
                Buat Reservasi
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function selectMember(member) {
    document.getElementById('member_id').value = member.id;
    const display = document.getElementById('selected-member-display');
    display._x_dataStack = [{ selectedMember: member }];
    display.classList.remove('hidden');
    document.getElementById('member-search').value = '';
}

function clearMember() {
    document.getElementById('member_id').value = '';
    const display = document.getElementById('selected-member-display');
    display.classList.add('hidden');
}

function selectItem(item) {
    document.getElementById('item_id').value = item.id;
    const display = document.getElementById('selected-item-display');
    display._x_dataStack = [{ selectedItem: item }];
    display.classList.remove('hidden');
    document.getElementById('item-search').value = '';
}

function clearItem() {
    document.getElementById('item_id').value = '';
    const display = document.getElementById('selected-item-display');
    display.classList.add('hidden');
}
</script>
@endpush
@endsection
