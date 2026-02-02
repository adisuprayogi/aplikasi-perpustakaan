@extends('layouts.admin')

@section('title', 'Upload File Digital')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('digital-files.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Upload File Digital</h1>
            <p class="text-sm text-gray-500 mt-0.5">Tambah file digital baru ke perpustakaan</p>
        </div>
    </div>
</div>

<div class="max-w-3xl">
    <form method="POST" action="{{ route('digital-files.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @csrf

        <!-- Collection -->
        <div class="mb-5">
            <label for="collection_id" class="block text-sm font-medium text-gray-700 mb-2">
                Koleksi <span class="text-red-500">*</span>
            </label>
            <select name="collection_id" id="collection_id" required
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 bg-white transition">
                <option value="">Pilih Koleksi</option>
                @foreach($collections as $c)
                <option value="{{ $c->id }}" {{ $collection?->id == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                @endforeach
            </select>
            @error('collection_id')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Title -->
        <div class="mb-5">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Judul File <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 transition"
                placeholder="Masukkan judul file">
            @error('title')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- File Upload -->
        <div class="mb-5">
            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                File <span class="text-red-500">*</span>
            </label>
            <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-emerald-500 transition" id="dropzone">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-3">
                        <label for="file" class="cursor-pointer">
                            <span class="mt-1 block text-sm font-medium text-gray-900">
                                Klik untuk upload atau drag & drop
                            </span>
                            <input type="file" name="file" id="file" class="sr-only" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif" required>
                            <p class="mt-1 text-xs text-gray-500">
                                PDF, DOC, XLS, PPT, Images (Max 100MB)
                            </p>
                        </label>
                    </div>
                </div>
                <div id="file-preview" class="hidden mt-3 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span id="file-name" class="ml-2 text-sm text-gray-700"></span>
                        </div>
                        <button type="button" id="remove-file" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @error('file')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Access Level -->
        <div class="mb-5">
            <label for="access_level" class="block text-sm font-medium text-gray-700 mb-2">
                Level Akses <span class="text-red-500">*</span>
            </label>
            <select name="access_level" id="access_level" required
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 bg-white transition">
                <option value="">Pilih Level Akses</option>
                <option value="public" {{ old('access_level') == 'public' ? 'selected' : '' }}>Publik (Semua orang)</option>
                <option value="registered" {{ old('access_level') == 'registered' ? 'selected' : '' }}>Terdaftar (Login required)</option>
                <option value="campus_only" {{ old('access_level') == 'campus_only' ? 'selected' : '' }}>Kampus Saja (Member only)</option>
            </select>
            @error('access_level')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-5">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi
            </label>
            <textarea name="description" id="description" rows="3"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 transition"
                placeholder="Deskripsi singkat tentang file (opsional)">{{ old('description') }}</textarea>
            @error('description')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Version -->
        <div class="mb-5">
            <label for="version" class="block text-sm font-medium text-gray-700 mb-2">
                Versi
            </label>
            <input type="text" name="version" id="version" value="{{ old('version') }}"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 transition"
                placeholder="Contoh: 1.0, 2.1, Final (opsional)">
            @error('version')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Published At & Is Active -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <div>
                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Terbit
                </label>
                <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 transition">
                @error('published_at')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="flex items-center h-[46px]">
                    <input type="hidden" name="is_active" value="0">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-3 text-sm text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('digital-files.index') }}" class="px-5 py-2.5 text-gray-700 hover:text-gray-900 font-medium rounded-xl hover:bg-gray-100 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                Upload File
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const removeFile = document.getElementById('remove-file');
    const dropzone = document.getElementById('dropzone');

    // File input change
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            fileName.textContent = file.name;
            filePreview.classList.remove('hidden');
        }
    });

    // Remove file
    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.classList.add('hidden');
    });

    // Drag and drop
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropzone.classList.add('border-emerald-500', 'bg-emerald-50');
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropzone.classList.remove('border-emerald-500', 'bg-emerald-50');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropzone.classList.remove('border-emerald-500', 'bg-emerald-50');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileName.textContent = files[0].name;
            filePreview.classList.remove('hidden');
        }
    });
});
</script>
@endsection
