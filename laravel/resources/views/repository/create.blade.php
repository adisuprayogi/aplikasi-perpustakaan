@extends('layouts.public')

@section('title', 'Submit Karya Ilmiah')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Submit Karya Ilmiah</h1>
                <p class="text-gray-500 mt-1">Bagikan karya ilmiah Anda ke repository institusi</p>
            </div>
        </div>

        <a href="{{ route('repository.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Repository
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
        <form action="{{ route('repository.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Author Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Penulis
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penulis <span class="text-red-500">*</span></label>
                        <input type="text" name="author_name" value="{{ old('author_name', auth()->user()->name) }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                        <input type="text" name="author_nim" value="{{ old('author_nim') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Penulis</label>
                        <input type="email" name="author_email" value="{{ old('author_email', auth()->user()->email) }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                    </div>
                </div>
            </div>

            <!-- Advisor Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Informasi Pembimbing
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pembimbing</label>
                        <input type="text" name="advisor_name" value="{{ old('advisor_name') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Co-Pembimbing</label>
                        <input type="text" name="co_advisor_name" value="{{ old('co_advisor_name') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                    </div>
                </div>
            </div>

            <!-- Document Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Informasi Dokumen
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required maxlength="500"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Abstrak</label>
                        <textarea name="abstract" rows="4"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">{{ old('abstract') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Dokumen <span class="text-red-500">*</span></label>
                            <select name="document_type" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 bg-white">
                                <option value="">Pilih Tipe</option>
                                <option value="undergraduate_thesis" {{ old('document_type') === 'undergraduate_thesis' ? 'selected' : '' }}>Skripsi</option>
                                <option value="masters_thesis" {{ old('document_type') === 'masters_thesis' ? 'selected' : '' }}>Tesis</option>
                                <option value="doctoral_dissertation" {{ old('document_type') === 'doctoral_dissertation' ? 'selected' : '' }}>Disertasi</option>
                                <option value="research_paper" {{ old('document_type') === 'research_paper' ? 'selected' : '' }}>Research Paper</option>
                                <option value="journal_article" {{ old('document_type') === 'journal_article' ? 'selected' : '' }}>Artikel Jurnal</option>
                                <option value="conference_paper" {{ old('document_type') === 'conference_paper' ? 'selected' : '' }}>Conference Paper</option>
                                <option value="book_chapter" {{ old('document_type') === 'book_chapter' ? 'selected' : '' }}>Bab Buku</option>
                                <option value="technical_report" {{ old('document_type') === 'technical_report' ? 'selected' : '' }}>Laporan Teknis</option>
                                <option value="other" {{ old('document_type') === 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun <span class="text-red-500">*</span></label>
                            <input type="number" name="year" value="{{ old('year', date('Y')) }}" required min="1900" max="{{ date('Y') + 1 }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bahasa <span class="text-red-500">*</span></label>
                            <select name="language" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 bg-white">
                                <option value="id" {{ old('language') === 'id' ? 'selected' : '' }}>Indonesia</option>
                                <option value="en" {{ old('language') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ old('language') === 'ar' ? 'selected' : '' }}>Arabic</option>
                                <option value="other" {{ old('language') === 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                            <input type="text" name="faculty" value="{{ old('faculty') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                            <input type="text" name="department" value="{{ old('department') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                            <input type="text" name="program_study" value="{{ old('program_study') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kata Kunci</label>
                        <input type="text" name="keywords" value="{{ old('keywords') }}" placeholder="Pisahkan dengan koma"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600">
                        <p class="text-xs text-gray-500 mt-1">Contoh: pembelajaran mesin, kecerdasan buatan, data mining</p>
                    </div>
                </div>
            </div>

            <!-- Access Settings -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Pengaturan Akses
                </h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Level Akses <span class="text-red-500">*</span></label>
                    <select name="access_level" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600/20 focus:border-purple-600 bg-white">
                        <option value="public" {{ old('access_level') === 'public' ? 'selected' : '' }}>Publik - Dapat diakses semua orang</option>
                        <option value="registered" {{ old('access_level') === 'registered' ? 'selected' : '' }}>Terdaftar - Hanya pengguna terdaftar</option>
                        <option value="campus_only" {{ old('access_level') === 'campus_only' ? 'selected' : '' }}>Kampus - Hanya civitas kampus</option>
                        <option value="restricted" {{ old('access_level') === 'restricted' ? 'selected' : '' }}>Terbatas - Tidak dapat diunduh</option>
                    </select>
                </div>
            </div>

            <!-- File Upload -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Upload File
                </h3>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-purple-400 transition">
                    <input type="file" name="file" id="file-upload" accept=".pdf,.doc,.docx" required
                        class="hidden" onchange="previewFile(this)">
                    <label for="file-upload" class="cursor-pointer">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-700">Klik untuk upload atau drag & drop</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX (Max 10MB)</p>
                    </label>
                    <div id="file-preview" class="hidden mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span id="file-name" class="text-sm text-gray-700"></span>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('repository.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Submit Karya
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewFile(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('file-preview').classList.remove('hidden');
        document.getElementById('file-name').textContent = file.name + ' (' + formatFileSize(file.size) + ')';
    }
}

function clearFile() {
    document.getElementById('file-upload').value = '';
    document.getElementById('file-preview').classList.add('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>
@endsection
