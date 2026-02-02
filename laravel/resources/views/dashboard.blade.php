@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Hero Section Welcome Banner -->
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

    <div class="relative px-8 py-10 sm:px-12 lg:px-16">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Selamat Datang!</h1>
                        <p class="text-blue-200 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <p class="text-blue-100 max-w-xl">
                    Halo, <span class="font-semibold text-white">{{ auth()->user()->name }}</span>
                    @if(auth()->user()->branch)
                    <span class="text-blue-200"> dari </span><span class="font-semibold text-white">{{ auth()->user()->branch->name }}</span>
                    @endif
                </p>
            </div>

            <!-- User Info Card -->
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm text-blue-200">Role</p>
                        <p class="text-white font-semibold">{{ ucfirst(auth()->user()->roles->first()->name ?? 'User') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions - Role Based -->
@if(isset($quickActions) && count($quickActions) > 0)
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">Aksi Cepat</h2>
            <p class="text-sm text-gray-500">Menu penting untuk {{ auth()->user()->roles->first()->name ?? 'user' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($quickActions as $action)
        @can($action['permission'] ?? '*')
        <a href="{{ route($action['route']) }}" class="group flex flex-col items-center p-5 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-{{ $action['color'] }}-200">
            <div class="w-14 h-14 bg-gradient-to-br from-{{ $action['color'] }}-500 to-{{ $action['color'] }}-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 text-center group-hover:text-{{ $action['color'] }}-600 transition-colors line-clamp-2">{{ $action['title'] }}</h3>
        </a>
        @endcan
        @endforeach
    </div>
</div>
@endif

<!-- Primary Stats Grid - Same as OPAC Style -->
@if(isset($stats) && count($stats) > 0)
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Total Members -->
    @if(isset($stats['total_members']))
    <div class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['total_members']) }}</p>
            <p class="text-blue-100 text-sm mt-1">Total Anggota</p>
            @if(isset($stats['active_members']))
            <p class="text-blue-200 text-xs mt-2">{{ number_format($stats['active_members']) }} aktif</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Active Loans -->
    @if(isset($stats['active_loans']))
    <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['active_loans']) }}</p>
            <p class="text-emerald-100 text-sm mt-1">Peminjaman Aktif</p>
        </div>
    </div>
    @endif

    <!-- Available Items -->
    @if(isset($stats['available_items']))
    <div class="group relative overflow-hidden bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['available_items']) }}</p>
            <p class="text-violet-100 text-sm mt-1">Item Tersedia</p>
        </div>
    </div>
    @endif

    <!-- Overdue Loans - Changes color if > 0 -->
    @if(isset($stats['overdue_loans']))
    <div class="group relative overflow-hidden @if($stats['overdue_loans'] > 0) from-red-500 to-rose-600 @else from-gray-500 to-gray-600 @endif bg-gradient-to-br rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($stats['overdue_loans'] > 0)
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center animate-pulse">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                @endif
            </div>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['overdue_loans']) }}</p>
            <p class="text-white/80 text-sm mt-1">Terlambat</p>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Secondary Stats - Smaller Cards -->
@if(isset($stats) && count($stats) > 0)
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @if(isset($stats['total_collections']))
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Koleksi</p>
                <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_collections']) }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(isset($stats['total_items']))
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Item</p>
                <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_items']) }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(isset($stats['pending_reservations']))
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Reservasi</p>
                <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['pending_reservations']) }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(isset($stats['total_branches']))
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Branch</p>
                <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_branches']) }}</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Overdue Alert Banner -->
@if($overdueLoans && $overdueLoans->count() > 0)
<div class="mb-8 bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 rounded-r-xl p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-red-800">{{ $overdueLoans->count() }} Peminjaman Terlambat</h3>
                <p class="text-sm text-red-700 mt-1">Ada peminjaman yang melewati tanggal pengembalian. Segera hubungi anggota.</p>
            </div>
        </div>
        <a href="{{ route('loans.index') }}?status=overdue" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
            Lihat Detail
        </a>
    </div>
</div>
@endif

<!-- Quick Actions - Same Style as OPAC Categories -->
<div>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">Aksi Cepat</h2>
            <p class="text-sm text-gray-500">Transaksi yang sering dilakukan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @can('loans.create')
        <a href="{{ route('loans.create') }}" class="group flex items-center p-5 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-5 group-hover:scale-110 transition-transform shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Peminjaman Baru</h3>
                <p class="text-sm text-gray-500">Proses peminjaman koleksi</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endcan

        @can('loans.return')
        <a href="{{ route('loans.index') }}?action=return" class="group flex items-center p-5 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-emerald-200">
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mr-5 group-hover:scale-110 transition-transform shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">Pengembalian</h3>
                <p class="text-sm text-gray-500">Proses pengembalian</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endcan

        @can('members.create')
        <a href="{{ route('members.create') }}" class="group flex items-center p-5 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-violet-200">
            <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl flex items-center justify-center mr-5 group-hover:scale-110 transition-transform shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition-colors">Registrasi Anggota</h3>
                <p class="text-sm text-gray-500">Tambah anggota baru</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-violet-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endcan

        @can('reservations.create')
        <a href="{{ route('reservations.create') }}" class="group flex items-center p-5 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-amber-200">
            <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mr-5 group-hover:scale-110 transition-transform shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">Reservasi Baru</h3>
                <p class="text-sm text-gray-500">Booking koleksi</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 group-hover:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endcan
    </div>
</div>

<!-- Recent Activity Section -->
@if($recentLoans && $recentLoans->count() > 0)
<div class="mt-10">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Aktivitas Terbaru</h2>
                <p class="text-sm text-gray-500">{{ $recentLoans->count() }} transaksi terakhir</p>
            </div>
        </div>
        <a href="{{ route('loans.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium text-sm">
            Lihat Semua
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggota</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Koleksi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($recentLoans as $loan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3 shadow">
                                    <span class="text-sm font-semibold text-white">{{ strtoupper(substr($loan->member->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $loan->member->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $loan->member->member_no }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900 font-medium line-clamp-1">{{ $loan->item->collection->title }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $loan->item->barcode }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                                {{ $loan->loanBranch->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $loan->loan_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $loan->due_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($loan->status === 'active')
                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                    Aktif
                                </span>
                            @elseif($loan->status === 'returned')
                                <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                                    Dikembalikan
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
