@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')

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
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Peminjaman</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Analisis data peminjaman perpustakaan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('reports.loans') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6 flex flex-wrap gap-4">
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
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-gray-500 text-sm">Total Peminjaman</p>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_loans']) }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-gray-500 text-sm">Peminjaman Aktif</p>
        <p class="text-3xl font-bold text-green-600">{{ number_format($stats['active_loans']) }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-gray-500 text-sm">Sudah Dikembalikan</p>
        <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['completed_loans']) }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <p class="text-gray-500 text-sm">Total Perpanjangan</p>
        <p class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_renewals']) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Per Jenis Anggota</h3>
        <div class="space-y-3">
            @foreach($stats['loans_by_member_type'] as $type => $data)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                    <span class="text-gray-900 font-medium">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Per Jenis Koleksi</h3>
        <div class="space-y-3">
            @foreach($stats['loans_by_collection_type'] as $type => $data)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-700">{{ ucfirst($type) }}</span>
                    <span class="text-gray-900 font-medium">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
