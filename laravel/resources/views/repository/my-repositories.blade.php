@extends('layouts.public')

@section('title', 'Repository Saya')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Repository Saya</h1>
                    <p class="text-gray-500 mt-1">Kelola karya ilmiah yang Anda submit</p>
                </div>
            </div>
            <a href="{{ route('repository.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Submit Karya Baru
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <p class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $repositories->count() }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Submit</p>
        </div>
        <div class="bg-amber-50 rounded-2xl border border-amber-100 p-4">
            <p class="text-2xl lg:text-3xl font-bold text-amber-700">{{ $repositories->where('status', 'pending_moderation')->count() }}</p>
            <p class="text-sm text-amber-600 mt-1">Menunggu</p>
        </div>
        <div class="bg-blue-50 rounded-2xl border border-blue-100 p-4">
            <p class="text-2xl lg:text-3xl font-bold text-blue-700">{{ $repositories->where('status', 'approved')->count() }}</p>
            <p class="text-sm text-blue-600 mt-1">Disetujui</p>
        </div>
        <div class="bg-green-50 rounded-2xl border border-green-100 p-4">
            <p class="text-2xl lg:text-3xl font-bold text-green-700">{{ $repositories->where('status', 'published')->count() }}</p>
            <p class="text-sm text-green-600 mt-1">Terbit</p>
        </div>
        <div class="bg-red-50 rounded-2xl border border-red-100 p-4">
            <p class="text-2xl lg:text-3xl font-bold text-red-700">{{ $repositories->where('status', 'rejected')->count() }}</p>
            <p class="text-sm text-red-600 mt-1">Ditolak</p>
        </div>
    </div>

    <!-- Repository List -->
    @forelse($repositories as $repository)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <div class="flex flex-col lg:flex-row lg:items-start gap-4">
            <!-- File Icon -->
            <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <h3 class="text-base font-semibold text-gray-900 line-clamp-2">{{ $repository->title }}</h3>
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg flex-shrink-0 @if($repository->status === 'pending_moderation') bg-amber-50 text-amber-700 @elseif($repository->status === 'approved') bg-blue-50 text-blue-700 @elseif($repository->status === 'published') bg-green-50 text-green-700 @elseif($repository->status === 'rejected') bg-red-50 text-red-700 @else bg-gray-50 text-gray-700 @endif">
                        {{ $repository->status_label }}
                    </span>
                </div>

                <p class="text-sm text-gray-600 mt-1">{{ $repository->author_name }}</p>

                <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-gray-500">
                    <span class="inline-flex px-2 py-1 bg-purple-50 text-purple-700 rounded-lg text-xs font-medium">
                        {{ $repository->document_type_label }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $repository->year }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ number_format($repository->view_count) }} dilihat
                    </span>
                    @if($repository->is_downloadable)
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l4-4m4 4V4"/>
                        </svg>
                        {{ number_format($repository->download_count) }} unduhan
                    </span>
                    @endif
                </div>

                @if($repository->isRejected() && $repository->rejection_reason)
                <div class="mt-3 p-3 bg-red-50 rounded-lg border border-red-100">
                    <p class="text-xs text-red-600">
                        <span class="font-medium">Alasan Penolakan:</span> {{ $repository->rejection_reason }}
                    </p>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex flex-wrap items-center gap-2 mt-4">
                    @if($repository->isPublished())
                    <a href="{{ route('repository.show', $repository->slug) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-purple-700 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Publik
                    </a>
                    @endif
                    <a href="{{ route('repositories.show', $repository) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Detail
                    </a>
                    @if(in_array($repository->status, ['pending_moderation', 'rejected']))
                    <a href="{{ route('repositories.edit', $repository) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada submit</h3>
        <p class="text-gray-500 mb-6">Anda belum submit karya ilmiah apapun.</p>
        <a href="{{ route('repository.create') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Submit Karya Pertama
        </a>
    </div>
    @endforelse
</div>
@endsection
