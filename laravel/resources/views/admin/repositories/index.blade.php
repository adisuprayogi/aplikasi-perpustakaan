@extends('layouts.admin')

@section('title', 'Institutional Repository')

@section('content')
<!-- Page Header -->
<div class="mb-6 lg:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Institutional Repository</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola karya ilmiah kampus</p>
                </div>
            </div>
        </div>
        <a href="{{ route('repositories.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Repository
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-gradient-to-br from-purple-500 to-violet-500 rounded-2xl p-4 lg:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium opacity-90">Total</span>
            <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-2xl lg:text-3xl font-bold">{{ $statistics['total'] }}</p>
    </div>

    <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-4 lg:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs lg:text-sm font-medium opacity-90">Menunggu</span>
            <svg class="w-4 h-4 lg:w-5 lg:h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl lg:text-3xl font-bold">{{ $statistics['pending'] }}</p>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl p-4 lg:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs lg:text-sm font-medium opacity-90">Disetujui</span>
            <svg class="w-4 h-4 lg:w-5 lg:h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-2xl lg:text-3xl font-bold">{{ $statistics['approved'] }}</p>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl p-4 lg:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs lg:text-sm font-medium opacity-90">Terbit</span>
            <svg class="w-4 h-4 lg:w-5 lg:h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </div>
        <p class="text-2xl lg:text-3xl font-bold">{{ $statistics['published'] }}</p>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-rose-500 rounded-2xl p-4 lg:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs lg:text-sm font-medium opacity-90">Ditolak</span>
            <svg class="w-4 h-4 lg:w-5 lg:h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <p class="text-2xl lg:text-3xl font-bold">{{ $statistics['rejected'] }}</p>
    </div>
</div>

<!-- Filters & Search -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('repositories.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penulis..."
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
        </div>
        <select name="document_type" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 bg-white">
            <option value="">Semua Tipe</option>
            <option value="undergraduate_thesis" {{ request('document_type') == 'undergraduate_thesis' ? 'selected' : '' }}>Skripsi</option>
            <option value="masters_thesis" {{ request('document_type') == 'masters_thesis' ? 'selected' : '' }}>Tesis</option>
            <option value="doctoral_dissertation" {{ request('document_type') == 'doctoral_dissertation' ? 'selected' : '' }}>Disertasi</option>
            <option value="research_paper" {{ request('document_type') == 'research_paper' ? 'selected' : '' }}>Research Paper</option>
            <option value="journal_article" {{ request('document_type') == 'journal_article' ? 'selected' : '' }}>Artikel Jurnal</option>
            <option value="other" {{ request('document_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
        </select>
        <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 bg-white">
            <option value="">Semua Status</option>
            <option value="pending_moderation" {{ request('status') == 'pending_moderation' ? 'selected' : '' }}>Menunggu Moderasi</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Terbit</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Arsip</option>
        </select>
        <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-xl transition">
            Cari
        </button>
        @if(request()->anyFilled('search', 'document_type', 'status'))
        <a href="{{ route('repositories.index') }}" class="px-4 py-2.5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 text-sm font-medium rounded-xl transition">
            Reset
        </a>
        @endif
    </form>
</div>

<!-- Repositories Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Penulis</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Submit</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($repositories as $repository)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="max-w-md">
                            <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $repository->title }}</p>
                            @if($repository->doi)
                            <p class="text-xs text-gray-500 mt-0.5">DOI: {{ $repository->doi }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-sm text-gray-700">{{ $repository->author_name }}</p>
                        @if($repository->author_nim)
                        <p class="text-xs text-gray-500">{{ $repository->author_nim }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-purple-50 text-purple-700">
                            {{ $repository->document_type_label }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        @if($repository->status === 'pending_moderation')
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-amber-50 text-amber-700">
                            {{ $repository->status_label }}
                        </span>
                        @elseif($repository->status === 'approved')
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-green-50 text-green-700">
                            {{ $repository->status_label }}
                        </span>
                        @elseif($repository->status === 'published')
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-blue-50 text-blue-700">
                            {{ $repository->status_label }}
                        </span>
                        @elseif($repository->status === 'rejected')
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-red-50 text-red-700">
                            {{ $repository->status_label }}
                        </span>
                        @else
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-gray-50 text-gray-700">
                            {{ $repository->status_label }}
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500">
                        {{ $repository->submitted_at?->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('repositories.show', $repository) }}" class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('repositories.edit', $repository) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @can('repositories.delete')
                            <form action="{{ route('repositories.destroy', $repository) }}" method="POST" onsubmit="return confirm('Hapus repository ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500 font-medium">Belum ada repository</p>
                        <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan repository baru</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($repositories as $repository)
        <div class="p-4 hover:bg-gray-50/50 transition">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1">{{ $repository->title }}</h3>
                    @if($repository->doi)
                    <p class="text-[10px] text-gray-500 font-mono">DOI: {{ $repository->doi }}</p>
                    @endif
                </div>
                @if($repository->status === 'pending_moderation')
                <span class="px-2 py-1 text-[10px] font-medium rounded-full bg-amber-100 text-amber-700 whitespace-nowrap">
                    {{ $repository->status_label }}
                </span>
                @elseif($repository->status === 'approved')
                <span class="px-2 py-1 text-[10px] font-medium rounded-full bg-green-100 text-green-700 whitespace-nowrap">
                    {{ $repository->status_label }}
                </span>
                @elseif($repository->status === 'published')
                <span class="px-2 py-1 text-[10px] font-medium rounded-full bg-blue-100 text-blue-700 whitespace-nowrap">
                    {{ $repository->status_label }}
                </span>
                @elseif($repository->status === 'rejected')
                <span class="px-2 py-1 text-[10px] font-medium rounded-full bg-red-100 text-red-700 whitespace-nowrap">
                    {{ $repository->status_label }}
                </span>
                @else
                <span class="px-2 py-1 text-[10px] font-medium rounded-full bg-gray-100 text-gray-700 whitespace-nowrap">
                    {{ $repository->status_label }}
                </span>
                @endif
            </div>

            <div class="flex items-center gap-2 text-xs text-gray-600 mb-2">
                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="font-medium">{{ $repository->author_name }}</span>
                @if($repository->author_nim)
                <span class="text-gray-400">·</span>
                <span class="text-gray-500 font-mono">{{ $repository->author_nim }}</span>
                @endif
            </div>

            <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-100">
                <div>
                    <div class="text-[10px] text-gray-500 uppercase tracking-wide">Tipe Dokumen</div>
                    <div class="text-xs font-semibold text-purple-700">{{ $repository->document_type_label }}</div>
                </div>
                <div class="text-right">
                    <div class="text-[10px] text-gray-500 uppercase tracking-wide">Tanggal Submit</div>
                    <div class="text-xs font-semibold text-gray-900">{{ $repository->submitted_at?->format('d M Y') ?? '-' }}</div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('repositories.show', $repository) }}" class="inline-flex items-center px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-medium rounded-lg transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Detail
                </a>
                <a href="{{ route('repositories.edit', $repository) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                @can('repositories.delete')
                <form action="{{ route('repositories.destroy', $repository) }}" method="POST" onsubmit="return confirm('Hapus repository ini?')" class="inline">
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
                <p class="text-sm font-medium text-gray-600 mb-1">Belum ada repository</p>
                <p class="text-xs text-gray-400">Mulai dengan menambahkan repository baru</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($repositories->hasPages())
    <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
        {{ $repositories->appends(request()->except('page'))->links() }}
    </div>
    @endif
</div>
@endsection
