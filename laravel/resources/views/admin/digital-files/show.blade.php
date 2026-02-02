@extends('layouts.admin')

@section('title', $digitalFile->title)

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('digital-files.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $digitalFile->title }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Detail file digital</p>
        </div>
    </div>
</div>

<!-- File Info Card -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                    @if($digitalFile->isPdf())
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    @elseif($digitalFile->isImage())
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
                    <h2 class="text-xl font-bold text-gray-900">{{ $digitalFile->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $digitalFile->file_name }}</p>
                    @if($digitalFile->version)
                    <span class="inline-block mt-2 px-2.5 py-0.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                        Versi {{ $digitalFile->version }}
                    </span>
                    @endif
                </div>
                <div class="flex gap-2">
                    @if($digitalFile->isPdf() || $digitalFile->isImage())
                    <a href="{{ route('digital-files.preview', $digitalFile) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium rounded-xl transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Preview
                    </a>
                    @endif
                    <a href="{{ route('digital-files.download', $digitalFile) }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-medium rounded-xl transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </a>
                </div>
            </div>

            @if($digitalFile->description)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Deskripsi</h3>
                <p class="text-sm text-gray-600">{{ $digitalFile->description }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Statistik</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Download</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($digitalFile->download_count) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Dilihat</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($digitalFile->view_count) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Ukuran</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $digitalFile->file_size_human }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Aktif</span>
                    @if($digitalFile->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Ya
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Tidak
                    </span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Akses</span>
                    @if($digitalFile->access_level === 'public')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Publik
                    </span>
                    @elseif($digitalFile->access_level === 'registered')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Terdaftar
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Kampus
                    </span>
                    @endif
                </div>
                @if($digitalFile->published_at)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Terbit</span>
                    <span class="text-sm text-gray-900">{{ $digitalFile->published_at->format('d M Y') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Details Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- File Details -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Detail File</h3>
        <dl class="space-y-3">
            <div class="flex justify-between">
                <dt class="text-sm text-gray-500">Nama File</dt>
                <dd class="text-sm text-gray-900 font-medium">{{ $digitalFile->file_name }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm text-gray-500">Tipe File</dt>
                <dd class="text-sm text-gray-900 font-medium uppercase">{{ $digitalFile->file_type }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm text-gray-500">MIME Type</dt>
                <dd class="text-sm text-gray-900 font-medium">{{ $digitalFile->mime_type }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm text-gray-500">Ukuran</dt>
                <dd class="text-sm text-gray-900 font-medium">{{ $digitalFile->file_size_human }}</dd>
            </div>
        </dl>
    </div>

    <!-- Collection & Uploader -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Koleksi & Uploader</h3>
        <dl class="space-y-3">
            <div>
                <dt class="text-sm text-gray-500 mb-1">Koleksi</dt>
                <dd class="text-sm">
                    @if($digitalFile->collection)
                    <a href="{{ route('collections.show', $digitalFile->collection) }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                        {{ $digitalFile->collection->title }}
                    </a>
                    @else
                    <span class="text-gray-400">-</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500 mb-1">Diupload oleh</dt>
                <dd class="text-sm text-gray-900 font-medium">{{ $digitalFile->uploader?->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500 mb-1">Tanggal Upload</dt>
                <dd class="text-sm text-gray-900">{{ $digitalFile->created_at->format('d M Y, H:i') }}</dd>
            </div>
            @if($digitalFile->updated_at != $digitalFile->created_at)
            <div>
                <dt class="text-sm text-gray-500 mb-1">Terakhir Diupdate</dt>
                <dd class="text-sm text-gray-900">{{ $digitalFile->updated_at->format('d M Y, H:i') }}</dd>
            </div>
            @endif
        </dl>
    </div>
</div>

<!-- Actions -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-semibold text-gray-700">Aksi</h3>
            <p class="text-xs text-gray-500 mt-0.5">Kelola file digital ini</p>
        </div>
        <div class="flex items-center gap-3">
            @can('digital_files.edit')
            <a href="{{ route('digital-files.edit', $digitalFile) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            @endcan
            @can('digital_files.delete')
            <form method="POST" action="{{ route('digital-files.destroy', $digitalFile) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus file ini? File tidak dapat dikembalikan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-medium rounded-xl transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </form>
            @endcan
        </div>
    </div>
</div>
@endsection
