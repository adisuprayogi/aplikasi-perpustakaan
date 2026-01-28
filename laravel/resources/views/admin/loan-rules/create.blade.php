@extends('layouts.admin')

@section('title', 'Tambah Aturan Peminjaman')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Aturan Peminjaman</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Buat aturan peminjaman baru</p>
                </div>
            </div>
        </div>
        <a href="{{ route('loan-rules.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <form method="POST" action="{{ route('loan-rules.store') }}" class="p-6">
        @csrf

        <div class="space-y-6">
            <!-- Member Type & Collection Type -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tipe Anggota & Koleksi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Member Type -->
                    <div>
                        <label for="member_type" class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Anggota</label>
                        <select id="member_type" name="member_type" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            @foreach($memberTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('member_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Collection Type -->
                    <div>
                        <label for="collection_type_id" class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Koleksi</label>
                        <select id="collection_type_id" name="collection_type_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="">Semua Tipe Koleksi</option>
                            @foreach($collectionTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Kosongkan untuk aturan default semua tipe koleksi</p>
                        @error('collection_type_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Loan Settings -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Peminjaman</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Loan Period -->
                    <div>
                        <label for="loan_period" class="block text-sm font-medium text-gray-700 mb-1.5">Lama Pinjam</label>
                        <input type="number" id="loan_period" name="loan_period" value="7" min="1" max="365" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                        <p class="mt-1 text-xs text-gray-500">Dalam hari</p>
                        @error('loan_period')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Loans -->
                    <div>
                        <label for="max_loans" class="block text-sm font-medium text-gray-700 mb-1.5">Maksimal Pinjaman</label>
                        <input type="number" id="max_loans" name="max_loans" value="3" min="1" max="50" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                        <p class="mt-1 text-xs text-gray-500">Jumlah item maksimal</p>
                        @error('max_loans')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fine Per Day -->
                    <div>
                        <label for="fine_per_day" class="block text-sm font-medium text-gray-700 mb-1.5">Denda Per Hari</label>
                        <input type="number" id="fine_per_day" name="fine_per_day" value="0" min="0" max="999999.99" step="0.01" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                        <p class="mt-1 text-xs text-gray-500">Dalam Rupiah</p>
                        @error('fine_per_day')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Renew Limit -->
                    <div>
                        <label for="renew_limit" class="block text-sm font-medium text-gray-700 mb-1.5">Batas Perpanjangan</label>
                        <input type="number" id="renew_limit" name="renew_limit" value="2" min="0" max="10" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                        <p class="mt-1 text-xs text-gray-500">0 = tidak bisa diperpanjang</p>
                        @error('renew_limit')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Options -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Opsi Tambahan</h3>
                <div class="space-y-4">
                    <!-- Is Renewable -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_renewable" name="is_renewable" value="1" checked
                            class="w-4 h-4 text-blue-700 border-gray-300 rounded focus:ring-blue-700">
                        <label for="is_renewable" class="ml-3 text-sm text-gray-700">Bisa diperpanjang</label>
                    </div>

                    <!-- Is Fine By Calendar -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_fine_by_calendar" name="is_fine_by_calendar" value="1" checked
                            class="w-4 h-4 text-blue-700 border-gray-300 rounded focus:ring-blue-700">
                        <label for="is_fine_by_calendar" class="ml-3 text-sm text-gray-700">Hitung denda termasuk hari libur</label>
                    </div>

                    <!-- Is Active -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" checked
                            class="w-4 h-4 text-blue-700 border-gray-300 rounded focus:ring-blue-700">
                        <label for="is_active" class="ml-3 text-sm text-gray-700">Aktif</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end space-x-4">
            <a href="{{ route('loan-rules.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">Batal</a>
            <button type="submit" class="px-6 py-2 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-xl transition-all duration-200">
                Simpan Aturan
            </button>
        </div>
    </form>
</div>
@endsection
