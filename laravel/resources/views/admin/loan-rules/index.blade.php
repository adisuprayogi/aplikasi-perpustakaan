@extends('layouts.admin')

@section('title', 'Aturan Peminjaman')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Aturan Peminjaman</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola aturan peminjaman berdasarkan tipe anggota</p>
                </div>
            </div>
        </div>
        @can('loan-rules.create')
        <a href="{{ route('loan-rules.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-cyan-700 hover:to-blue-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Aturan
        </a>
        @endcan
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 mb-6">
    <form method="GET" action="{{ route('loan-rules.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <select name="member_type" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent text-sm">
                <option value="">Semua Tipe Anggota</option>
                @foreach($memberTypes as $key => $label)
                <option value="{{ $key }}" {{ request('member_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm whitespace-nowrap">
                Filter
            </button>
            @if(request('member_type'))
            <a href="{{ route('loan-rules.index') }}" class="flex-1 sm:flex-none px-4 py-2 text-gray-600 hover:text-gray-900 transition text-sm whitespace-nowrap text-center">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Anggota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Koleksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lama Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maks. Pinjaman</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda/Hari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perpanjangan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($loanRules as $rule)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($rule->member_type === 'student') bg-blue-100 text-blue-800
                            @elseif($rule->member_type === 'lecturer') bg-green-100 text-green-800
                            @elseif($rule->member_type === 'staff') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $rule->member_type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $rule->collectionType->name ?? 'Semua Tipe' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $rule->loan_period }} hari
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $rule->max_loans }} item
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Rp {{ number_format($rule->fine_per_day, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($rule->is_renewable)
                            <span class="text-green-600">Ya (max {{ $rule->renew_limit }}x)</span>
                        @else
                            <span class="text-red-600">Tidak</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($rule->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-3">
                            @can('loan-rules.view')
                            <a href="{{ route('loan-rules.show', $rule) }}" class="text-blue-700 hover:text-blue-900 font-medium">Lihat</a>
                            @endcan
                            @can('loan-rules.edit')
                            <a href="{{ route('loan-rules.edit', $rule) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                            @endcan
                            @can('loan-rules.delete')
                            <form method="POST" action="{{ route('loan-rules.destroy', $rule) }}" class="inline" onsubmit="return confirm('Hapus aturan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Belum ada aturan peminjaman.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($loanRules as $rule)
        <div class="p-4 hover:bg-gray-50/50 transition">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($rule->member_type === 'student') bg-blue-100 text-blue-800
                            @elseif($rule->member_type === 'lecturer') bg-green-100 text-green-800
                            @elseif($rule->member_type === 'staff') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $rule->member_type_label }}
                        </span>
                        @if($rule->is_active)
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-600">Nonaktif</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-900">{{ $rule->collectionType->name ?? 'Semua Tipe' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3 pb-3 border-b border-gray-100">
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <div class="text-[10px] text-gray-500 uppercase tracking-wide mb-1">Lama Pinjam</div>
                    <div class="text-sm font-semibold text-gray-900">{{ $rule->loan_period }} hari</div>
                </div>
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <div class="text-[10px] text-gray-500 uppercase tracking-wide mb-1">Maks. Pinjaman</div>
                    <div class="text-sm font-semibold text-gray-900">{{ $rule->max_loans }} item</div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-100">
                <div>
                    <div class="text-[10px] text-gray-500 uppercase tracking-wide">Denda/Hari</div>
                    <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($rule->fine_per_day, 0, ',', '.') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-[10px] text-gray-500 uppercase tracking-wide">Perpanjangan</div>
                    @if($rule->is_renewable)
                        <div class="text-sm font-semibold text-green-600">Ya (max {{ $rule->renew_limit }}x)</div>
                    @else
                        <div class="text-sm font-semibold text-red-600">Tidak</div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @can('loan-rules.view')
                <a href="{{ route('loan-rules.show', $rule) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat
                </a>
                @endcan
                @can('loan-rules.edit')
                <a href="{{ route('loan-rules.edit', $rule) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                @endcan
                @can('loan-rules.delete')
                <form method="POST" action="{{ route('loan-rules.destroy', $rule) }}" class="inline" onsubmit="return confirm('Hapus aturan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium rounded-lg transition">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
                @endcan
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Belum ada aturan peminjaman</p>
                <p class="text-xs text-gray-400">Mulai dengan menambahkan aturan baru</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
