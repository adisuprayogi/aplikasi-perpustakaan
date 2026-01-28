@extends('layouts.admin')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman</h1>
            <p class="mt-1 text-sm text-gray-500">Transaksi #{{ $loan->id }}</p>
        </div>
        <div class="flex items-center space-x-2">
            @if($loan->status === 'active')
                @can('loans.renew')
                <button onclick="document.getElementById('renewForm').submit()" class="inline-flex items-center px-4 py-2 border border-blue-700 rounded-lg text-sm font-medium text-blue-700 bg-white hover:bg-blue-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Perpanjang
                </button>
                <form id="renewForm" method="POST" action="{{ route('loans.renew', $loan) }}" class="hidden">
                    @csrf
                    @method('PUT')
                </form>
                @endcan
            @endif
            <a href="{{ route('loans.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Status Alert -->
@if($loan->status === 'active' && $loan->isOverdue())
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
    <div class="flex">
        <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <h3 class="text-sm font-medium text-red-800">Peminjaman Terlambat</h3>
            <p class="mt-1 text-sm text-red-700">
                Terlambat {{ $loan->days_overdue }} hari. Perkiraan denda: <strong>Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</strong>
            </p>
        </div>
    </div>
</div>
@elseif($loan->status === 'returned')
<div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
    <div class="flex">
        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <h3 class="text-sm font-medium text-green-800">Dikembalikan</h3>
            <p class="mt-1 text-sm text-green-700">
                Buku telah dikembalikan pada {{ $loan->return_date->format('d/m/Y') }}
                @if($loan->fine > 0)
                    · Denda: Rp {{ number_format($loan->fine, 0, ',', '.') }}
                @endif
            </p>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Collection Info -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Koleksi</h3>
            <div class="flex items-start space-x-4">
                @if($loan->item->collection->cover_image)
                <img src="{{ $loan->item->collection->cover_image }}" alt="{{ $loan->item->collection->title }}"
                    class="w-24 h-36 object-cover rounded-lg flex-shrink-0">
                @endif
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900">{{ $loan->item->collection->title }}</h4>
                    @if($loan->item->collection->authors)
                    <p class="text-sm text-gray-600 mt-1">
                        {{ is_array($loan->item->collection->authors) ? implode(', ', array_column($loan->item->collection->authors, 'name')) : $loan->item->collection->authors }}
                    </p>
                    @endif
                    <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500">
                        <span class="font-mono">{{ $loan->item->barcode }}</span>
                        <span>·</span>
                        <span>{{ $loan->item->collection->collectionType->code }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Info -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Anggota</h3>
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">{{ $loan->member->name }}</h4>
                    <p class="text-sm text-gray-500 mt-1">{{ $loan->member->member_no }}</p>
                </div>
                <div class="text-right">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($loan->member->type === 'student') bg-blue-100 text-blue-800
                        @elseif($loan->member->type === 'lecturer') bg-green-100 text-green-800
                        @elseif($loan->member->type === 'staff') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($loan->member->type) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Loan Details -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Peminjaman</h3>
            <dl class="space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Tanggal Pinjam</dt>
                    <dd class="text-sm text-gray-900">{{ $loan->loan_date->format('d/m/Y') }}</dd>
                </div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Jatuh Tempo</dt>
                    <dd class="text-sm {{ $isOverdue ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                        {{ $loan->due_date->format('d/m/Y') }}
                        @if($isOverdue)
                        <span class="block text-xs text-red-500">Terlambat {{ $daysOverdue }} hari</span>
                        @endif
                    </dd>
                </div>
                @if($loan->return_date)
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Tanggal Kembali</dt>
                    <dd class="text-sm text-gray-900">{{ $loan->return_date->format('d/m/Y') }}</dd>
                </div>
                @endif
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Branch Pinjam</dt>
                    <dd class="text-sm text-gray-900">{{ $loan->loanBranch->name }}</dd>
                </div>
                @if($loan->returnBranch)
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Branch Kembali</dt>
                    <dd class="text-sm text-gray-900">{{ $loan->returnBranch->name }}</dd>
                </div>
                @endif
                @if($loan->renewal_count > 0)
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Jumlah Perpanjangan</dt>
                    <dd class="text-sm text-gray-900">{{ $loan->renewal_count }}x</dd>
                </div>
                @endif
                <div class="flex items-center justify-between">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd>
                        @if($loan->status === 'active')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @elseif($loan->status === 'returned')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Dikembalikan</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Return Form -->
        @if($loan->status === 'active')
        @can('loans.return')
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengembalian</h3>
            <form method="POST" action="{{ route('loans.return', $loan) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="return_branch_id" class="block text-sm font-medium text-gray-700 mb-1.5">Branch Pengembalian</label>
                        <select id="return_branch_id" name="return_branch_id" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            @foreach($branches ?? [] as $branch)
                                <option value="{{ $branch->id }}" {{ $loan->loan_branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="condition" class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Buku</label>
                        <select id="condition" name="condition"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="good">Baik</option>
                            <option value="damaged">Rusak</option>
                            <option value="lost">Hilang</option>
                        </select>
                    </div>
                </div>

                @if($loan->isOverdue())
                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl" x-data="{ fineOption: 'pay_full', showPaymentAmount: false }">
                    <p class="text-sm text-yellow-800 mb-3">
                        Denda keterlambatan: <strong>Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</strong>
                        ({{ $loan->days_overdue }} hari)
                    </p>

                    <!-- Fine Payment Options -->
                    <div class="space-y-2 mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Pembayaran Denda</label>

                        <label class="flex items-center p-3 bg-white border border-yellow-300 rounded-lg cursor-pointer hover:bg-yellow-100 transition">
                            <input type="radio" name="fine_option" value="pay_full" class="mr-3" checked x-model="fineOption">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Bayar Lunas</span>
                                <p class="text-xs text-gray-500">Bayar penuh denda saat ini</p>
                            </div>
                            <span class="text-sm font-bold text-green-700">Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</span>
                        </label>

                        <label class="flex items-center p-3 bg-white border border-yellow-300 rounded-lg cursor-pointer hover:bg-yellow-100 transition">
                            <input type="radio" name="fine_option" value="pay_partial" class="mr-3" x-model="fineOption" @click="showPaymentAmount = true">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Bayar Sebagian</span>
                                <p class="text-xs text-gray-500">Bayar sebagian, sisanya ditagih nanti</p>
                            </div>
                        </label>

                        <div x-show="fineOption === 'pay_partial'" class="ml-6 mt-2" x-transition>
                            <label class="block text-sm text-gray-600 mb-1">Jumlah Pembayaran</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-medium">Rp</span>
                                <input type="number" name="payment_amount" min="1" max="{{ $loan->calculated_fine }}"
                                    class="w-full pl-10 px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                    placeholder="0" required>
                            </div>
                        </div>

                        <label class="flex items-center p-3 bg-white border border-yellow-300 rounded-lg cursor-pointer hover:bg-yellow-100 transition">
                            <input type="radio" name="fine_option" value="defer" class="mr-3" x-model="fineOption" @click="showPaymentAmount = false">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Tangguhkan</span>
                                <p class="text-xs text-gray-500">Denda ditambahkan ke tunggakan anggota</p>
                            </div>
                        </label>
                    </div>

                    <!-- Payment Method (jika pilih bayar) -->
                    <div x-show="fineOption !== 'defer'" class="pt-3 border-t border-yellow-300" x-transition>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="edc">EDC/Kartu</option>
                        </select>
                    </div>

                    <!-- Reference Number (untuk transfer/EDC) -->
                    <div x-show="fineOption !== 'defer'" class="mt-3" x-transition>
                        <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-1.5">No. Referensi</label>
                        <input type="text" id="payment_reference" name="payment_reference"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                            placeholder="Nomor referensi/bukti pembayaran (opsional)">
                    </div>
                </div>
                @endif

                <button type="submit" class="w-full px-6 py-3 bg-green-700 hover:bg-green-800 text-white font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 transition-all duration-200">
                    Proses Pengembalian
                </button>
            </form>
        </div>
        @endcan
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Fine Info -->
        @if($loan->fine > 0 || $loan->calculated_fine > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-yellow-800">Total Denda</p>
                    <p class="text-2xl font-bold text-yellow-900 mt-1">Rp {{ number_format($loan->fine ?: $loan->calculated_fine, 0, ',', '.') }}</p>
                </div>
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.99-1"/>
                </svg>
            </div>

            @if($loan->paid_fine > 0 || $loan->fine > 0)
            <div class="space-y-2 mb-4 pt-4 border-t border-yellow-300">
                @if($loan->fine > 0 && $loan->paid_fine > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-yellow-700">Dibayar:</span>
                    <span class="font-medium text-yellow-900">Rp {{ number_format($loan->paid_fine, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($loan->remaining_fine > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-yellow-700">Sisa:</span>
                    <span class="font-bold text-red-600">Rp {{ number_format($loan->remaining_fine, 0, ',', '.') }}</span>
                </div>
                @elseif($loan->fine > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-yellow-700">Status:</span>
                    <span class="font-bold text-green-600">Lunas</span>
                </div>
                @endif
            </div>
            @endif

            <div class="space-y-2">
                @if($loan->remaining_fine > 0)
                @can('payments.create')
                <a href="{{ route('fines.create', $loan) }}" class="flex items-center justify-center w-full px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Bayar Denda
                </a>
                @endcan
                @endif

                @if($loan->payments()->count() > 0)
                <a href="{{ route('fines.history', $loan) }}" class="block w-full px-4 py-2 border border-yellow-400 text-yellow-800 text-sm font-medium rounded-lg hover:bg-yellow-100 transition text-center">
                    Riwayat Pembayaran
                </a>
                @endif

                @if($loan->remaining_fine > 0)
                @can('payments.waive')
                <button onclick="if(confirm('Hapus denda ini? Denda akan dihapus sepenuhnya.')) document.getElementById('waiveForm').submit()" class="block w-full px-4 py-2 border border-red-300 text-red-700 text-sm font-medium rounded-lg hover:bg-red-50 transition text-center">
                    Hapus Denda
                </button>
                <form id="waiveForm" method="POST" action="{{ route('fines.waive', $loan) }}" class="hidden">
                    @csrf
                </form>
                @endcan
                @endif
            </div>
        </div>
        @endif

        <!-- Processing Info -->
        <div class="bg-white shadow rounded-lg p-5">
            <h4 class="text-sm font-medium text-gray-500 mb-3">Diproses Oleh</h4>
            <p class="text-sm text-gray-900">{{ $loan->processedBy->name }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $loan->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Actions -->
        @if($loan->status === 'active')
        <div class="bg-white shadow rounded-lg p-5">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Aksi Cepat</h4>
            <div class="space-y-2">
                @can('loans.renew')
                <button onclick="document.getElementById('renewForm').submit()" class="w-full px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-lg transition">
                    Perpanjang Peminjaman
                </button>
                @endcan
                <a href="{{ route('members.show', $loan->member) }}" class="block w-full px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition text-center">
                    Lihat Profil Anggota
                </a>
                <a href="{{ route('collections.show', $loan->item->collection) }}" class="block w-full px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition text-center">
                    Lihat Detail Koleksi
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
