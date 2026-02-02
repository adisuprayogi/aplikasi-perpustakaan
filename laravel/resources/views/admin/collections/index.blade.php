@extends('layouts.admin')

@section('title', 'Koleksi')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Koleksi Bibliografis</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola data koleksi perpustakaan</p>
                </div>
            </div>
        </div>
        @can('collections.create')
        <a href="{{ route('collections.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Koleksi
        </a>
        @endcan
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 mb-6">
    <form method="GET" action="{{ route('collections.index') }}" class="flex flex-col gap-3">
        <div class="w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul/penulis/ISBN..."
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition text-sm">
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Koleksi</label>
                <select name="collection_type_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition text-sm">
                    <option value="">Semua Tipe</option>
                    @foreach($collectionTypes as $type)
                    <option value="{{ $type->id }}" {{ request('collection_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <label class="flex items-center px-4 py-2.5 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-100 transition h-[42px]">
                    <input type="checkbox" name="available_only" value="1" {{ request('available_only') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 whitespace-nowrap">Hanya tersedia</span>
                </label>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 sm:flex-none px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition font-medium text-sm whitespace-nowrap">
                Cari
            </button>
            @if(request()->hasAny('search', 'collection_type_id', 'available_only'))
            <a href="{{ route('collections.index') }}" class="flex-1 sm:flex-none px-5 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition font-medium text-sm whitespace-nowrap text-center">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
    @forelse($collections as $collection)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
        @if($collection->cover_image)
        <div class="relative h-40 sm:h-48 bg-gray-100">
            <img src="{{ Storage::url($collection->cover_image) }}" alt="{{ $collection->title }}" class="w-full h-full object-cover">
        </div>
        @else
        <div class="h-40 sm:h-48 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        @endif

        <div class="p-4 sm:p-5">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 line-clamp-2 mb-2">
                {{ $collection->title }}
            </h3>

            @if($collection->authors)
            <p class="text-xs sm:text-sm text-gray-500 mb-2 line-clamp-1">
                {{ is_array($collection->authors) ? implode(', ', array_column($collection->authors, 'name')) : $collection->authors }}
            </p>
            @endif

            @if($collection->publisher)
            <p class="text-xs sm:text-sm text-gray-500 mb-2">
                {{ $collection->publisher->name }}
                @if($collection->year) · {{ $collection->year }}
                @endif
            </p>
            @endif

            <div class="flex items-center justify-between mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                <div class="flex items-center space-x-2 sm:space-x-4 text-xs sm:text-sm">
                    <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 rounded-lg">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        {{ $collection->total_items }}
                    </span>
                    <span class="inline-flex items-center px-2 py-1 {{ $collection->available_items > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} rounded-lg">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $collection->available_items }}
                    </span>
                </div>

                <div class="flex items-center space-x-2">
                    @can('collections.view')
                    <a href="{{ route('collections.show', $collection) }}" class="text-blue-600 hover:text-blue-900 font-medium text-xs sm:text-sm">Lihat</a>
                    @endcan
                    @can('collections.edit')
                    <a href="{{ route('collections.edit', $collection) }}" class="text-blue-600 hover:text-blue-900 font-medium text-xs sm:text-sm">Edit</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-8 sm:py-12 text-center">
        <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-300 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <p class="text-xs sm:text-sm text-gray-500">Belum ada data koleksi.</p>
    </div>
    @endforelse
</div>

@if($collections->hasPages())
<div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
    {{ $collections->appends(request()->except('page'))->links() }}
</div>
@endif
@endsection
