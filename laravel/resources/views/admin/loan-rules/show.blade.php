@extends('layouts.admin')

@section('title', 'Detail Aturan Peminjaman')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Aturan Peminjaman</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $loanRule->member_type_label }} - {{ $loanRule->collectionType->name ?? 'Semua Tipe' }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @can('loan-rules.edit')
            <a href="{{ route('loan-rules.edit', $loanRule) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            @endcan
            <a href="{{ route('loan-rules.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Rule Details -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Aturan</h3>

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Tipe Anggota</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($loanRule->member_type === 'student') bg-blue-100 text-blue-800
                            @elseif($loanRule->member_type === 'lecturer') bg-green-100 text-green-800
                            @elseif($loanRule->member_type === 'staff') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $loanRule->member_type_label }}
                        </span>
                    </dd>
                </div>

                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Tipe Koleksi</dt>
                    <dd class="text-sm text-gray-900">{{ $loanRule->collectionType->name ?? 'Semua Tipe' }}</dd>
                </div>

                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Lama Pinjam</dt>
                    <dd class="text-sm text-gray-900">{{ $loanRule->loan_period }} hari</dd>
                </div>

                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Maksimal Pinjaman</dt>
                    <dd class="text-sm text-gray-900">{{ $loanRule->max_loans }} item</dd>
                </div>

                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Denda Per Hari</dt>
                    <dd class="text-sm text-gray-900">Rp {{ number_format($loanRule->fine_per_day, 0, ',', '.') }}</dd>
                </div>

                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Bisa Diperpanjang</dt>
                    <dd class="text-sm">
                        @if($loanRule->is_renewable)
                            <span class="text-green-600 font-medium">Ya (max {{ $loanRule->renew_limit }}x)</span>
                        @else
                            <span class="text-red-600 font-medium">Tidak</span>
                        @endif
                    </dd>
                </div>

                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Hitung Denda</dt>
                    <dd class="text-sm text-gray-900">
                        {{ $loanRule->is_fine_by_calendar ? 'Termasuk hari libur' : 'Hari kerja saja' }}
                    </dd>
                </div>

                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="text-sm">
                        @if($loanRule->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                Nonaktif
                            </span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Masa Pinjaman</span>
                    <span class="text-sm font-medium text-gray-900">{{ $loanRule->loan_period }} hari</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Maks. Item</span>
                    <span class="text-sm font-medium text-gray-900">{{ $loanRule->max_loans }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Denda/Hari</span>
                    <span class="text-sm font-medium text-gray-900">Rp {{ number_format($loanRule->fine_per_day, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @can('loan-rules.delete')
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>
            <form method="POST" action="{{ route('loan-rules.destroy', $loanRule) }}" onsubmit="return confirm('Hapus aturan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                    Hapus Aturan
                </button>
            </form>
        </div>
        @endcan
    </div>
</div>
@endsection
