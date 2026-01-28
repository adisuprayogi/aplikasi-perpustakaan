@extends('layouts.admin')

@section('title', 'Peminjaman Baru')

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
                    <h1 class="text-2xl font-bold text-gray-900">Peminjaman Baru</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Input data peminjaman koleksi</p>
                </div>
            </div>
        </div>
        <a href="{{ route('loans.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="loanForm()">
    <!-- Loan Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form method="POST" action="{{ route('loans.store') }}" class="p-6" @submit.prevent="submitForm">
            @csrf

            <div class="space-y-6">
                <!-- Member Search -->
                <div>
                    <label for="member_search" class="block text-sm font-medium text-gray-700 mb-2">Cari Anggota</label>
                    <div class="relative">
                        <input type="text" id="member_search" x-model="memberSearch" @input="searchMember"
                            class="w-full px-4 py-2.5 pl-10 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition"
                            placeholder="No. Anggota atau NIK">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Search Results -->
                    <div x-show="memberResults.length > 0" class="mt-2 border border-gray-200 rounded-xl divide-y divide-gray-200 max-h-48 overflow-y-auto">
                        <template x-for="member in memberResults" :key="member.id">
                            <div @click="selectMember(member)" class="p-3 hover:bg-gray-50 cursor-pointer transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900" x-text="member.name"></p>
                                        <p class="text-xs text-gray-500" x-text="member.member_no"></p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700" x-text="member.type"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Selected Member -->
                    <div x-show="selectedMember" class="mt-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900" x-text="selectedMember?.name"></p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span x-text="selectedMember?.member_no"></span> ·
                                    <span x-text="selectedMember?.branch?.name"></span>
                                </p>
                            </div>
                            <button type="button" @click="clearMember()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="member_id" x-model="selectedMemberId">
                    @error('member_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Item Search -->
                <div>
                    <label for="item_search" class="block text-sm font-medium text-gray-700 mb-2">Cari Item / Barcode</label>
                    <div class="relative">
                        <input type="text" id="item_search" x-model="itemSearch" @input="searchItem"
                            class="w-full px-4 py-2.5 pl-10 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition"
                            placeholder="Scan barcode atau ketik">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>

                    <!-- Search Results -->
                    <div x-show="itemResults.length > 0" class="mt-2 border border-gray-200 rounded-xl divide-y divide-gray-200 max-h-48 overflow-y-auto">
                        <template x-for="item in itemResults" :key="item.id">
                            <div @click="selectItem(item)" class="p-3 hover:bg-gray-50 cursor-pointer transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900" x-text="item.collection?.title"></p>
                                        <p class="text-xs text-gray-500" x-text="item.barcode"></p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-50 text-green-700">Tersedia</span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Selected Item -->
                    <div x-show="selectedItem" class="mt-3 p-4 bg-green-50 rounded-xl border border-green-100">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900" x-text="selectedItem?.collection?.title"></p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span class="font-mono" x-text="selectedItem?.barcode"></span> ·
                                    <span x-text="selectedItem?.collection?.collection_type?.code"></span>
                                </p>
                            </div>
                            <button type="button" @click="clearItem()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="item_id" x-model="selectedItemId">
                    @error('item_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Branch -->
                <div>
                    <label for="loan_branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch Peminjaman</label>
                    <select id="loan_branch_id" name="loan_branch_id" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                        <option value="">Pilih Branch</option>
                        @foreach($branches ?? [] as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('loan_branch_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('loans.index') }}" class="px-5 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition font-medium">
                    Batal
                </a>
                <button type="submit" :disabled="!canSubmit"
                    class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:from-gray-400 disabled:to-gray-400 text-white font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl">
                    Proses Peminjaman
                </button>
            </div>
        </form>
    </div>

    <!-- Preview & Info -->
    <div class="space-y-6">
        <!-- Loan Period Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Periode Peminjaman</h3>
            <div x-show="selectedItem" class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Tipe Koleksi</span>
                    <span class="font-medium text-gray-900" x-text="selectedItem?.collection?.collection_type?.name"></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Periode Pinjam</span>
                    <span class="font-medium text-gray-900" x-text="(selectedItem?.collection?.collection_type?.loan_period || 7) + ' hari'"></span>
                </div>
                <div class="pt-3 border-t border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Tanggal Jatuh Tempo</p>
                    <p class="text-lg font-semibold text-gray-900" x-text="calculateDueDate()"></p>
                </div>
            </div>
            <div x-show="!selectedItem" class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm">Pilih item untuk melihat periode peminjaman</p>
            </div>
        </div>

        <!-- Member Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Anggota</h3>
            <div x-show="selectedMember" class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700" x-text="selectedMember?.status"></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Pinjaman Aktif</span>
                    <span class="font-medium text-gray-900" x-text="selectedMember?.active_loans || 0"></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Total Pinjaman</span>
                    <span class="font-medium text-gray-900" x-text="selectedMember?.total_loans || 0"></span>
                </div>
                <div x-show="selectedMember?.valid_until" class="pt-3 border-t border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Berlaku Hingga</p>
                    <p class="text-sm font-medium text-gray-900" x-text="formatDate(selectedMember?.valid_until)"></p>
                </div>
            </div>
            <div x-show="!selectedMember" class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <p class="text-sm">Pilih anggota untuk melihat status</p>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function loanForm() {
    return {
        memberSearch: '',
        itemSearch: '',
        memberResults: [],
        itemResults: [],
        selectedMember: null,
        selectedItem: null,
        selectedMemberId: null,
        selectedItemId: null,

        get canSubmit() {
            return this.selectedMemberId && this.selectedItemId;
        },

        searchMember() {
            if (this.memberSearch.length < 2) {
                this.memberResults = [];
                return;
            }

            fetch('{{ route('api.search.member') }}?search=' + encodeURIComponent(this.memberSearch))
                .then(response => response.json())
                .then(data => {
                    this.memberResults = data;
                });
        },

        selectMember(member) {
            this.selectedMember = member;
            this.selectedMemberId = member.id;
            this.memberSearch = member.member_no;
            this.memberResults = [];
        },

        clearMember() {
            this.selectedMember = null;
            this.selectedMemberId = null;
            this.memberSearch = '';
            this.memberResults = [];
        },

        searchItem() {
            if (this.itemSearch.length < 2) {
                this.itemResults = [];
                return;
            }

            fetch('{{ route('api.search.item') }}?search=' + encodeURIComponent(this.itemSearch))
                .then(response => response.json())
                .then(data => {
                    this.itemResults = data;
                });
        },

        selectItem(item) {
            this.selectedItem = item;
            this.selectedItemId = item.id;
            this.itemSearch = item.barcode;
            this.itemResults = [];
        },

        clearItem() {
            this.selectedItem = null;
            this.selectedItemId = null;
            this.itemSearch = '';
            this.itemResults = [];
        },

        calculateDueDate() {
            if (!this.selectedItem) return '-';

            const period = this.selectedItem?.collection?.collection_type?.loan_period || 7;
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + period);

            return dueDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            return new Date(dateStr).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        },

        submitForm() {
            if (!this.canSubmit) return;
            event.target.form.submit();
        }
    }
}
</script>
@endpush
@endsection
