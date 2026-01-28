@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<style>
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .tab-btn { border-bottom: 2px solid transparent; }
    .tab-btn.active { border-bottom-color: #4f46e5; color: #4f46e5; }
</style>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pengaturan Sistem</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Konfigurasi aplikasi perpustakaan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Tabs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6 overflow-x-auto" role="tablist">
                    @foreach($groups as $key => $label)
                        <button type="button"
                                onclick="switchTab('{{ $key }}')"
                                class="tab-btn whitespace-nowrap py-4 px-1 text-sm font-medium transition-colors duration-200 {{ $activeTab === $key ? 'active' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('settings.update') }}" id="settings-form">
                @csrf
                <input type="hidden" name="current_tab" id="current_tab" value="{{ $activeTab }}">

                <div class="p-6">
                    <!-- Library Info Tab -->
                    <div id="tab-library_info" class="tab-content {{ $activeTab === 'library_info' ? 'active' : '' }}">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Informasi Perpustakaan</h3>
                                <p class="mt-1 text-sm text-gray-500">Informasi dasar perpustakaan Anda.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-4">
                                    <label for="library_name" class="block text-sm font-medium text-gray-700">Nama Perpustakaan</label>
                                    <input type="text" id="library_name" name="library_name"
                                           value="{{ $settings['library.name'] ?? '' }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="library_address" class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <textarea id="library_address" name="library_address"
                                              rows="3"
                                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">{{ $settings['library.address'] ?? '' }}</textarea>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="library_phone" class="block text-sm font-medium text-gray-700">Telepon</label>
                                    <input type="text" id="library_phone" name="library_phone"
                                           value="{{ $settings['library.phone'] ?? '' }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="library_email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="library_email" name="library_email"
                                           value="{{ $settings['library.email'] ?? '' }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="library_open_hours" class="block text-sm font-medium text-gray-700">Jam Operasional</label>
                                    <input type="text" id="library_open_hours" name="library_open_hours"
                                           value="{{ $settings['library.open_hours'] ?? '' }}"
                                           placeholder="Contoh: Senin - Jumat: 08:00 - 16:00"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    @error('library_open_hours')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loan Settings Tab -->
                    <div id="tab-loan" class="tab-content {{ $activeTab === 'loan' ? 'active' : '' }}">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Pengaturan Peminjaman</h3>
                                <p class="mt-1 text-sm text-gray-500">Konfigurasi default untuk peminjaman.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="loan_default_period" class="block text-sm font-medium text-gray-700">Masa Peminjaman Default (hari)</label>
                                    <input type="number" id="loan_default_period" name="loan_default_period"
                                           min="1" max="365" value="{{ $settings['loan.default_period'] ?? 14 }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    @error('loan_default_period')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="loan_max_renewal" class="block text-sm font-medium text-gray-700">Maksimal Perpanjangan</label>
                                    <input type="number" id="loan_max_renewal" name="loan_max_renewal"
                                           min="0" max="10" value="{{ $settings['loan.max_renewal'] ?? 2 }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    @error('loan_max_renewal')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="loan_grace_period" class="block text-sm font-medium text-gray-700">Grace Period (hari)</label>
                                    <input type="number" id="loan_grace_period" name="loan_grace_period"
                                           min="0" max="30" value="{{ $settings['loan.grace_period'] ?? 0 }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    <p class="mt-1 text-xs text-gray-500">Jumlah toleransi hari sebelum denda dihitung</p>
                                    @error('loan_grace_period')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3 flex items-end">
                                    <div class="flex items-center h-full">
                                        <input type="checkbox" id="loan_auto_calc_fine" name="loan_auto_calc_fine"
                                               value="1" {{ ($settings['loan.auto_calc_fine'] ?? true) ? 'checked' : '' }}
                                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="loan_auto_calc_fine" class="ml-2 block text-sm text-gray-900">
                                            Otomatis Hitung Denda
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fine Settings Tab -->
                    <div id="tab-fine" class="tab-content {{ $activeTab === 'fine' ? 'active' : '' }}">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Pengaturan Denda</h3>
                                <p class="mt-1 text-sm text-gray-500">Konfigurasi perhitungan denda keterlambatan.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="fine_daily_rate" class="block text-sm font-medium text-gray-700">Tarif Denda Per Hari (Rp)</label>
                                    <input type="number" id="fine_daily_rate" name="fine_daily_rate"
                                           min="0" max="1000000" value="{{ $settings['fine.daily_rate'] ?? 1000 }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    @error('fine_daily_rate')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="fine_max_fine" class="block text-sm font-medium text-gray-700">Maksimal Denda Per Item (Rp)</label>
                                    <input type="number" id="fine_max_fine" name="fine_max_fine"
                                           min="0" max="10000000" value="{{ $settings['fine.max_fine'] ?? 50000 }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    @error('fine_max_fine')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="fine_currency" class="block text-sm font-medium text-gray-700">Mata Uang</label>
                                    <select id="fine_currency" name="fine_currency"
                                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                        <option value="IDR" {{ ($settings['fine.currency'] ?? 'IDR') === 'IDR' ? 'selected' : '' }}>IDR - Rupiah</option>
                                        <option value="USD" {{ ($settings['fine.currency'] ?? 'IDR') === 'USD' ? 'selected' : '' }}>USD - Dollar</option>
                                        <option value="EUR" {{ ($settings['fine.currency'] ?? 'IDR') === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    </select>
                                </div>

                                <div class="sm:col-span-3 flex items-end">
                                    <div class="flex items-center h-full">
                                        <input type="checkbox" id="fine_exclude_holidays" name="fine_exclude_holidays"
                                               value="1" {{ ($settings['fine.exclude_holidays'] ?? true) ? 'checked' : '' }}
                                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="fine_exclude_holidays" class="ml-2 block text-sm text-gray-900">
                                            Pengecualian Hari Libur
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reservation Settings Tab -->
                    <div id="tab-reservation" class="tab-content {{ $activeTab === 'reservation' ? 'active' : '' }}">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Pengaturan Reservasi</h3>
                                <p class="mt-1 text-sm text-gray-500">Konfigurasi sistem reservasi.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="reservation_max_per_member" class="block text-sm font-medium text-gray-700">Maksimal Reservasi Per Anggota</label>
                                    <input type="number" id="reservation_max_per_member" name="reservation_max_per_member"
                                           min="1" max="50" value="{{ $settings['reservation.max_per_member'] ?? 5 }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    @error('reservation_max_per_member')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="reservation_expiry_days" class="block text-sm font-medium text-gray-700">Masa Berlaku Reservasi (hari)</label>
                                    <input type="number" id="reservation_expiry_days" name="reservation_expiry_days"
                                           min="1" max="30" value="{{ $settings['reservation.expiry_days'] ?? 2 }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    @error('reservation_expiry_days')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-6 flex items-center">
                                    <input type="checkbox" id="reservation_allow_queue" name="reservation_allow_queue"
                                           value="1" {{ ($settings['reservation.allow_queue'] ?? true) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="reservation_allow_queue" class="ml-2 block text-sm text-gray-900">
                                        Izinkan Antrian Reservasi
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Settings Tab -->
                    <div id="tab-email" class="tab-content {{ $activeTab === 'email' ? 'active' : '' }}">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Pengaturan Email</h3>
                                <p class="mt-1 text-sm text-gray-500">Konfigurasi email untuk notifikasi.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-4">
                                    <label for="email_from_address" class="block text-sm font-medium text-gray-700">Email Pengirim</label>
                                    <input type="email" id="email_from_address" name="email_from_address"
                                           value="{{ $settings['email.from_address'] ?? 'noreply@library.com' }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    @error('email_from_address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-4">
                                    <label for="email_from_name" class="block text-sm font-medium text-gray-700">Nama Pengirim</label>
                                    <input type="text" id="email_from_name" name="email_from_name"
                                           value="{{ $settings['email.from_name'] ?? 'Perpustakaan Kampus' }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    @error('email_from_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-6 flex items-center">
                                    <input type="checkbox" id="email_notifications_enabled" name="email_notifications_enabled"
                                           value="1" {{ ($settings['email.notifications_enabled'] ?? true) ? 'checked' : '' }}"
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="email_notifications_enabled" class="ml-2 block text-sm text-gray-900">
                                        Aktifkan Notifikasi Email
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OPAC Settings Tab -->
                    <div id="tab-opac" class="tab-content {{ $activeTab === 'opac' ? 'active' : '' }}">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Pengaturan OPAC</h3>
                                <p class="mt-1 text-sm text-gray-500">Konfigurasi tampilan katalog publik.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="opac_items_per_page" class="block text-sm font-medium text-gray-700">Item Per Halaman</label>
                                    <input type="number" id="opac_items_per_page" name="opac_items_per_page"
                                           min="5" max="100" value="{{ $settings['opac.items_per_page'] ?? 12 }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition">
                                    @error('opac_items_per_page')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-6 flex items-center">
                                    <input type="checkbox" id="opac_enable_search" name="opac_enable_search"
                                           value="1" {{ ($settings['opac.enable_search'] ?? true) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="opac_enable_search" class="ml-2 block text-sm text-gray-900">
                                        Aktifkan Pencarian
                                    </label>
                                </div>

                                <div class="sm:col-span-6 flex items-center">
                                    <input type="checkbox" id="opac_show_cover" name="opac_show_cover"
                                           value="1" {{ ($settings['opac.show_cover'] ?? true) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="opac_show_cover" class="ml-2 block text-sm text-gray-900">
                                        Tampilkan Cover Gambar
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                    <form method="POST" action="{{ route('settings.reset') }}" onsubmit="return confirm('Apakah Anda yakin ingin mereset pengaturan ke nilai default?');">
                        @csrf
                        <button type="submit"
                                class="text-sm text-red-600 hover:text-red-800">
                            Reset ke Default
                        </button>
                    </form>

                    <button type="submit"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(tabKey) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });

    // Show selected tab
    document.getElementById('tab-' + tabKey).classList.add('active');

    // Update current tab input
    document.getElementById('current_tab').value = tabKey;

    // Find and activate the button
    const buttons = document.querySelectorAll('.tab-btn');
    const groups = @json(array_keys($groups));
    const index = groups.indexOf(tabKey);
    if (index >= 0 && buttons[index]) {
        buttons[index].classList.add('active');
        buttons[index].classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    }
}
</script>
@endsection
