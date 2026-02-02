@extends('layouts.admin')

@section('title', 'Laporan Perbandingan Cabang')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Perbandingan Cabang</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Perbandingan performa antar cabang perpustakaan</p>
                </div>
            </div>
        </div>
        <a href="{{ route('reports.branches.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-blue-100 text-sm">Total Cabang</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($comparison['summary']['total_branches']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-green-100 text-sm">Total Anggota</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($comparison['summary']['total_members_all']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-purple-100 text-sm">Total Item</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($comparison['summary']['total_items_all']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
        <p class="text-orange-100 text-sm">Total Peminjaman</p>
        <p class="text-3xl font-bold mt-2">{{ number_format($comparison['summary']['total_loans_all']) }}</p>
    </div>
</div>

<!-- Branch Comparison Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cabang</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggota</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Peminjaman</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Terlambat</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Bulan Ini</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($comparison['branches'] as $branch)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $branch['name'] }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $branch['type'] === 'main' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $branch['type'] === 'main' ? 'Pusat' : 'Cabang' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm text-gray-900">{{ number_format($branch['active_members']) }}</div>
                            <div class="text-xs text-gray-500">dari {{ number_format($branch['total_members']) }}</div>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">
                            {{ number_format($branch['total_items']) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm text-gray-900">{{ number_format($branch['active_loans']) }}</div>
                            <div class="text-xs text-gray-500">aktif</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $branch['overdue_loans'] > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ number_format($branch['overdue_loans']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <div class="text-green-600">{{ number_format($branch['loans_this_month']) }} <span class="text-gray-400">pinjam</span></div>
                            <div class="text-blue-600">{{ number_format($branch['returns_this_month']) }} <span class="text-gray-400">kembali</span></div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p>Belum ada data cabang</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <!-- Members by Branch Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Anggota per Cabang</h3>
        <div class="h-64">
            <canvas id="membersChart"></canvas>
        </div>
    </div>

    <!-- Loans by Branch Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Peminjaman per Cabang</h3>
        <div class="h-64">
            <canvas id="loansChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Members by Branch Chart
const ctxMembers = document.getElementById('membersChart');
if (ctxMembers) {
    const branches = @js($comparison['branches'] ?? []);
    const labels = branches.map(b => b.name);
    const data = branches.map(b => b.active_members);

    new Chart(ctxMembers, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Anggota Aktif',
                data: data,
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
                }
            }
        }
    });
}

// Loans by Branch Chart
const ctxLoans = document.getElementById('loansChart');
if (ctxLoans) {
    const branches = @js($comparison['branches'] ?? []);
    const labels = branches.map(b => b.name);
    const loansData = branches.map(b => b.loans_this_month);
    const returnsData = branches.map(b => b.returns_this_month);

    new Chart(ctxLoans, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Peminjaman',
                    data: loansData,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1
                },
                {
                    label: 'Pengembalian',
                    data: returnsData,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
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
</script>
@endpush
