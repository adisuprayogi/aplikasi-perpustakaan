@extends('layouts.admin')

@section('title', 'Edit Branch')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Branch</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $branch->name }}</p>
        </div>
        <a href="{{ route('branches.show', $branch) }}" class="text-blue-700 hover:text-blue-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <form method="POST" action="{{ route('branches.update', $branch) }}" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Code -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1.5">Kode Branch</label>
                <input type="text" id="code" name="code" value="{{ old('code', $branch->code) }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="Contoh: PUSAT">
                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Branch</label>
                <select id="type" name="type" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                    <option value="">Pilih Tipe</option>
                    <option value="central" {{ old('type', $branch->type) == 'central' ? 'selected' : '' }}>Pusat (Central)</option>
                    <option value="faculty" {{ old('type', $branch->type) == 'faculty' ? 'selected' : '' }}>Fakultas</option>
                    <option value="study_program" {{ old('type', $branch->type) == 'study_program' ? 'selected' : '' }}>Program Studi</option>
                </select>
                @error('type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Branch</label>
                <input type="text" id="name" name="name" value="{{ old('name', $branch->name) }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="Contoh: Perpustakaan Pusat">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $branch->email) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="library@kampus.ac.id">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $branch->phone) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="021-12345678">
                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                <textarea id="address" name="address" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="Alamat lengkap branch">{{ old('address', $branch->address) }}</textarea>
                @error('address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Is Active -->
            <div class="md:col-span-2">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-800 border-gray-300 rounded focus:ring-blue-700">
                    <span class="ml-2 text-sm text-gray-700">Branch aktif</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('branches.show', $branch) }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-800 hover:bg-blue-900 text-white font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition-all duration-200">
                Update Branch
            </button>
        </div>
    </form>
</div>
@endsection
