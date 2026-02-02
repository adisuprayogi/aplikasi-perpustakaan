@extends('layouts.admin')

@section('title', 'Edit Repository: ' . $repository->title)

@section('content')
<!-- Page Header -->
<div class="mb-6 lg:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl lg:text-2xl font-bold text-gray-900 line-clamp-2">Edit Repository</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $repository->title }}</p>
                </div>
            </div>
        </div>
        <a href="{{ route('repositories.show', $repository) }}" class="hidden sm:inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <form method="POST" action="{{ route('repositories.update', $repository) }}" enctype="multipart/form-data" class="p-4 lg:p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4 lg:space-y-6">
                <!-- Title -->
                <div class="lg:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $repository->title) }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                        placeholder="Judul lengkap karya ilmiah">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Author Information -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informasi Penulis
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="author_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Penulis *</label>
                            <input type="text" id="author_name" name="author_name" value="{{ old('author_name', $repository->author_name) }}" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                                placeholder="Nama lengkap penulis">
                            @error('author_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="author_nim" class="block text-sm font-medium text-gray-700 mb-2">NIM</label>
                            <input type="text" id="author_nim" name="author_nim" value="{{ old('author_nim', $repository->author_nim) }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                                placeholder="Nomor Induk Mahasiswa">
                            @error('author_nim')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="author_email" class="block text-sm font-medium text-gray-700 mb-2">Email Penulis</label>
                            <input type="email" id="author_email" name="author_email" value="{{ old('author_email', $repository->author_email) }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                                placeholder="email@example.com">
                            @error('author_email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Advisor Information -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Informasi Pembimbing
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="advisor_name" class="block text-sm font-medium text-gray-700 mb-2">Pembimbing Utama</label>
                            <input type="text" id="advisor_name" name="advisor_name" value="{{ old('advisor_name', $repository->advisor_name) }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                                placeholder="Nama pembimbing utama">
                            @error('advisor_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="co_advisor_name" class="block text-sm font-medium text-gray-700 mb-2">Co-Pembimbing</label>
                            <input type="text" id="co_advisor_name" name="co_advisor_name" value="{{ old('co_advisor_name', $repository->co_advisor_name) }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                                placeholder="Nama co-pembimbing">
                            @error('co_advisor_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Abstract -->
                <div class="lg:col-span-2">
                    <label for="abstract" class="block text-sm font-medium text-gray-700 mb-2">Abstrak</label>
                    <textarea id="abstract" name="abstract" rows="4"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                        placeholder="Abstrak karya ilmiah...">{{ old('abstract', $repository->abstract) }}</textarea>
                    @error('abstract')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keywords -->
                <div class="lg:col-span-2">
                    <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">Kata Kunci</label>
                    <input type="text" id="keywords" name="keywords" value="{{ old('keywords', $repository->keywords) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                        placeholder="kata1, kata2, kata3...">
                    @error('keywords')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4 lg:space-y-6">
                <!-- Document Type -->
                <div>
                    <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Dokumen *</label>
                    <select id="document_type" name="document_type" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition bg-white">
                        <option value="">Pilih Tipe Dokumen</option>
                        <option value="undergraduate_thesis" {{ old('document_type', $repository->document_type) == 'undergraduate_thesis' ? 'selected' : '' }}>Skripsi</option>
                        <option value="masters_thesis" {{ old('document_type', $repository->document_type) == 'masters_thesis' ? 'selected' : '' }}>Tesis</option>
                        <option value="doctoral_dissertation" {{ old('document_type', $repository->document_type) == 'doctoral_dissertation' ? 'selected' : '' }}>Disertasi</option>
                        <option value="research_paper" {{ old('document_type', $repository->document_type) == 'research_paper' ? 'selected' : '' }}>Research Paper</option>
                        <option value="journal_article" {{ old('document_type', $repository->document_type) == 'journal_article' ? 'selected' : '' }}>Artikel Jurnal</option>
                        <option value="conference_paper" {{ old('document_type', $repository->document_type) == 'conference_paper' ? 'selected' : '' }}>Conference Paper</option>
                        <option value="book_chapter" {{ old('document_type', $repository->document_type) == 'book_chapter' ? 'selected' : '' }}>Bab Buku</option>
                        <option value="technical_report" {{ old('document_type', $repository->document_type) == 'technical_report' ? 'selected' : '' }}>Laporan Teknis</option>
                        <option value="other" {{ old('document_type', $repository->document_type) == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('document_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year -->
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun *</label>
                    <input type="number" id="year" name="year" value="{{ old('year', $repository->year) }}" required min="1900" max="{{ date('Y') + 1 }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition">
                    @error('year')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Language -->
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Bahasa *</label>
                    <select id="language" name="language" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition bg-white">
                        <option value="id" {{ old('language', $repository->language) == 'id' ? 'selected' : '' }}>Indonesia</option>
                        <option value="en" {{ old('language', $repository->language) == 'en' ? 'selected' : '' }}>English</option>
                        <option value="ar" {{ old('language', $repository->language) == 'ar' ? 'selected' : '' }}>Arabic</option>
                        <option value="other" {{ old('language', $repository->language) == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('language')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department/Faculty -->
                <div>
                    <label for="faculty" class="block text-sm font-medium text-gray-700 mb-2">Fakultas</label>
                    <input type="text" id="faculty" name="faculty" value="{{ old('faculty', $repository->faculty) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                        placeholder="Nama fakultas">
                    @error('faculty')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Jurusan/Departemen</label>
                    <input type="text" id="department" name="department" value="{{ old('department', $repository->department) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                        placeholder="Nama jurusan/departemen">
                    @error('department')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="program_study" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                    <input type="text" id="program_study" name="program_study" value="{{ old('program_study', $repository->program_study) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition"
                        placeholder="Nama program studi">
                    @error('program_study')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Access Level -->
                <div>
                    <label for="access_level" class="block text-sm font-medium text-gray-700 mb-2">Tingkat Akses *</label>
                    <select id="access_level" name="access_level" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 transition bg-white">
                        <option value="campus_only" {{ old('access_level', $repository->access_level) == 'campus_only' ? 'selected' : '' }}>Kampus Saja</option>
                        <option value="public" {{ old('access_level', $repository->access_level) == 'public' ? 'selected' : '' }}>Publik</option>
                        <option value="registered" {{ old('access_level', $repository->access_level) == 'registered' ? 'selected' : '' }}>Terdaftar</option>
                        <option value="restricted" {{ old('access_level', $repository->access_level) == 'restricted' ? 'selected' : '' }}>Terbatas</option>
                    </select>
                    @error('access_level')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Downloadable -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_downloadable" id="is_downloadable" value="1" {{ old('is_downloadable', $repository->is_downloadable) ? 'checked' : '' }} class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-600">
                    <label for="is_downloadable" class="ml-3 text-sm text-gray-700">File dapat diunduh</label>
                </div>

                <!-- Current File Info -->
                @if($repository->file_path)
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-sm font-medium text-gray-900 mb-1">File Saat Ini</p>
                    <p class="text-xs text-gray-600">{{ $repository->file_name }}</p>
                    <p class="text-xs text-gray-500">{{ $repository->file_size_human }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- File Upload -->
        <div class="mt-6 lg:mt-8 pt-6 lg:pt-8 border-t border-gray-100">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Ganti File (Opsional)
            </h3>
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-purple-400 transition">
                <input type="file" name="file" id="file" accept=".pdf,.doc,.docx" class="hidden" onchange="previewFile(this)">
                <label for="file" class="cursor-pointer">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm text-gray-600">Klik untuk upload file baru atau drag & drop</p>
                    <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX (Max 10MB)</p>
                    <p class="text-xs text-gray-400 mt-2">Biarkan kosong jika tidak ingin mengganti file</p>
                </label>
                <div id="file-preview" class="hidden mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-700" id="file-name"></p>
                </div>
            </div>
            @error('file')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="mt-6 lg:mt-8 flex items-center justify-end gap-3 pt-4 lg:pt-6 border-t border-gray-100">
            <a href="{{ route('repositories.show', $repository) }}" class="hidden sm:block px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition font-medium">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white text-base font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
function previewFile(input) {
    const preview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');

    if (input.files && input.files[0]) {
        fileName.textContent = input.files[0].name + ' (' + (input.files[0].size / 1024 / 1024).toFixed(2) + ' MB)';
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endsection
