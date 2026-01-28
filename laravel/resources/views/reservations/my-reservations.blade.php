@extends('layouts.app')

@section('title', 'Reservasi Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Reservasi Saya</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Kelola reservasi koleksi perpustakaan Anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <a href="{{ route('reservations.my') }}" class="group relative overflow-hidden @if(request('status') == '') bg-gradient-to-br from-blue-500 to-blue-600 @else bg-white border border-gray-100 @endif rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative">
                    <p class="text-xs @if(request('status') == '') text-blue-100 @else text-gray-500 @endif font-medium mb-1">Semua</p>
                    <p class="text-2xl font-bold @if(request('status') == '') text-white @else text-gray-900 @endif">{{ $stats['pending'] + $stats['ready'] + $stats['fulfilled'] + $stats['cancelled'] + $stats['expired'] }}</p>
                </div>
            </a>

            <a href="{{ route('reservations.my', ['status' => 'pending']) }}" class="group relative overflow-hidden @if(request('status') == 'pending') bg-gradient-to-br from-amber-500 to-yellow-500 @else bg-white border border-gray-100 @endif rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative">
                    <p class="text-xs @if(request('status') == 'pending') text-amber-100 @else text-gray-500 @endif font-medium mb-1">Pending</p>
                    <p class="text-2xl font-bold @if(request('status') == 'pending') text-white @else text-gray-900 @endif">{{ $stats['pending'] ?? 0 }}</p>
                </div>
            </a>

            <a href="{{ route('reservations.my', ['status' => 'ready']) }}" class="group relative overflow-hidden @if(request('status') == 'ready') bg-gradient-to-br from-emerald-500 to-green-500 @else bg-white border border-gray-100 @endif rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative">
                    <p class="text-xs @if(request('status') == 'ready') text-emerald-100 @else text-gray-500 @endif font-medium mb-1">Siap Diambil</p>
                    <p class="text-2xl font-bold @if(request('status') == 'ready') text-white @else text-gray-900 @endif">{{ $stats['ready'] ?? 0 }}</p>
                </div>
            </a>

            <a href="{{ route('reservations.my', ['status' => 'fulfilled']) }}" class="group relative overflow-hidden @if(request('status') == 'fulfilled') bg-gradient-to-br from-blue-500 to-indigo-500 @else bg-white border border-gray-100 @endif rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative">
                    <p class="text-xs @if(request('status') == 'fulfilled') text-blue-100 @else text-gray-500 @endif font-medium mb-1">Dipenuhi</p>
                    <p class="text-2xl font-bold @if(request('status') == 'fulfilled') text-white @else text-gray-900 @endif">{{ $stats['fulfilled'] ?? 0 }}</p>
                </div>
            </a>

            <a href="{{ route('reservations.my', ['status' => 'cancelled']) }}" class="group relative overflow-hidden @if(request('status') == 'cancelled') bg-gradient-to-br from-gray-500 to-gray-600 @else bg-white border border-gray-100 @endif rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative">
                    <p class="text-xs @if(request('status') == 'cancelled') text-gray-100 @else text-gray-500 @endif font-medium mb-1">Batal/Exp</p>
                    <p class="text-2xl font-bold @if(request('status') == 'cancelled') text-white @else text-gray-900 @endif">{{ $stats['cancelled'] + $stats['expired'] }}</p>
                </div>
            </a>
        </div>

        <!-- Reservations Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Koleksi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Barcode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl Reservasi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kadaluarsa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($reservations as $reservation)
                        <tr class="hover:bg-gray-50/50 @if($reservation->isExpired()) bg-red-50/50 @endif transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start">
                                    @if($reservation->item->collection->cover_image)
                                    <div class="w-16 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                        <img src="{{ asset('storage/' . $reservation->item->collection->cover_image) }}" alt="{{ $reservation->item->collection->title }}" class="w-full h-full object-cover">
                                    </div>
                                    @else
                                    <div class="w-16 h-20 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 line-clamp-2">{{ $reservation->item->collection->title }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $reservation->item->branch->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">
                                {{ $reservation->item->barcode }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $reservation->reservation_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm @if($reservation->isExpired()) font-semibold text-red-600 @else text-gray-700 @endif">
                                    {{ $reservation->expiry_date->format('d/m/Y') }}
                                </span>
                                @if($reservation->isExpired())
                                <div class="text-xs text-red-500 mt-1 font-medium">Kedaluwarsa</div>
                                @elseif($reservation->expiry_date->diffInDays(now()) <= 2 && $reservation->status !== 'fulfilled')
                                <div class="text-xs text-amber-600 mt-1 font-medium">
                                    {{ $reservation->expiry_date->diffInDays(now()) == 0 ? 'Hari ini' : ($reservation->expiry_date->diffInDays(now()) . ' hari lagi') }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($reservation->status === 'pending')
                                    @if($reservation->isExpired())
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Kedaluwarsa</span>
                                    </div>
                                    @else
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Pending</span>
                                    </div>
                                    @endif
                                @elseif($reservation->status === 'ready')
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Siap Diambil</span>
                                </div>
                                @elseif($reservation->status === 'fulfilled')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Dipenuhi</span>
                                @elseif($reservation->status === 'cancelled')
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Dibatalkan</span>
                                </div>
                                @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">{{ ucfirst($reservation->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(in_array($reservation->status, ['pending', 'ready']))
                                <button
                                    x-data="open = false"
                                    @click="open = true"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium rounded-lg transition">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Batal
                                </button>

                                <!-- Cancel Confirmation Modal -->
                                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                                    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="open = false"></div>
                                    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" x-show="open">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">Batalkan Reservasi?</h3>
                                        <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin membatalkan reservasi untuk koleksi "{{ $reservation->item->collection->title }}"?</p>

                                        <form action="{{ route('reservations.cancel-my', $reservation) }}" method="POST" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan (opsional)</label>
                                                <textarea name="cancellation_reason" rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-600/20 focus:border-red-600" placeholder="Tulis alasan pembatalan..."></textarea>
                                            </div>
                                            <div class="flex gap-3">
                                                <button type="button" @click="open = false" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition font-medium">
                                                    Tidak
                                                </button>
                                                <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl transition font-medium">
                                                    Ya, Batalkan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @else
                                <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Belum ada reservasi</p>
                                    <p class="text-xs text-gray-400">Reservasi koleksi yang sedang Anda pinjam</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reservations->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh ready reservations every 30 seconds
    @if(request('status') == 'ready' || request('status') == '')
    setTimeout(function() {
        window.location.reload();
    }, 30000);
    @endif
</script>
@endpush
@endsection
