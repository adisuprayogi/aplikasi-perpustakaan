@extends('layouts.public')

@section('title', 'Hasil Pencarian: ' . $query)

@section('content')
<!-- Search Header -->
<div class="bg-gradient-to-br from-purple-600 via-violet-600 to-indigo-700 text-white py-12 lg:py-16 rounded-2xl mb-8 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>

    <div class="relative px-6">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h1 class="text-2xl lg:text-3xl font-bold">Hasil Pencarian</h1>
        </div>
        <p class="text-purple-100 text-lg">
            Menampilkan hasil untuk: <span class="font-semibold">"{{ $query }}"</span>
        </p>
        <p class="text-purple-200 text-sm mt-2">
            {{ $repositories->total() }} hasil ditemukan
        </p>
    </div>
</div>

<!-- Results -->
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada hasil</h3>
        <p class="text-gray-500 mb-6">Tidak ditemukan karya yang sesuai dengan pencarian Anda.</p>
        <a href="{{ route('repository.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            Lihat Semua Karya
        </a>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($repositories->hasPages())
<div class="mt-8">
    {{ $repositories->appends(['q' => $query])->links() }}
</div>
@endif
@endsection
