@extends('layouts.admin')

@section('title', 'Bayar Denda')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pembayaran Denda</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Catat pembayaran denda untuk peminjaman #{{ $loan->id }}</p>
                </div>
            </div>
        </div>
        <a href="{{ route('loans.show', $loan) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

<!-- Loan Info -->
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="flex-1">
            <h3 class="text-sm font-medium text-yellow-800">Detail Peminjaman</h3>
            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-yellow-700">Anggota:</span>
                    <span class="ml-2 font-medium text-yellow-900">{{ $loan->member->name }}</span>
                </div>
                <div>
                    <span class="text-yellow-700">Koleksi:</span>
                    <span class="ml-2 font-medium text-yellow-900 line-clamp-1">{{ $loan->item->collection->title }}</span>
                </div>
                <div>
                    <span class="text-yellow-700">Jatuh Tempo:</span>
                    <span class="ml-2 font-medium text-yellow-900">{{ $loan->due_date->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="text-yellow-700">Terlambat:</span>
                    <span class="ml-2 font-medium text-yellow-900">{{ $loan->days_overdue }} hari</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Payment Form -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Form Pembayaran</h3>

            <form method="POST" action="{{ route('fines.store', $loan) }}">
                @csrf

                <div class="space-y-6">
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Pembayaran</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">Rp</span>
                            <input type="number" id="amount" name="amount" min="1" max="{{ $loan->remaining_fine }}" required
                                class="w-full pl-12 px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition text-lg font-semibold"
                                placeholder="0">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Sisa denda: <strong class="text-red-600">Rp {{ number_format($loan->remaining_fine, 0, ',', '.') }}</strong>
                        </p>
                        @error('amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="">Pilih metode pembayaran</option>
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="edc">EDC/Kartu</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reference Number -->
                    <div>
                        <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-1.5">No. Referensi</label>
                        <input type="text" id="payment_reference" name="payment_reference" value="{{ old('payment_reference') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="Nomor referensi/bukti pembayaran">
                        <p class="mt-1 text-xs text-gray-500">Opsional: isi untuk transfer atau EDC</p>
                        @error('payment_reference')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('loans.show', $loan) }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium rounded-xl transition-all duration-200">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary -->
    <div class="space-y-6">
        <!-- Fine Summary -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Denda</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Total Denda</span>
                    <span class="text-sm font-medium text-gray-900">Rp {{ number_format($loan->fine, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Sudah Dibayar</span>
                    <span class="text-sm font-medium text-green-600">Rp {{ number_format($loan->paid_fine, 0, ',', '.') }}</span>
                </div>
                <div class="border-t pt-4">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-900">Sisa Denda</span>
                        <span class="text-lg font-bold text-red-600">Rp {{ number_format($loan->remaining_fine, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        @if($loan->payments()->count() > 0)
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Pembayaran</h3>
            <div class="space-y-3">
                @foreach($loan->payments()->latest()->limit(5)->get() as $payment)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->payment_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($payment->payment_method === 'cash') bg-green-100 text-green-800
                            @elseif($payment->payment_method === 'transfer') bg-blue-100 text-blue-800
                            @elseif($payment->payment_method === 'edc') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($payment->payment_method === 'edc') EDC
                            @else ucfirst($payment->payment_method) @endif
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @if($loan->payments()->count() > 5)
            <a href="{{ route('fines.history', $loan) }}" class="block mt-4 text-center text-sm text-blue-700 hover:text-blue-900 font-medium">
                Lihat Semua Riwayat →
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
