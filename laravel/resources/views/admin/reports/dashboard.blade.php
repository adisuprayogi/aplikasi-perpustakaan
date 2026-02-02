@extends('layouts.admin')

@section('title', 'Dashboard Laporan')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@endpush

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

<!-- Circulation Trends Chart -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Tren Sirkulasi (12 Bulan Terakhir)</h3>
    <div class="h-80">
        <canvas id="circulationChart"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Collections by Type Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Koleksi berdasarkan Tipe</h3>
        <div class="h-64">
            <canvas id="collectionTypeChart"></canvas>
        </div>
    </div>

    <!-- Members by Type Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Anggota berdasarkan Tipe</h3>
        <div class="h-64">
            <canvas id="memberTypeChart"></canvas>
        </div>
    </div>
</div>

<!-- Popular Items Chart -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Koleksi Terpopuler</h3>
    <div class="h-80">
        <canvas id="popularItemsChart"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Popular Items List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Koleksi Terpopuler</h3>
        <div class="space-y-3">
            @forelse($stats['popular_items'] as $index => $item)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
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

    <!-- Quick Stats -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Ringkasan Cepat</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Total Anggota</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($stats['total_members']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Total Koleksi</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($stats['total_collections']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Peminjaman Selesai</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($stats['active_loans'] + $stats['returns_today']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Circulation Trends Chart
const ctxCirculation = document.getElementById('circulationChart');
if (ctxCirculation) {
    const circulationData = @js($circulationTrends);

    new Chart(ctxCirculation, {
        type: 'line',
        data: {
            labels: circulationData.map(d => d.month),
            datasets: [
                {
                    label: 'Peminjaman',
                    data: circulationData.map(d => d.loans),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Pengembalian',
                    data: circulationData.map(d => d.returns),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Terlambat',
                    data: circulationData.map(d => d.overdue),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Collections by Type Chart (Pie)
const ctxCollectionType = document.getElementById('collectionTypeChart');
if (ctxCollectionType) {
    const collectionData = @js($collectionStats['by_type'] ?? []);

    const labels = Object.keys(collectionData).map(type => {
        const typeMap = { 'book': 'Buku', 'journal': 'Jurnal', 'reference': 'Referensi', 'other': 'Lainnya' };
        return typeMap[type] || type;
    });
    const data = Object.values(collectionData).map(item => item.count);

    new Chart(ctxCollectionType, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(139, 92, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
}

// Members by Type Chart (Pie)
const ctxMemberType = document.getElementById('memberTypeChart');
if (ctxMemberType) {
    const memberData = @js($memberStats['by_type'] ?? []);

    const labels = Object.keys(memberData).map(type => {
        const typeMap = { 'student': 'Mahasiswa', 'lecturer': 'Dosen', 'staff': 'Staff', 'external': 'Eksternal' };
        return typeMap[type] || type;
    });
    const data = Object.values(memberData);

    new Chart(ctxMemberType, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
}

// Popular Items Chart (Bar)
const ctxPopularItems = document.getElementById('popularItemsChart');
if (ctxPopularItems) {
    const popularItems = @js($stats['popular_items'] ?? []);

    new Chart(ctxPopularItems, {
        type: 'bar',
        data: {
            labels: popularItems.map(item => item.title.length > 30 ? item.title.substring(0, 30) + '...' : item.title),
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: popularItems.map(item => item.loan_count),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
}
</script>
@endpush
