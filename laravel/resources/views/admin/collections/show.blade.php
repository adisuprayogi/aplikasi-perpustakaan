@extends('layouts.admin')

@section('title', $collection->title)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $collection->title }}</h1>
            <p class="mt-1 text-sm text-gray-500">Detail koleksi bibliografis</p>
        </div>
        <div class="flex items-center space-x-2">
            @can('collections.edit')
            <a href="{{ route('collections.edit', $collection) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            @endcan
            <a href="{{ route('collections.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Copy</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($collection->total_items) }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Tersedia</p>
                <p class="text-2xl font-semibold text-green-600">{{ number_format($collection->available_items) }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Dipinjam</p>
                <p class="text-2xl font-semibold text-red-600">{{ number_format($collection->borrowed_items) }}</p>
            </div>
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Collection Info -->
    <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Bibliografis</h3>

        @if($collection->cover_image)
        <div class="mb-4">
            <img src="{{ Storage::url($collection->cover_image) }}" alt="{{ $collection->title }}" class="w-32 h-48 object-cover rounded-lg">
        </div>
        @endif

        <dl class="space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Penulis</dt>
                <dd class="text-sm text-gray-900 text-right max-w-xs">{{ is_array($collection->authors) ? implode(', ', array_column($collection->authors, 'name')) : $collection->authors }}</dd>
            </div>

            @if($collection->isbn)
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                <dd class="text-sm text-gray-900 font-mono">{{ $collection->isbn }}</dd>
            </div>
            @endif

            @if($collection->publisher)
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Penerbit</dt>
                <dd class="text-sm text-gray-900">{{ $collection->publisher->name }} @if($collection->year) · {{ $collection->year }} @endif</dd>
            </div>
            @endif

            @if($collection->collectionType)
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Tipe Koleksi</dt>
                <dd>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $collection->collectionType->code }}</span>
                </dd>
            </div>
            @endif

            @if($collection->classification)
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Klasifikasi</dt>
                <dd class="text-sm text-gray-900">{{ $collection->classification->code }} - {{ $collection->classification->name }}</dd>
            </div>
            @endif

            @if($collection->gmd)
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">GMD</dt>
                <dd class="text-sm text-gray-900">{{ $collection->gmd->name }}</dd>
            </div>
            @endif

            @if($collection->pages)
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Halaman</dt>
                <dd class="text-sm text-gray-900">{{ $collection->pages }} hal</dd>
            </div>
            @endif

            @if($collection->subjects && $collection->subjects->count() > 0)
            <div class="pt-3">
                <dt class="text-sm font-medium text-gray-500 mb-2">Subjek</dt>
                <div class="flex flex-wrap gap-2">
                    @foreach($collection->subjects as $subject)
                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded">{{ $subject->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($collection->abstract)
            <div class="pt-3">
                <dt class="text-sm font-medium text-gray-500 mb-2">Abstrak</dt>
                <dd class="text-sm text-gray-700">{{ $collection->abstract }}</dd>
            </div>
            @endif
        </dl>
    </div>

    <!-- Items List -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Item / Copy</h3>
            @if($collection->available_items > 0)
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ $collection->available_items }} tersedia</span>
            @endif
        </div>

        @if($items->count() > 0)
            <div class="space-y-3">
                @foreach($items as $item)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div>
                        <p class="text-sm font-mono font-medium text-gray-900">{{ $item->barcode }}</p>
                        <p class="text-xs text-gray-500">{{ $item->branch->name ?? '-' }}</p>
                    </div>
                    <div>
                        @if($item->status === 'available')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                        @elseif($item->status === 'borrowed')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Dipinjam</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($item->status) }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p>Belum ada item</p>
            </div>
        @endif
    </div>
</div>

<!-- Loans History -->
@if($loans->count() > 0)
<div class="mt-6 bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Peminjaman Terakhir</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggota</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barcode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($loans as $loan)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $loan->member->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500 font-mono">{{ $loan->item->barcode }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $loan->loan_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $loan->return_date ? $loan->return_date->format('d/m/Y') : '-' }}</td>
                    <td class="px-4 py-3">
                        @if($loan->status === 'active')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @elseif($loan->status === 'returned')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Dikembalikan</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
