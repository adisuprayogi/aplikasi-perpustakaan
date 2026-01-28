@extends('layouts.public')

@section('title', 'Pencarian Lanjutan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pencarian Lanjutan</h1>
        <p class="mt-1 text-sm text-gray-500">Gunakan filter untuk mencari koleksi yang lebih spesifik</p>
    </div>

    <form action="{{ route('opac.search') }}" method="GET" class="bg-white rounded-lg shadow-sm p-6">
        <!-- Search Query -->
        <div class="mb-6">
            <label for="q" class="block text-sm font-medium text-gray-700 mb-2">Kata Kunci</label>
            <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Judul, ISBN, ISSN, atau kata kunci..."
                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Collection Type -->
            <div>
                <label for="collection_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Koleksi</label>
                <select name="collection_type" id="collection_type"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700">
                    <option value="">Semua Tipe</option>
                    @foreach($filterOptions['collection_types'] as $type)
                    <option value="{{ $type->id }}" {{ request('collection_type') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- GMD -->
            <div>
                <label for="gmd" class="block text-sm font-medium text-gray-700 mb-2">GMD (General Material Designation)</label>
                <select name="gmd" id="gmd"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700">
                    <option value="">Semua GMD</option>
                    @foreach($filterOptions['gmds'] as $gmd)
                    <option value="{{ $gmd->id }}" {{ request('gmd') == $gmd->id ? 'selected' : '' }}>
                        {{ $gmd->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Author -->
            <div>
                <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                <select name="author" id="author"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700">
                    <option value="">Semua Penulis</option>
                    @foreach($filterOptions['authors'] as $author)
                    <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                        {{ $author->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Subject -->
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subjek</label>
                <select name="subject" id="subject"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700">
                    <option value="">Semua Subjek</option>
                    @foreach($filterOptions['subjects'] as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Publisher -->
            <div>
                <label for="publisher" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                <select name="publisher" id="publisher"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700">
                    <option value="">Semua Penerbit</option>
                    @foreach($filterOptions['publishers'] as $publisher)
                    <option value="{{ $publisher->id }}" {{ request('publisher') == $publisher->id ? 'selected' : '' }}>
                        {{ $publisher->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Language -->
            <div>
                <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Bahasa</label>
                <select name="language" id="language"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700">
                    <option value="">Semua Bahasa</option>
                    @foreach($filterOptions['languages'] as $lang)
                    <option value="{{ $lang }}" {{ request('language') == $lang ? 'selected' : '' }}>
                        {{ strtoupper($lang) }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Year Range -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <input type="number" name="year_from" value="{{ request('year_from') }}" placeholder="Dari"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700">
                </div>
                <span class="text-gray-500">sampai</span>
                <div class="flex-1">
                    <input type="number" name="year_to" value="{{ request('year_to') }}" placeholder="Sampai"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700">
                </div>
            </div>
        </div>

        <!-- Available Only -->
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="available_only" value="1" {{ request('available_only') ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-700 border-gray-300 rounded focus:ring-blue-700">
                <span class="ml-2 text-sm text-gray-700">Hanya tampilkan koleksi yang tersedia</span>
            </label>
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-between">
            <a href="{{ route('opac.search') }}" class="text-sm text-gray-600 hover:text-gray-900">
                Reset Semua Filter
            </a>
            <div class="flex gap-3">
                <a href="{{ route('opac.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-lg transition">
                    Cari
                </button>
            </div>
        </div>
    </form>

    <!-- Search Tips -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="font-medium text-blue-900 mb-3">Tips Pencarian</h3>
        <ul class="text-sm text-blue-800 space-y-2">
            <li class="flex items-start">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Gunakan kata kunci spesifik untuk hasil yang lebih tepat
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Gunakan filter untuk mempersempit hasil pencarian
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Centang "Hanya yang tersedia" untuk melihat koleksi yang bisa dipinjam
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Gunakan range tahun untuk mencari koleksi dari periode tertentu
            </li>
        </ul>
    </div>
</div>
@endsection
