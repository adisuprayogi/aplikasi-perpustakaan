@extends('layouts.admin')

@section('title', 'Laporan Keterlambatan')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Keterlambatan</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Analisis data keterlambatan peminjaman</p>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('reports.overdue') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6 flex flex-wrap gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
    </div>
    <div class="flex items-end">
        <button type="submit" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition font-medium">Filter</button>
    </div>
</form>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-red-100 text-sm">Total Terlambat</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_overdue']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-orange-100 text-sm">Total Denda</p>
        <p class="text-2xl font-bold mt-2">Rp {{ number_format($stats['total_fine_amount'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-green-100 text-sm">Sudah Dibayar</p>
        <p class="text-2xl font-bold mt-2">Rp {{ number_format($stats['total_paid_fines'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-amber-100 text-sm">Sisa Denda</p>
        <p class="text-2xl font-bold mt-2">Rp {{ number_format($stats['total_remaining_fines'], 0, ',', '.') }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rata-rata Hari Terlambat</h3>
    <p class="text-5xl font-bold text-red-600">{{ number_format($stats['average_overdue_days'] ?? 0, 1) }} hari</p>
</div>
@endsection
