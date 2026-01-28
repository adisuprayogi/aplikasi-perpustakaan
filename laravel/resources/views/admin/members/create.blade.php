@extends('layouts.admin')

@section('title', 'Registrasi Anggota')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Registrasi Anggota Baru</h1>
            <p class="mt-1 text-sm text-gray-500">Daftarkan anggota perpustakaan baru</p>
        </div>
        <a href="{{ route('members.index') }}" class="text-blue-700 hover:text-blue-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <form method="POST" action="{{ route('members.store') }}" class="p-6">
        @csrf

        <!-- Type Selection -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Tipe Anggota</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="relative">
                    <input type="radio" name="type" value="student" required
                        class="peer sr-only" {{ old('type') == 'student' ? 'checked' : '' }}>
                    <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-800 peer-checked:bg-blue-50 hover:border-gray-300 transition">
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-600 peer-checked:text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-900">Mahasiswa</p>
                        </div>
                    </div>
                </label>

                <label class="relative">
                    <input type="radio" name="type" value="lecturer" required
                        class="peer sr-only" {{ old('type') == 'lecturer' ? 'checked' : '' }}>
                    <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-800 peer-checked:bg-blue-50 hover:border-gray-300 transition">
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-900">Dosen</p>
                        </div>
                    </div>
                </label>

                <label class="relative">
                    <input type="radio" name="type" value="staff" required
                        class="peer sr-only" {{ old('type') == 'staff' ? 'checked' : '' }}>
                    <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-800 peer-checked:bg-blue-50 hover:border-gray-300 transition">
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-900">Staf</p>
                        </div>
                    </div>
                </label>

                <label class="relative">
                    <input type="radio" name="type" value="external" required
                        class="peer sr-only" {{ old('type') == 'external' ? 'checked' : '' }}>
                    <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-800 peer-checked:bg-blue-50 hover:border-gray-300 transition">
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-900">Eksternal</p>
                        </div>
                    </div>
                </label>
            </div>
            @error('type')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="Nama lengkap anggota">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- ID Number -->
            <div>
                <label for="id_number" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Identitas</label>
                <input type="text" id="id_number" name="id_number" value="{{ old('id_number') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="NIM/NIP/NIK">
                @error('id_number')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Branch -->
            <div>
                <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1.5">Branch</label>
                <select id="branch_id" name="branch_id" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                    <option value="">Pilih Branch</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="nama@email.com">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="081234567890">
                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Valid From -->
            <div>
                <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-1.5">Berlaku Dari</label>
                <input type="date" id="valid_from" name="valid_from" value="{{ old('valid_from', now()->format('Y-m-d')) }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                @error('valid_from')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Valid Until -->
            <div>
                <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-1.5">Berlaku Hingga</label>
                <input type="date" id="valid_until" name="valid_until" value="{{ old('valid_until') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                @error('valid_until')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                <textarea id="address" name="address" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('members.index') }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-800 hover:bg-blue-900 text-white font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition-all duration-200">
                Daftarkan Anggota
            </button>
        </div>
    </form>
</div>
@endsection
