@extends('layouts.admin')

@section('title', 'Preview: ' . $digitalFile->title)

@push('styles')
<style>
    .preview-container {
        height: calc(100vh - 200px);
        min-height: 500px;
    }
    .preview-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    .preview-image {
        max-width: 100%;
        max-height: calc(100vh - 250px);
        object-fit: contain;
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('digital-files.show', $digitalFile) }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $digitalFile->title }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">Preview file digital</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('digital-files.download', $digitalFile) }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-medium rounded-xl transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download
            </a>
            <a href="{{ route('digital-files.show', $digitalFile) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                Tutup
            </a>
        </div>
    </div>
</div>

<!-- Preview Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if($digitalFile->isPdf())
    <!-- PDF Preview -->
    <div class="preview-container bg-gray-100">
        <iframe src="{{ asset('storage/' . $digitalFile->file_path) }}" type="application/pdf"></iframe>
    </div>
    @elseif($digitalFile->isImage())
    <!-- Image Preview -->
    <div class="p-6 bg-gray-100 flex items-center justify-center min-h-[500px]">
        <img src="{{ asset('storage/' . $digitalFile->file_path) }}" alt="{{ $digitalFile->title }}" class="preview-image rounded-lg shadow-lg">
    </div>
    @else
    <!-- Unsupported Preview -->
    <div class="p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Preview Tidak Tersedia</h3>
        <p class="text-sm text-gray-500 mb-6">Tipe file ini tidak dapat dipreview. Silakan download untuk melihat isi file.</p>
        <a href="{{ route('digital-files.download', $digitalFile) }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download File
        </a>
    </div>
    @endif
</div>

<!-- File Info -->
<div class="mt-4 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-md">
                @if($digitalFile->isPdf())
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                @elseif($digitalFile->isImage())
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                @else
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ $digitalFile->title }}</p>
                <p class="text-xs text-gray-500">{{ $digitalFile->file_name }} • {{ $digitalFile->file_size_human }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                {{ number_format($digitalFile->view_count) }} dilihat
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                {{ number_format($digitalFile->download_count) }} diunduh
            </span>
        </div>
    </div>
</div>
@endsection
