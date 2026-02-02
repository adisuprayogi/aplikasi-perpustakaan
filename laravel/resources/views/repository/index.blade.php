@extends('layouts.public')

@section('title', 'Institutional Repository')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-purple-600 via-violet-600 to-indigo-700 text-white py-16 lg:py-24 rounded-3xl mb-8 lg:mb-12 relative overflow-hidden">
    <!-- Decorative circles -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
        <div class="inline-flex items-center justify-center p-3 bg-white/10 backdrop-blur-sm rounded-2xl mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h1 class="text-3xl lg:text-5xl font-bold mb-4">Institutional Repository</h1>
        <p class="text-lg lg:text-xl text-purple-100 max-w-2xl mx-auto">
            Kumpulan karya ilmiah skripsi, tesis, disertasi, dan publikasi dari civitas akademika
        </p>

        <!-- Search Box -->
        <form action="{{ route('repository.search') }}" method="GET" class="mt-8 max-w-2xl mx-auto">
            <div class="flex">
                <input type="text" name="q" placeholder="Cari judul, penulis, atau kata kunci..."
                    class="flex-1 px-6 py-4 rounded-l-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-white/30">
                <button type="submit" class="px-8 py-4 bg-white text-purple-700 font-semibold rounded-r-xl hover:bg-purple-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $repositories->total() }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Karya</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $repositories->where('document_type', 'undergraduate_thesis')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Skripsi</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $repositories->where('document_type', 'masters_thesis')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Tesis</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $repositories->where('document_type', 'doctoral_dissertation')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Disertasi</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414-1.414l-5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Content -->
    <div class="flex-1">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="flex flex-wrap items-center gap-3">
                <select class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 bg-white">
                    <option value="">Semua Tipe</option>
                    <option value="undergraduate_thesis">Skripsi</option>
                    <option value="masters_thesis">Tesis</option>
                    <option value="doctoral_dissertation">Disertasi</option>
                    <option value="research_paper">Research Paper</option>
                    <option value="journal_article">Artikel Jurnal</option>
                </select>
                <select class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 bg-white">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year >= date('Y') - 10; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
                @auth
                <a href="{{ route('repository.create') }}" class="ml-auto px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
                    + Submit Karya
                </a>
                @endauth
            </div>
        </div>

        <!-- Repository List -->
        <div class="space-y-4">
            @forelse($repositories as $repository)
            <a href="{{ route('repository.show', $repository->slug) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <!-- File Icon -->
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-base font-semibold text-gray-900 hover:text-purple-600 transition line-clamp-2">{{ $repository->title }}</h3>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-purple-50 text-purple-700 flex-shrink-0">
                                {{ $repository->document_type_label }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mt-1">{{ $repository->author_name }}</p>

                        @if($repository->abstract)
                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ Str::limit(strip_tags($repository->abstract), 200) }}</p>
                        @endif

                        <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $repository->year }}
                            </span>
                            @if($repository->faculty)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ $repository->faculty }}
                            </span>
                            @endif
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ number_format($repository->view_count) }} dilihat
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 font-medium">Belum ada karya ilmiah</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($repositories->hasPages())
        <div class="mt-6">
            {{ $repositories->appends(request()->except('page'))->links() }}
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="w-full lg:w-80">
        <!-- Popular -->
        @if($popularRepositories->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Paling Populer
            </h3>
            <div class="space-y-3">
                @foreach($popularRepositories as $popular)
                <a href="{{ route('repository.show', $popular->slug) }}" class="block group">
                    <p class="text-sm font-medium text-gray-900 group-hover:text-purple-600 transition line-clamp-2">{{ $popular->title }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($popular->download_count) }} unduhan</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent -->
        @if($recentRepositories->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Terbaru
            </h3>
            <div class="space-y-3">
                @foreach($recentRepositories as $recent)
                <a href="{{ route('repository.show', $recent->slug) }}" class="block group">
                    <p class="text-sm font-medium text-gray-900 group-hover:text-purple-600 transition line-clamp-2">{{ $recent->title }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $recent->published_at?->diffForHumans() ?? 'Baru saja' }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
