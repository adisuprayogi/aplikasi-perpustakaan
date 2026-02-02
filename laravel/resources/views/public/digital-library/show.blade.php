@extends('layouts.public')

@section('title', $file->title)

@section('content')
<div class="mb-6">
    <a href="{{ route('digital-library.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Perpustakaan Digital
    </a>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                    @if($file->isPdf())
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    @elseif($file->isImage())
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    @else
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    @endif
                </div>
                <div class="flex-1">
                    <h1 class="text-xl font-bold text-gray-900">{{ $file->title }}</h1>
                    @if($file->collection)
                    <p class="text-sm text-gray-500 mt-1">{{ $file->collection->title }}</p>
                    @endif
                    <div class="flex items-center gap-3 mt-3">
                        @if($file->version)
                        <span class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                            Versi {{ $file->version }}
                        </span>
                        @endif
                        <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-medium rounded-full">
                            {{ $file->file_size_human }}
                        </span>
                        @if($file->access_level === 'public')
                        <span class="inline-flex items-center px-2.5 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            Publik
                        </span>
                        @elseif($file->access_level === 'registered')
                        <span class="inline-flex items-center px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            Login Required
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                            Campus Only
                        </span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    @if($file->isPdf() || $file->isImage())
                    <a href="{{ route('digital-library.preview', $file) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Preview
                    </a>
                    @endif
                    <a href="{{ route('digital-library.download', $file) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </a>
                </div>
            </div>
        </div>

        @if($file->description)
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-sm font-semibold text-gray-700 mb-2">Deskripsi</h2>
            <p class="text-sm text-gray-600">{{ $file->description }}</p>
        </div>
        @endif

        <!-- File Details -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Informasi File</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Nama File</dt>
                        <dd class="text-gray-900 font-medium text-right">{{ $file->file_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Tipe File</dt>
                        <dd class="text-gray-900 font-medium text-right uppercase">{{ $file->file_type }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Ukuran</dt>
                        <dd class="text-gray-900 font-medium text-right">{{ $file->file_size_human }}</dd>
                    </div>
                    @if($file->published_at)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Tanggal Terbit</dt>
                        <dd class="text-gray-900 font-medium text-right">{{ $file->published_at->format('d M Y') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Statistik</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Diunduh</dt>
                        <dd class="text-gray-900 font-medium text-right">{{ number_format($file->download_count) }} kali</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Dilihat</dt>
                        <dd class="text-gray-900 font-medium text-right">{{ number_format($file->view_count) }} kali</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Diupload</dt>
                        <dd class="text-gray-900 font-medium text-right">{{ $file->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
