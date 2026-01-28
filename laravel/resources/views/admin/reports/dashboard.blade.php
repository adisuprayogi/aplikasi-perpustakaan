@extends('layouts.admin')

@section('title', 'Dashboard Laporan')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Laporan</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Ringkasan statistik perpustakaan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-blue-100 text-sm font-medium">Total Anggota</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_members']) }}</p>
        <p class="text-blue-100 text-xs mt-1">{{ number_format($stats['active_members']) }} aktif</p>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-purple-100 text-sm font-medium">Total Koleksi</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_collections']) }}</p>
        <p class="text-purple-100 text-xs mt-1">{{ number_format($stats['total_items']) }} item</p>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-green-100 text-sm font-medium">Peminjaman Aktif</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['active_loans']) }}</p>
        <p class="text-green-100 text-xs mt-1">{{ number_format($stats['loans_today']) }} hari ini</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-red-100 text-sm font-medium">Peminjaman Terlambat</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['overdue_loans']) }}</p>
        <p class="text-red-100 text-xs mt-1">Rp {{ number_format($stats['total_fines'], 0, ',', '.') }} denda</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-gray-500 text-sm">Reservasi Pending</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_reservations']) }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-gray-500 text-sm">Pengembalian Hari Ini</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['returns_today']) }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-gray-500 text-sm">Anggota Baru (Bulan Ini)</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['new_members_this_month']) }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Koleksi Terpopuler</h3>
    <div class="space-y-3">
        @forelse($stats['popular_items'] as $index => $item)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $item['title'] }}</p>
                    </div>
                </div>
                <p class="text-lg font-bold text-indigo-600">{{ $item['loan_count'] }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">Belum ada data peminjaman</p>
        @endforelse
    </div>
</div>
@endsection
