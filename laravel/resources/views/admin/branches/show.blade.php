@extends('layouts.admin')

@section('title', $branch->name)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $branch->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">Detail branch perpustakaan</p>
        </div>
        <div class="flex items-center space-x-2">
            @can('branches.edit')
            <a href="{{ route('branches.edit', $branch) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            @endcan
            <a href="{{ route('branches.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </div>

    <!-- Status Alert -->
    @if($branch->trashed())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-red-800">Branch ini telah dihapus</p>
                </div>
                @can('branches.edit')
                <form method="POST" action="{{ route('branches.restore', $branch) }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-red-800 underline hover:text-red-900">Pulihkan</button>
                </form>
                @endcan
            </div>
        </div>
    @endif
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Anggota</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_members']) }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Anggota Aktif</p>
                <p class="text-2xl font-semibold text-green-600">{{ number_format($stats['active_members']) }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Item</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_items']) }}</p>
            </div>
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Item Tersedia</p>
                <p class="text-2xl font-semibold text-blue-600">{{ number_format($stats['available_items']) }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Branch Info -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Branch</h3>
        <dl class="space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Kode</dt>
                <dd class="text-sm text-gray-900 font-mono">{{ $branch->code }}</dd>
            </div>
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Tipe</dt>
                <dd>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($branch->type === 'central') bg-purple-100 text-purple-800
                        @elseif($branch->type === 'faculty') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($branch->type) }}
                    </span>
                </dd>
            </div>
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd>
                    @if($branch->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                    @endif
                </dd>
            </div>
            @if($branch->email)
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="text-sm text-gray-900">{{ $branch->email }}</dd>
            </div>
            @endif
            @if($branch->phone)
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                <dd class="text-sm text-gray-900">{{ $branch->phone }}</dd>
            </div>
            @endif
            @if($branch->address)
            <div class="pt-3">
                <dt class="text-sm font-medium text-gray-500 mb-1">Alamat</dt>
                <dd class="text-sm text-gray-900">{{ $branch->address }}</dd>
            </div>
            @endif
        </dl>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
        <div class="space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Anggota Baru</span>
                    <span class="text-sm text-gray-500">{{ $branch->members->count() }}</span>
                </div>
                @if($branch->members->count() > 0)
                    <div class="space-y-2">
                        @foreach($branch->members->take(3) as $member)
                            <div class="flex items-center justify-between text-sm py-2 border-b border-gray-50 last:border-0">
                                <span class="text-gray-900">{{ $member->name }}</span>
                                <span class="text-gray-500">{{ $member->member_no }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
