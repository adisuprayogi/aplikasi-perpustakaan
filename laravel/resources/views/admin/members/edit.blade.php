@extends('layouts.admin')

@section('title', 'Edit Anggota')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Anggota</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $member->name }} ({{ $member->member_no }})</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('members.show', $member) }}" class="text-blue-700 hover:text-blue-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <form method="POST" action="{{ route('members.update', $member) }}" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $member->name) }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="Nama lengkap anggota">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- ID Number -->
            <div>
                <label for="id_number" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Identitas</label>
                <input type="text" id="id_number" name="id_number" value="{{ old('id_number', $member->id_number) }}" required
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
                        <option value="{{ $branch->id }}" {{ old('branch_id', $member->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="nama@email.com">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $member->phone) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="081234567890">
                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select id="status" name="status" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                    <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="suspended" {{ old('status', $member->status) == 'suspended' ? 'selected' : '' }}>Disuspend</option>
                    <option value="expired" {{ old('status', $member->status) == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    <option value="blacklisted" {{ old('status', $member->status) == 'blacklisted' ? 'selected' : '' }}>Blacklist</option>
                </select>
                @error('status')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Valid From -->
            <div>
                <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-1.5">Berlaku Dari</label>
                <input type="date" id="valid_from" name="valid_from" value="{{ old('valid_from', $member->valid_from?->format('Y-m-d')) }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                @error('valid_from')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Valid Until -->
            <div>
                <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-1.5">Berlaku Hingga</label>
                <input type="date" id="valid_until" name="valid_until" value="{{ old('valid_until', $member->valid_until?->format('Y-m-d')) }}"
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
                    placeholder="Alamat lengkap">{{ old('address', $member->address) }}</textarea>
                @error('address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('members.show', $member) }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-800 hover:bg-blue-900 text-white font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition-all duration-200">
                Update Anggota
            </button>
        </div>
    </form>
</div>
@endsection
