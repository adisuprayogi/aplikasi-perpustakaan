@extends('layouts.admin')

@section('title', 'Laporan Anggota')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Anggota</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Statistik keanggotaan perpustakaan</p>
                </div>
            </div>
        </div>
        <a href="{{ route('reports.members.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-blue-100 text-sm">Total Anggota</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_members']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-green-100 text-sm">Anggota Aktif</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['active_members']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-red-100 text-sm">Tidak Aktif</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['inactive_members']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-amber-100 text-sm">Ditangguhkan</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['suspended_members']) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Anggota per Jenis</h3>
        <div class="space-y-3">
            @foreach($stats['by_type'] as $type => $count)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                    <span class="text-2xl font-bold text-indigo-600">{{ number_format($count) }}</span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Keanggotaan</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-4 bg-green-50 rounded-xl">
                <span class="text-gray-700">Anggota Baru (bulan ini)</span>
                <span class="text-2xl font-bold text-green-600">{{ number_format($stats['new_this_month']) }}</span>
            </div>
            <div class="flex justify-between items-center p-4 bg-red-50 rounded-xl">
                <span class="text-gray-700">Masa Berlaku Habis</span>
                <span class="text-2xl font-bold text-red-600">{{ number_format($stats['expired_members']) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
