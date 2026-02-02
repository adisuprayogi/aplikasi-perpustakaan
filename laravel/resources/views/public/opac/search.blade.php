@extends('layouts.public')

@section('title', 'Hasil Pencarian - ' . $query)

@section('content')
<div class="mb-6 lg:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Hasil Pencarian</h1>
            <p class="mt-1 text-sm text-gray-500">
                @if($query)
                Menampilkan hasil untuk "<strong>{{ $query }}</strong>"
                @else
                Semua Koleksi
                @endif
                <span class="ml-2">({{ $collections->total() }} hasil)</span>
            </p>
        </div>
        <a href="{{ route('opac.advanced') }}" class="inline-flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition min-h-[48px]">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
            <span class="hidden sm:inline">Filter Lanjutan</span>
            <span class="sm:hidden">Filter</span>
        </a>
    </div>

    <!-- Active Filters -->
    @if(request()->except(['q', 'page']))
    <div class="flex flex-wrap gap-2 mb-4">
        @if(request()->filled('collection_type'))
        <span class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 text-sm rounded-full">
            Tipe: {{ $filterOptions['collection_types']->firstWhere('id', request('collection_type'))->name ?? '' }}
        </span>
        @endif
        @if(request()->filled('gmd'))
        <span class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 text-sm rounded-full">
            GMD: {{ $filterOptions['gmds']->firstWhere('id', request('gmd'))->name ?? '' }}
        </span>
        @endif
        @if(request()->filled('author'))
        <span class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 text-sm rounded-full">
            Penulis: {{ $filterOptions['authors']->firstWhere('id', request('author'))->name ?? '' }}
        </span>
        @endif
        @if(request()->filled('available_only'))
        <span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 text-sm rounded-full">
            Hanya yang Tersedia
        </span>
        @endif
        <a href="{{ route('opac.search', ['q' => $query]) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-gray-700 text-sm rounded-full hover:bg-gray-300">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Hapus Filter
        </a>
    </div>
    @endif
</div>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Mobile Filter Toggle -->
    <div class="lg:hidden" x-data="{ filterOpen: false }">
        <button @click="filterOpen = !filterOpen" class="w-full flex items-center justify-between px-4 py-3 bg-white rounded-xl shadow-sm border border-gray-100 mb-4">
            <span class="text-sm font-medium text-gray-700">Filter Pencarian</span>
            <svg class="w-5 h-5 text-gray-400" :class="{ 'rotate-180': filterOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="filterOpen" x-transition class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4">
            <form action="{{ route('opac.search') }}" method="GET" class="space-y-4">
                <input type="hidden" name="q" value="{{ $query }}">

                <!-- Collection Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Koleksi</label>
                    <select name="collection_type" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-blue-600 min-h-[48px]">
                        <option value="">Semua</option>
                        @foreach($filterOptions['collection_types'] as $type)
                        <option value="{{ $type->id }}" {{ request('collection_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- GMD -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">GMD</label>
                    <select name="gmd" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-blue-600 min-h-[48px]">
                        <option value="">Semua</option>
                        @foreach($filterOptions['gmds'] as $gmd)
                        <option value="{{ $gmd->id }}" {{ request('gmd') == $gmd->id ? 'selected' : '' }}>{{ $gmd->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Available Only -->
                <div class="flex items-center">
                    <input type="checkbox" name="available_only" id="available_only_mobile" {{ request('available_only') ? 'checked' : '' }} class="w-5 h-5 text-blue-700 border-gray-300 rounded focus:ring-blue-700">
                    <label for="available_only_mobile" class="ml-3 text-sm text-gray-700">Hanya yang tersedia</label>
                </div>

                <button type="submit" class="w-full px-4 py-3 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-xl transition min-h-[48px]">
                    Terapkan Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar Filters (Desktop) -->
    <aside class="w-64 flex-shrink-0 hidden lg:block">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sticky top-20">
            <h3 class="font-medium text-gray-900 mb-4">Filter</h3>

            <form action="{{ route('opac.search') }}" method="GET" class="space-y-4">
                <input type="hidden" name="q" value="{{ $query }}">

                <!-- Collection Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Koleksi</label>
                    <select name="collection_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-700">
                        <option value="">Semua</option>
                        @foreach($filterOptions['collection_types'] as $type)
                        <option value="{{ $type->id }}" {{ request('collection_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- GMD -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">GMD</label>
                    <select name="gmd" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-700">
                        <option value="">Semua</option>
                        @foreach($filterOptions['gmds'] as $gmd)
                        <option value="{{ $gmd->id }}" {{ request('gmd') == $gmd->id ? 'selected' : '' }}>{{ $gmd->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Author -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                    <select name="author" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-700">
                        <option value="">Semua</option>
                        @foreach($filterOptions['authors']->take(20) as $author)
                        <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subjek</label>
                    <select name="subject" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-700">
                        <option value="">Semua</option>
                        @foreach($filterOptions['subjects']->take(20) as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Available Only -->
                <div class="flex items-center">
                    <input type="checkbox" name="available_only" id="available_only" {{ request('available_only') ? 'checked' : '' }} class="w-4 h-4 text-blue-700 border-gray-300 rounded focus:ring-blue-700">
                    <label for="available_only" class="ml-2 text-sm text-gray-700">Hanya yang tersedia</label>
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-lg transition">
                    Terapkan Filter
                </button>
            </form>
        </div>
    </aside>

    <!-- Results -->
    <div class="flex-1">
        @if($collections->count() > 0)
        <div class="grid grid-cols-1 gap-4">
            @foreach($collections as $collection)
            <a href="{{ route('opac.show', $collection->id) }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Cover -->
                    @if($collection->cover_image)
                    <img src="{{ asset('storage/' . $collection->cover_image) }}" alt="{{ $collection->title }}"
                        class="w-full sm:w-24 h-48 sm:h-32 object-cover rounded-xl flex-shrink-0">
                    @else
                    <div class="w-full sm:w-24 h-48 sm:h-32 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-16 h-16 sm:w-10 sm:h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    @endif

                    <!-- Info -->
                    <div class="flex-1">
                        <h3 class="text-base lg:text-lg font-medium text-gray-900 hover:text-blue-700 transition">{{ $collection->title }}</h3>

                        @if(is_array($collection->authors) && count($collection->authors) > 0)
                        <p class="text-sm text-gray-600 mt-1">
                            {{ collect($collection->authors)->pluck('name')->join(', ') }}
                        </p>
                        @endif

                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($collection->collectionType)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ $collection->collectionType->name }}</span>
                            @endif
                            @if($collection->year)
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $collection->year }}</span>
                            @endif
                            @if($collection->language)
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ strtoupper($collection->language) }}</span>
                            @endif
                        </div>

                        @if($collection->abstract)
                        <p class="text-sm text-gray-500 mt-2 line-clamp-2 hidden sm:block">{{ $collection->abstract }}</p>
                        @endif

                        <div class="flex items-center justify-between mt-3">
                            <div class="flex items-center text-sm text-gray-500">
                                @if($collection->available_items > 0)
                                <span class="flex items-center text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $collection->available_items }} tersedia
                                </span>
                                @else
                                <span class="flex items-center text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Tidak tersedia
                                </span>
                                @endif
                            </div>
                            <span class="text-sm text-blue-700 font-medium">Detail →</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $collections->appends(request()->except('page'))->links() }}
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada hasil ditemukan</h3>
            <p class="text-gray-500 mb-4">Coba gunakan kata kunci lain atau atur ulang filter pencarian.</p>
            <a href="{{ route('opac.search') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-xl transition min-h-[48px]">
                Reset Pencarian
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
