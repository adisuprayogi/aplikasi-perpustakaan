@extends('layouts.public')

@section('title', $repository->title)

@section('content')
<!-- Breadcrumb -->
<div class="mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('repository.index') }}" class="text-gray-500 hover:text-gray-700">Repository</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </li>
            <li class="text-gray-700 font-medium line-clamp-1">{{ $repository->title }}</li>
        </ol>
    </nav>
</div>

<!-- Document Header -->
<div class="bg-gradient-to-br from-purple-600 via-violet-600 to-indigo-700 text-white rounded-2xl p-6 lg:p-8 mb-8 relative overflow-hidden">
    <!-- Decorative circles -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>

    <div class="relative">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="inline-flex px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                {{ $repository->document_type_label }}
            </span>
            @if($repository->doi)
            <span class="inline-flex px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                DOI: {{ $repository->doi }}
            </span>
            @endif
        </div>

        <h1 class="text-2xl lg:text-3xl font-bold mb-4">{{ $repository->title }}</h1>

        <div class="flex flex-wrap items-center gap-4 text-sm text-purple-100">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ $repository->author_name }}
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
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Author Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Penulis
            </h3>
            <div class="space-y-2">
                <p class="text-base font-medium text-gray-900">{{ $repository->author_name }}</p>
                @if($repository->author_nim)
                <p class="text-sm text-gray-500">NIM: {{ $repository->author_nim }}</p>
                @endif
                @if($repository->advisor_name)
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Pembimbing:</span> {{ $repository->advisor_name }}
                    @if($repository->co_advisor_name)
                    , <span class="font-medium">Co-Pembimbing:</span> {{ $repository->co_advisor_name }}
                    @endif
                </p>
                @endif
            </div>
        </div>

        <!-- Abstract -->
        @if($repository->abstract)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Abstrak</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $repository->abstract }}</p>
        </div>
        @endif

        <!-- Keywords -->
        @if($repository->keywords)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Kata Kunci</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $repository->keywords) as $keyword)
                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">{{ trim($keyword) }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Citation -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Sitasi
            </h3>
            <div class="bg-gray-50 p-4 rounded-xl">
                <p class="text-sm text-gray-700">{{ $repository->getCitation() }}</p>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Download Button -->
        @if($repository->is_downloadable)
        <a href="{{ route('repository.download', $repository->slug) }}" class="block w-full bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white text-center p-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
            <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l4-4m4 4V4"/>
            </svg>
            Unduh Dokumen
        </a>
        @else
        <div class="w-full bg-gray-100 text-gray-500 text-center p-4 rounded-2xl font-semibold">
            <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Unduhan Tidak Tersedia
        </div>
        @endif

        <!-- Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Informasi</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Tipe Dokumen</span>
                    <span class="text-sm font-medium text-gray-900">{{ $repository->document_type_label }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Tahun</span>
                    <span class="text-sm font-medium text-gray-900">{{ $repository->year }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Bahasa</span>
                    <span class="text-sm font-medium text-gray-900">{{ strtoupper($repository->language) }}</span>
                </div>
                @if($repository->faculty)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Fakultas</span>
                    <span class="text-sm font-medium text-gray-900">{{ $repository->faculty }}</span>
                </div>
                @endif
                @if($repository->department)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Jurusan</span>
                    <span class="text-sm font-medium text-gray-900">{{ $repository->department }}</span>
                </div>
                @endif
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Ukuran File</span>
                    <span class="text-sm font-medium text-gray-900">{{ $repository->file_size_human }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Tanggal Terbit</span>
                    <span class="text-sm font-medium text-gray-900">{{ $repository->published_at?->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Access Info -->
        <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-900">Akses: {{ $repository->access_level_label }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($repository->access_level === 'public')
                        Dokumen ini dapat diakses oleh publik
                        @elseif($repository->access_level === 'registered')
                        Dokumen ini hanya dapat diakses oleh pengguna terdaftar
                        @elseif($repository->access_level === 'campus_only')
                        Dokumen ini hanya dapat diakses oleh civitas kampus
                        @else
                        Dokumen ini memiliki akses terbatas
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
