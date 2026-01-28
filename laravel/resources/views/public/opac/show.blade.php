@extends('layouts.public')

@section('title', $collection->title)

@section('content')
<div class="mb-6">
    <a href="{{ request()->header('referer') ?? route('opac.search') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Cover & Basic Info -->
    <div class="lg:col-span-1">
        <!-- Cover -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($collection->cover_image)
            <img src="{{ asset('storage/' . $collection->cover_image) }}" alt="{{ $collection->title }}"
                class="w-full">
            @else
            <div class="w-full aspect-[3/4] bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center">
                <svg class="w-24 h-24 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            @endif

            <div class="p-4">
                <h1 class="text-xl font-semibold text-gray-900">{{ $collection->title }}</h1>

                <!-- Availability Badge -->
                @if($totalAvailable > 0)
                <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center text-green-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div>
                            <p class="font-medium">{{ $totalAvailable }} item tersedia</p>
                            <p class="text-sm">{{ $collection->items->count() }} total item</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center text-red-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="font-medium">Tidak tersedia</p>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                @auth
                @can('loans.create')
                @if($totalAvailable > 0)
                <a href="{{ route('loans.create') }}?item_id={{ $collection->items->where('status', 'available')->first()->id }}" class="mt-4 w-full flex items-center justify-center px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-lg transition">
                    Pinjam Sekarang
                </a>
                @else
                <a href="{{ route('reservations.create') }}?item_id={{ $collection->items->first()->id }}" class="mt-4 w-full flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition">
                    Buat Reservasi
                </a>
                @endif
                @endcan
                @else
                <a href="{{ route('login') }}" class="mt-4 w-full flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    Login untuk Meminjam
                </a>
                @endauth
            </div>
        </div>

        <!-- Available Items List -->
        @if($collection->items->count() > 0)
        <div class="bg-white rounded-lg shadow-sm p-4 mt-4">
            <h3 class="font-medium text-gray-900 mb-3">Daftar Item</h3>
            <div class="space-y-2">
                @foreach($collection->items as $item)
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-mono text-gray-700">{{ $item->barcode }}</p>
                        <p class="text-xs text-gray-500">{{ $item->branch->name ?? '' }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full @if($item->status === 'available') bg-green-100 text-green-800 @elseif($item->status === 'borrowed') bg-blue-100 text-blue-800 @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($item->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column - Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(is_array($collection->authors) && count($collection->authors) > 0)
                <div>
                    <dt class="text-sm text-gray-500">Penulis</dt>
                    <dd class="text-sm text-gray-900 mt-1">{{ collect($collection->authors)->pluck('name')->join(', ') }}</dd>
                </div>
                @endif

                @if($collection->publisher)
                <div>
                    <dt class="text-sm text-gray-500">Penerbit</dt>
                    <dd class="text-sm text-gray-900 mt-1">{{ $collection->publisher->name }}</dd>
                </div>
                @endif

                @if($collection->year)
                <div>
                    <dt class="text-sm text-gray-500">Tahun Terbit</dt>
                    <dd class="text-sm text-gray-900 mt-1">{{ $collection->year }}</dd>
                </div>
                @endif

                @if($collection->collectionType)
                <div>
                    <dt class="text-sm text-gray-500">Tipe Koleksi</dt>
                    <dd class="text-sm text-gray-900 mt-1">{{ $collection->collectionType->name }}</dd>
                </div>
                @endif

                @if($collection->gmd)
                <div>
                    <dt class="text-sm text-gray-500">GMD</dt>
                    <dd class="text-sm text-gray-900 mt-1">{{ $collection->gmd->name }}</dd>
                </div>
                @endif

                @if($collection->language)
                <div>
                    <dt class="text-sm text-gray-500">Bahasa</dt>
                    <dd class="text-sm text-gray-900 mt-1">{{ strtoupper($collection->language) }}</dd>
                </div>
                @endif

                @if($collection->isbn)
                <div>
                    <dt class="text-sm text-gray-500">ISBN</dt>
                    <dd class="text-sm text-gray-900 mt-1 font-mono">{{ $collection->isbn }}</dd>
                </div>
                @endif

                @if($collection->issn)
                <div>
                    <dt class="text-sm text-gray-500">ISSN</dt>
                    <dd class="text-sm text-gray-900 mt-1 font-mono">{{ $collection->issn }}</dd>
                </div>
                @endif

                @if($collection->classification)
                <div>
                    <dt class="text-sm text-gray-500">Klasifikasi</dt>
                    <dd class="text-sm text-gray-900 mt-1">{{ $collection->classification->code }} - {{ $collection->classification->name }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Subjects -->
        @if($collection->subjects->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Subjek</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($collection->subjects as $subject)
                <a href="{{ route('opac.search', ['subject' => $subject->id]) }}" class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full hover:bg-blue-200 transition">
                    {{ $subject->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Abstract -->
        @if($collection->abstract)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Abstrak</h2>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $collection->abstract }}</p>
        </div>
        @endif

        <!-- Related Collections -->
        @if($relatedCollections->count() > 0)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Koleksi Terkait</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($relatedCollections as $related)
                <a href="{{ route('opac.show', $related->id) }}" class="group">
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                        @if($related->cover_image)
                        <img src="{{ asset('storage/' . $related->cover_image) }}" alt="{{ $related->title }}"
                            class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-200">
                        @else
                        <div class="w-full h-32 bg-gray-100 flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        @endif
                        <div class="p-3">
                            <h3 class="text-sm font-medium text-gray-900 line-clamp-2 group-hover:text-blue-700 transition">{{ $related->title }}</h3>
                            @if(is_array($related->authors) && count($related->authors) > 0)
                            <p class="text-xs text-gray-500 mt-1">{{ $related->authors[0]['name'] ?? '' }}</p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
