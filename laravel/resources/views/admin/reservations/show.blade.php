@extends('layouts.admin')

@section('title', 'Detail Reservasi')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Reservasi</h1>
            <p class="mt-1 text-sm text-gray-500">Informasi lengkap reservasi #{{ $reservation->id }}</p>
        </div>
        <a href="{{ route('reservations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Reservation Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Reservation Info -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Informasi Reservasi</h2>
                @if($reservation->status === 'pending')
                    @if($reservation->isExpired())
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        Kedaluwarsa
                    </span>
                    @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Pending
                    </span>
                    @endif
                @elseif($reservation->status === 'ready')
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    Siap Diambil
                </span>
                @elseif($reservation->status === 'fulfilled')
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    Dipenuhi
                </span>
                @elseif($reservation->status === 'cancelled')
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                    Dibatalkan
                </span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">ID Reservasi</p>
                    <p class="text-sm font-medium text-gray-900">#{{ $reservation->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Reservasi</p>
                    <p class="text-sm font-medium text-gray-900">{{ $reservation->reservation_date->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Berlaku Hingga</p>
                    <p class="text-sm font-medium @if($reservation->isExpired()) text-red-600 @else text-gray-900 @endif">
                        {{ $reservation->expiry_date->format('d/m/Y') }}
                        @if($reservation->isExpired())
                        <span class="text-xs">(Kedaluwarsa)</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Branch Pengambilan</p>
                    <p class="text-sm font-medium text-gray-900">{{ $reservation->branch->name }}</p>
                </div>
            </div>

            @if($reservation->notes)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500">Catatan</p>
                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $reservation->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Member Info -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Anggota</h2>
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-800 rounded-full flex items-center justify-center text-white font-medium text-lg">
                    {{ strtoupper(substr($reservation->member->name, 0, 1)) }}
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $reservation->member->name }}</p>
                    <p class="text-sm text-gray-500">{{ $reservation->member->member_no }} - {{ $reservation->member->type }}</p>
                </div>
                <a href="{{ route('members.show', $reservation->member) }}" class="px-3 py-1 text-sm text-blue-700 hover:text-blue-900 font-medium">
                    Lihat Profil
                </a>
            </div>
        </div>

        <!-- Item Info -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Koleksi</h2>
            <div class="flex">
                @if($reservation->item->collection->cover_image)
                <img src="{{ asset('storage/' . $reservation->item->collection->cover_image) }}" alt="{{ $reservation->item->collection->title }}"
                    class="w-20 h-28 object-cover rounded-lg shadow">
                @else
                <div class="w-20 h-28 bg-gray-200 rounded-lg flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                @endif
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $reservation->item->collection->title }}</p>
                    @if($reservation->item->collection->authors)
                    <p class="text-sm text-gray-500">{{ is_array($reservation->item->collection->authors) ? implode(', ', array_column($reservation->item->collection->authors, 'name')) : $reservation->item->collection->authors }}</p>
                    @endif
                    <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                        <span class="px-2 py-1 bg-gray-100 rounded">{{ $reservation->item->barcode }}</span>
                        <span class="px-2 py-1 rounded @if($reservation->item->status === 'available') bg-green-100 text-green-800 @elseif($reservation->item->status === 'borrowed') bg-blue-100 text-blue-800 @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($reservation->item->status) }}
                        </span>
                        <span>{{ $reservation->item->branch->name ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Actions -->
    <div class="space-y-6">
        <!-- Status Timeline -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status</h2>
            <div class="space-y-3">
                <div class="flex items-center @if(in_array($reservation->status, ['pending', 'ready', 'fulfilled', 'cancelled', 'expired'])) text-blue-600 @else text-gray-400 @endif">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">Reservasi Dibuat</span>
                </div>
                @if($reservation->status === 'ready' || $reservation->status === 'fulfilled')
                <div class="flex items-center text-blue-600">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">Item Siap Diambil</span>
                </div>
                @endif
                @if($reservation->status === 'fulfilled')
                <div class="flex items-center text-blue-600">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">Reservasi Dipenuhi</span>
                </div>
                @endif
                @if($reservation->status === 'cancelled')
                <div class="flex items-center text-gray-500">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">Dibatalkan</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if(in_array($reservation->status, ['pending', 'ready']))
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h2>
            <div class="space-y-3">
                @if($reservation->status === 'pending')
                    @can('reservations.create')
                    <form method="POST" action="{{ route('reservations.mark-ready', $reservation) }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Tandai Siap Diambil
                        </button>
                    </form>
                    @endcan
                @endif

                @if($reservation->status === 'ready')
                    @can('reservations.create')
                    <button onclick="openFulfillModal()" class="w-full flex items-center justify-center px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Penuhi Reservasi
                    </button>
                    @endcan
                @endif

                <button onclick="openCancelModal()" class="w-full flex items-center justify-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batalkan Reservasi
                </button>
            </div>
        </div>
        @endif

        <!-- Processed By -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Diproses Oleh</h2>
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-medium">
                    {{ strtoupper(substr($reservation->processedBy->name, 0, 1)) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $reservation->processedBy->name }}</p>
                    <p class="text-xs text-gray-500">{{ $reservation->processedBy->branch->name ?? 'System' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div x-data="{ show: false }" x-show="show" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Batalkan Reservasi</h3>
            <form method="POST" action="{{ route('reservations.cancel', $reservation) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan (Opsional)</label>
                    <textarea name="cancellation_reason" rows="3"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"
                        placeholder="Jelaskan alasan pembatalan..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                        Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Fulfill Modal -->
<div x-data="{ show: false }" x-show="show" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Penuhi Reservasi</h3>
            <p class="text-sm text-gray-600 mb-4">Reservasi akan dipenuhi dengan membuat peminjaman baru untuk anggota ini.</p>
            <form method="POST" action="{{ route('reservations.fulfill', $reservation) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Branch Peminjaman</label>
                    <select name="loan_branch_id" required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branch->id === $reservation->branch_id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white text-sm font-medium rounded-lg">
                        Proses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openCancelModal() {
    document.querySelector('[x-data*="show: false"]').__x.$data.show = true;
}

function openFulfillModal() {
    document.querySelectorAll('[x-data*="show: false"]').forEach(el => {
        if (el.querySelector('form[action*="fulfill"]')) {
            el.__x.$data.show = true;
        }
    });
}
</script>
@endpush
@endsection
