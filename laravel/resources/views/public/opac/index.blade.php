@extends('layouts.public')

@section('title', 'OPAC - Online Public Access Catalog')

@section('content')
<!-- Hero Section dengan Background Image Overlay -->
<div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 rounded-3xl overflow-hidden mb-8 shadow-2xl">
    <!-- Decorative Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#grid)"/>
        </svg>
    </div>

    <div class="relative px-8 py-16 sm:px-12 lg:px-16 text-center">
        <!-- Icon/Header -->
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl mb-6">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>

        <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4 tracking-tight">
            Perpustakaan Digital
        </h1>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto leading-relaxed">
            Temukan koleksi buku, jurnal, skripsi, dan referensi ilmiah untuk mendukung pembelajaran dan penelitian Anda.
        </p>

        <!-- Search Bar -->
        <form action="{{ route('opac.search') }}" method="GET" class="max-w-3xl mx-auto">
            <div class="relative flex items-center bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex-1 relative">
                    <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" placeholder="Cari judul buku, penulis, atau subjek..."
                        class="w-full pl-14 pr-6 py-5 text-gray-700 bg-transparent focus:outline-none text-lg">
                </div>
                <button type="submit" class="px-8 py-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-all duration-200 flex items-center gap-2">
                    <span>Cari</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Quick Search Tags -->
        <div class="mt-6 flex flex-wrap justify-center gap-2">
            <span class="text-blue-200 text-sm">Populer:</span>
            <a href="{{ route('opac.search') }}?q=javascript" class="px-3 py-1 bg-white/10 hover:bg-white/20 text-white text-sm rounded-full transition">JavaScript</a>
            <a href="{{ route('opac.search') }}?q=python" class="px-3 py-1 bg-white/10 hover:bg-white/20 text-white text-sm rounded-full transition">Python</a>
            <a href="{{ route('opac.search') }}?q=machine+learning" class="px-3 py-1 bg-white/10 hover:bg-white/20 text-white text-sm rounded-full transition">Machine Learning</a>
            <a href="{{ route('opac.search') }}?q=desain" class="px-3 py-1 bg-white/10 hover:bg-white/20 text-white text-sm rounded-full transition">Desain</a>
        </div>
    </div>
</div>

<!-- Statistics Cards with Gradient Backgrounds -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
    <div class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['total_collections']) }}</p>
            <p class="text-blue-100 text-sm mt-1">Koleksi</p>
        </div>
    </div>

    <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['available_items']) }}</p>
            <p class="text-emerald-100 text-sm mt-1">Tersedia</p>
        </div>
    </div>

    <div class="group relative overflow-hidden bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['total_items']) }}</p>
            <p class="text-violet-100 text-sm mt-1">Total Item</p>
        </div>
    </div>

    <div class="group relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['total_authors']) }}</p>
            <p class="text-amber-100 text-sm mt-1">Penulis</p>
        </div>
    </div>
</div>

<!-- Section: Koleksi Terbaru -->
<div class="mb-10">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Koleksi Terbaru</h2>
                <p class="text-sm text-gray-500">Baru saja ditambahkan ke perpustakaan</p>
            </div>
        </div>
        <a href="{{ route('opac.search') }}?sort=latest" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium text-sm">
            Lihat Semua
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($recentCollections as $collection)
        <a href="{{ route('opac.show', $collection->id) }}" class="group">
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                @if($collection->cover_image)
                <div class="aspect-[3/4] overflow-hidden">
                    <img src="{{ asset('storage/' . $collection->cover_image) }}" alt="{{ $collection->title }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                @else
                <div class="aspect-[3/4] bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                    <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                @endif
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $collection->title }}</h3>
                    @if(is_array($collection->authors) && count($collection->authors) > 0)
                    <p class="text-xs text-gray-500 mt-2">{{ $collection->authors[0]['name'] ?? '' }}</p>
                    @endif
                    @if($collection->collectionType)
                    <span class="inline-block mt-2 px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg">{{ $collection->collectionType->name }}</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<!-- Section: Koleksi Populer -->
<div class="mb-10">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Paling Populer</h2>
                <p class="text-sm text-gray-500">Sering dipinjam oleh anggota</p>
            </div>
        </div>
        <a href="{{ route('opac.search') }}?sort=popular" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium text-sm">
            Lihat Semua
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($popularCollections as $collection)
        <a href="{{ route('opac.show', $collection->id) }}" class="group">
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                @if($collection->cover_image)
                <div class="aspect-[3/4] overflow-hidden relative">
                    <img src="{{ asset('storage/' . $collection->cover_image) }}" alt="{{ $collection->title }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute top-2 right-2 px-2 py-1 bg-amber-500 text-white text-xs font-semibold rounded-full">
                        {{ $collection->borrowed_items }}
                    </div>
                </div>
                @else
                <div class="aspect-[3/4] bg-gradient-to-br from-amber-50 to-orange-100 flex items-center justify-center relative">
                    <svg class="w-16 h-16 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <div class="absolute top-2 right-2 px-2 py-1 bg-amber-500 text-white text-xs font-semibold rounded-full">
                        {{ $collection->borrowed_items }}
                    </div>
                </div>
                @endif
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $collection->title }}</h3>
                    @if(is_array($collection->authors) && count($collection->authors) > 0)
                    <p class="text-xs text-gray-500 mt-2">{{ $collection->authors[0]['name'] ?? '' }}</p>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<!-- Category Quick Links -->
<div>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">Jelajah Berdasarkan Kategori</h2>
            <p class="text-sm text-gray-500">Temukan koleksi berdasarkan tipe</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('opac.search') }}?collection_type=1" class="group flex items-center p-5 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-5 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Buku</h3>
                <p class="text-sm text-gray-500">Buku teks dan referensi</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('opac.search') }}?collection_type=2" class="group flex items-center p-5 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-emerald-200">
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mr-5 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">Jurnal</h3>
                <p class="text-sm text-gray-500">Jurnal ilmiah dan majalah</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('opac.search') }}?collection_type=3" class="group flex items-center p-5 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-violet-200">
            <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl flex items-center justify-center mr-5 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition-colors">Skripsi/Tesis</h3>
                <p class="text-sm text-gray-500">Karya ilmiah mahasiswa</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-violet-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>
@endsection
