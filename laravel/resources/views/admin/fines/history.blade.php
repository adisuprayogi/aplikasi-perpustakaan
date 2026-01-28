@extends('layouts.admin')

@section('title', 'Riwayat Pembayaran Denda')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Pembayaran Denda</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Peminjaman #{{ $loan->id }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($loan->remaining_fine > 0)
            @can('payments.create')
            <a href="{{ route('fines.create', $loan) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0h6"/>
                </svg>
                Bayar Denda
            </a>
            @endcan
            @endif
            <a href="{{ route('loans.show', $loan) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Summary Card -->
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center">
            <p class="text-sm font-medium text-gray-500">Total Denda</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">Rp {{ number_format($loan->fine, 0, ',', '.') }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm font-medium text-gray-500">Dibayar</p>
            <p class="mt-1 text-2xl font-semibold text-green-600">Rp {{ number_format($loan->paid_fine, 0, ',', '.') }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm font-medium text-gray-500">Sisa</p>
            <p class="mt-1 text-2xl font-semibold {{ $loan->remaining_fine > 0 ? 'text-red-600' : 'text-green-600' }}">
                Rp {{ number_format($loan->remaining_fine, 0, ',', '.') }}
            </p>
        </div>
    </div>
</div>

<!-- Payment History -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Semua Pembayaran</h3>
    </div>

    @if($payments->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Referensi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diproses Oleh</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payment->payment_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($payment->payment_method === 'cash') bg-green-100 text-green-800
                            @elseif($payment->payment_method === 'transfer') bg-blue-100 text-blue-800
                            @elseif($payment->payment_method === 'edc') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($payment->payment_method === 'edc') EDC
                            @else ucfirst($payment->payment_method) @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                        {{ $payment->reference_number ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $payment->processedBy->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $payment->notes ?? '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-12 text-center">
        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-sm text-gray-500">Belum ada riwayat pembayaran.</p>
    </div>
    @endif
</div>
@endsection
