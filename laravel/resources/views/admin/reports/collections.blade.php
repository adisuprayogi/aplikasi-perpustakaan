@extends('layouts.admin')

@section('title', 'Laporan Koleksi')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Koleksi</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Statistik koleksi perpustakaan</p>
                </div>
            </div>
        </div>
        <a href="{{ route('reports.collections.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-purple-100 text-sm">Total Judul</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_collections']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-indigo-100 text-sm">Total Item</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_items']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-blue-100 text-sm">Rata-rata Item/Judul</p>
        <p class="text-3xl font-bold mt-2">{{ $stats['total_collections'] > 0 ? number_format($stats['total_items'] / $stats['total_collections'], 1) : 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Koleksi per Jenis</h3>
        <div class="space-y-3">
            @foreach($stats['by_type'] as $type => $data)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-900">{{ ucfirst($type) }}</span>
                    <span class="text-gray-900 font-bold">{{ $data['count'] }} judul / {{ $data['items'] }} item</span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Koleksi Terpopuler</h3>
        <div class="space-y-2">
            @foreach($stats['most_borrowed'] as $index => $item)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-700">{{ $index + 1 }}. {{ $item['title'] }}</span>
                    <span class="text-green-600 font-bold">{{ $item['loan_count'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
