@extends('layouts.admin')

@section('title', $member->name)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $member->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $member->member_no }}</p>
        </div>
        <div class="flex items-center space-x-2">
            @can('members.edit')
            <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            @endcan
            <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pinjaman Aktif</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['active_loans']) }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Terlambat</p>
                <p class="text-2xl font-semibold text-red-600">{{ number_format($stats['overdue_loans']) }}</p>
            </div>
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pinjaman</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_loans']) }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    <a href="{{ route('fines.member', $member) }}" class="block bg-white shadow rounded-lg p-5 hover:bg-gray-50 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Denda</p>
                <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_fines'], 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
        </div>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Member Info -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Anggota</h3>
        <dl class="space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">No. Anggota</dt>
                <dd class="text-sm text-gray-900 font-mono">{{ $member->member_no }}</dd>
            </div>
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">No. Identitas</dt>
                <dd class="text-sm text-gray-900">{{ $member->id_number }}</dd>
            </div>
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Tipe</dt>
                <dd>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($member->type === 'student') bg-blue-100 text-blue-800
                        @elseif($member->type === 'lecturer') bg-green-100 text-green-800
                        @elseif($member->type === 'staff') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($member->type) }}
                    </span>
                </dd>
            </div>
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd>
                    @if($member->status === 'active')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    @elseif($member->status === 'suspended')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Disuspend</span>
                    @elseif($member->status === 'expired')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Kadaluarsa</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Blacklist</span>
                    @endif
                </dd>
            </div>
            @if($member->email)
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="text-sm text-gray-900">{{ $member->email }}</dd>
            </div>
            @endif
            @if($member->phone)
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                <dd class="text-sm text-gray-900">{{ $member->phone }}</dd>
            </div>
            @endif
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Branch</dt>
                <dd class="text-sm text-gray-900">{{ $member->branch->name ?? '-' }}</dd>
            </div>
            <div class="flex items-center justify-between">
                <dt class="text-sm font-medium text-gray-500">Masa Berlaku</dt>
                <dd class="text-sm text-gray-900">
                    @if($member->valid_until)
                        {{ $member->valid_until->format('d/m/Y') }}
                        @if($member->valid_until->isPast())
                            <span class="ml-1 text-xs text-red-600">(Kadaluarsa)</span>
                        @endif
                    @else
                        -
                    @endif
                </dd>
            </div>
        </dl>

        <!-- Actions -->
        @can('members.edit')
        <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
            <button onclick="document.getElementById('renewForm').submit()" class="w-full px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-lg transition">
                Perpanjang Keanggotaan
            </button>
            <button onclick="document.getElementById('suspendForm').submit()" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                Suspend Anggota
            </button>
        </div>

        <form id="renewForm" method="POST" action="{{ route('members.renew', $member) }}" class="hidden">
            @csrf
        </form>
        <form id="suspendForm" method="POST" action="{{ route('members.suspend', $member) }}" class="hidden">
            @csrf
        </form>
        @endcan
    </div>

    <!-- Current Loans -->
    <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pinjaman Aktif</h3>
        @if($currentLoans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Koleksi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barcode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($currentLoans as $loan)
                        <tr class="{{ $loan->isOverdue() ? 'bg-red-50' : '' }}">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $loan->item->collection->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 font-mono">{{ $loan->item->barcode }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="{{ $loan->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                    {{ $loan->due_date->format('d/m/Y') }}
                                </span>
                                @if($loan->isOverdue())
                                <div class="text-xs text-red-500">
                                    {{ $loan->due_date->diffInDays(now()) }} hari
                                </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($loan->isOverdue())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <p>Tidak ada pinjaman aktif</p>
            </div>
        @endif
    </div>
</div>
@endsection
