@extends('layouts.admin')

@section('title', $repository->title)

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
                    <h1 class="text-xl lg:text-2xl font-bold text-gray-900 line-clamp-2">{{ $repository->title }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $repository->document_type_label }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('repositories.edit', $repository) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <a href="{{ route('repositories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Status Badge -->
<div class="mb-6">
    @if($repository->status === 'pending_moderation')
    <div class="inline-flex items-center px-4 py-2 bg-amber-50 text-amber-800 rounded-xl">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-medium">{{ $repository->status_label }}</span>
    </div>
    @elseif($repository->status === 'approved')
    <div class="inline-flex items-center px-4 py-2 bg-green-50 text-green-800 rounded-xl">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="font-medium">{{ $repository->status_label }}</span>
    </div>
    @elseif($repository->status === 'published')
    <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-800 rounded-xl">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        <span class="font-medium">{{ $repository->status_label }}</span>
    </div>
    @elseif($repository->status === 'rejected')
    <div class="inline-flex items-center px-4 py-2 bg-red-50 text-red-800 rounded-xl">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span class="font-medium">{{ $repository->status_label }}</span>
    </div>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Author Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Penulis
            </h3>
            <div class="space-y-2">
                <p class="text-base font-medium text-gray-900">{{ $repository->author_name }}</p>
                @if($repository->author_nim)
                <p class="text-sm text-gray-500">NIM: {{ $repository->author_nim }}</p>
                @endif
                @if($repository->author_email)
                <p class="text-sm text-gray-500">{{ $repository->author_email }}</p>
                @endif
            </div>
            @if($repository->advisor_name || $repository->co_advisor_name)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Pembimbing:</span>
                    {{ $repository->advisor_name ?? '-' }}
                    @if($repository->co_advisor_name)
                    , <span class="font-medium">Co-Pembimbing:</span> {{ $repository->co_advisor_name }}
                    @endif
                </p>
            </div>
            @endif
        </div>

        <!-- Abstract -->
        @if($repository->abstract)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Abstrak</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $repository->abstract }}</p>
        </div>
        @endif

        <!-- DOI -->
        @if($repository->doi)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
                DOI
            </h3>
            <p class="text-sm text-purple-600 font-medium">{{ $repository->doi }}</p>
        </div>
        @endif

        <!-- Citation -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Sitasi
            </h3>
            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $repository->getCitation() }}</p>
        </div>

        <!-- Rejection Reason -->
        @if($repository->isRejected() && $repository->rejection_reason)
        <div class="bg-red-50 rounded-2xl border border-red-100 p-6">
            <h3 class="text-sm font-semibold text-red-900 mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Alasan Penolakan
            </h3>
            <p class="text-sm text-red-700">{{ $repository->rejection_reason }}</p>
            @if($repository->rejectedBy)
            <p class="text-xs text-red-600 mt-2">Ditolak oleh: {{ $repository->rejectedBy->name }} pada {{ $repository->rejected_at?->format('d M Y H:i') }}</p>
            @endif
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Statistik</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Dilihat</span>
                    <span class="text-sm font-medium text-gray-900">{{ number_format($repository->view_count) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Diunduh</span>
                    <span class="text-sm font-medium text-gray-900">{{ number_format($repository->download_count) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Ukuran File</span>
                    <span class="text-sm font-medium text-gray-900">{{ $repository->file_size_human }}</span>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Detail</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tipe Dokumen</p>
                    <p class="text-sm text-gray-900">{{ $repository->document_type_label }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tahun</p>
                    <p class="text-sm text-gray-900">{{ $repository->year }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Bahasa</p>
                    <p class="text-sm text-gray-900">{{ strtoupper($repository->language) }}</p>
                </div>
                @if($repository->faculty)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Fakultas</p>
                    <p class="text-sm text-gray-900">{{ $repository->faculty }}</p>
                </div>
                @endif
                @if($repository->department)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Jurusan</p>
                    <p class="text-sm text-gray-900">{{ $repository->department }}</p>
                </div>
                @endif
                @if($repository->program_study)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Program Studi</p>
                    <p class="text-sm text-gray-900">{{ $repository->program_study }}</p>
                </div>
                @endif
                <div>
                    <p class="text-xs text-gray-500 mb-1">Akses</p>
                    <p class="text-sm text-gray-900">{{ $repository->access_level_label }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Status</p>
                    <p class="text-sm text-gray-900">{{ $repository->status_label }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tanggal Submit</p>
                    <p class="text-sm text-gray-900">{{ $repository->submitted_at?->format('d M Y H:i') }}</p>
                </div>
                @if($repository->approved_at)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Disetujui Pada</p>
                    <p class="text-sm text-gray-900">{{ $repository->approved_at->format('d M Y H:i') }}</p>
                    @if($repository->approvedBy)
                    <p class="text-xs text-gray-500 mt-0.5">oleh {{ $repository->approvedBy->name }}</p>
                    @endif
                </div>
                @endif
                @if($repository->published_at)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Diterbitkan Pada</p>
                    <p class="text-sm text-gray-900">{{ $repository->published_at->format('d M Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Moderation Actions -->
        @can('repositories.moderate')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Aksi Moderasi</h3>
            <div class="space-y-2">
                @if($repository->isPendingModeration() || $repository->isRejected())
                <form action="{{ route('repositories.approve', $repository) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Setujui
                    </button>
                </form>
                @endif

                @if($repository->isPendingModeration() || $repository->isApproved())
                <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')" class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak
                </button>
                <form id="reject-form" action="{{ route('repositories.reject', $repository) }}" method="POST" class="hidden mt-2">
                    @csrf
                    <textarea name="reason" placeholder="Alasan penolakan..." required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm mb-2" rows="2"></textarea>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition">
                            Kirim
                        </button>
                        <button type="button" onclick="document.getElementById('reject-form').classList.add('hidden')" class="px-3 py-2 text-gray-600 hover:text-gray-800 text-xs font-medium rounded-lg transition">
                            Batal
                        </button>
                    </div>
                </form>
                @endif

                @if($repository->isApproved())
                <form action="{{ route('repositories.publish', $repository) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Terbitkan
                    </button>
                </form>
                @endif

                @if($repository->isPublished())
                <form action="{{ route('repositories.archive', $repository) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-xl transition">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        Arsipkan
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endcan
    </div>
</div>
@endsection
