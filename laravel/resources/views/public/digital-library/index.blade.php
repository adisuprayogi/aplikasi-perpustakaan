@extends('layouts.public')

@section('title', 'Perpustakaan Digital')

@section('content')
<div class="mb-8">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 rounded-3xl overflow-hidden mb-8 shadow-2xl">
        <div class="relative px-8 py-10 sm:px-12 lg:px-16">
            <h1 class="text-3xl font-bold text-white mb-2">Perpustakaan Digital</h1>
            <p class="text-blue-200">Akses koleksi digital perpustakaan kapan saja dan di mana saja</p>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <form method="GET" action="{{ route('digital-library.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[250px]">
                <div class="relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul file..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                </div>
            </div>
            <div>
                <select name="file_type" class="px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="pdf" {{ request('file_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                    <option value="doc" {{ request('file_type') == 'doc' ? 'selected' : '' }}>DOC</option>
                    <option value="docx" {{ request('file_type') == 'docx' ? 'selected' : '' }}>DOCX</option>
                    <option value="ppt" {{ request('file_type') == 'ppt' ? 'selected' : '' }}>PPT</option>
                    <option value="jpg" {{ request('file_type') == 'jpg' ? 'selected' : '' }}>JPG</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-lg">
                Cari
            </button>
            @if(request()->hasAny('search', 'file_type'))
            <a href="{{ route('digital-library.index') }}" class="px-5 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition text-sm">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Results -->
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-600">
            @if(request('search'))
            Menampilkan hasil untuk "<strong>{{ request('search') }}</strong>"
            @else
            Semua File Digital
            @endif
            <span class="ml-1">({{ $digitalFiles->total() }} file)</span>
        </p>
    </div>

    <!-- Files Grid -->
    @forelse($digitalFiles as $file)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($digitalFiles as $file)
        <div class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
            <!-- Decorative Circle -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>

            <div class="relative">
                <!-- File Icon -->
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                    @if($file->isPdf())
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    @elseif($file->isImage())
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    @else
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    @endif
                </div>

                <!-- Content -->
                <h3 class="text-lg font-bold text-white mb-2 line-clamp-2">{{ $file->title }}</h3>
                @if($file->collection)
                <p class="text-sm text-blue-100 mb-3 truncate">{{ $file->collection->title }}</p>
                @endif

                <!-- File Info -->
                <div class="flex items-center gap-3 text-sm text-blue-100 mb-4">
                    <span class="uppercase">{{ $file->file_type }}</span>
                    <span>•</span>
                    <span>{{ $file->file_size_human }}</span>
                </div>

                <!-- Access Badge -->
                @if($file->access_level !== 'public')
                <div class="inline-flex items-center px-2 py-1 bg-white/20 backdrop-blur-sm rounded-full mb-4">
                    @if($file->access_level === 'registered')
                    <span class="text-xs text-white">Login Required</span>
                    @else
                    <span class="text-xs text-white">Kampus Only</span>
                    @endif
                </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    @if($file->isPdf() || $file->isImage())
                    <a href="{{ route('digital-library.preview', $file) }}" target="_blank" class="flex-1 text-center px-3 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-lg transition backdrop-blur-sm">
                        Preview
                    </a>
                    @endif
                    <a href="{{ route('digital-library.download', $file) }}" class="flex-1 text-center px-3 py-2 bg-white hover:bg-gray-100 text-blue-600 text-sm font-medium rounded-lg transition">
                        Download
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">Tidak Ada File Ditemukan</h3>
        <p class="text-sm text-gray-500">Coba kata kunci atau filter lain</p>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($digitalFiles->hasPages())
    <div class="mt-8">
        {{ $digitalFiles->appends(request()->query())->onEachSide(2)->links() }}
    </div>
    @endif
</div>
@endsection
