@extends('layouts.admin')

@section('title', 'Tambah Koleksi')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Koleksi Baru</h1>
            <p class="mt-1 text-sm text-gray-500">Input data bibliografis koleksi</p>
        </div>
        <a href="{{ route('collections.index') }}" class="text-blue-700 hover:text-blue-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <form method="POST" action="{{ route('collections.store') }}" enctype="multipart/form-data" class="p-6">
        @csrf

        <div class="space-y-6">
            <!-- Basic Info -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Judul</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="Judul lengkap koleksi">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cover Image -->
                    <div class="md:col-span-2">
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1.5">Gambar Sampul</label>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-800 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP. Maksimal 2MB</p>
                        @error('cover_image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Authors -->
                    <div class="md:col-span-2">
                        <label for="authors" class="block text-sm font-medium text-gray-700 mb-1.5">Penulis</label>
                        <select id="authors" name="authors[]" multiple required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd untuk memilih multiple</p>
                        @error('authors')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1.5">ISBN</label>
                        <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="978-...">
                        @error('isbn')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ISSN -->
                    <div>
                        <label for="issn" class="block text-sm font-medium text-gray-700 mb-1.5">ISSN</label>
                        <input type="text" id="issn" name="issn" value="{{ old('issn') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="...">
                        @error('issn')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publisher -->
                    <div>
                        <label for="publisher_id" class="block text-sm font-medium text-gray-700 mb-1.5">Penerbit</label>
                        <select id="publisher_id" name="publisher_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="">Pilih Penerbit</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>{{ $publisher->name }}</option>
                            @endforeach
                        </select>
                        @error('publisher_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1.5">Tahun Terbit</label>
                        <input type="number" id="year" name="year" value="{{ old('year') }}" min="1000" max="{{ date('Y') + 1 }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="2024">
                        @error('year')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Edition -->
                    <div>
                        <label for="edition" class="block text-sm font-medium text-gray-700 mb-1.5">Edisi</label>
                        <input type="text" id="edition" name="edition" value="{{ old('edition') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="Edisi 1">
                        @error('edition')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pages -->
                    <div>
                        <label for="pages" class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Halaman</label>
                        <input type="number" id="pages" name="pages" value="{{ old('pages') }}" min="1"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="250">
                        @error('pages')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Language -->
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-1.5">Bahasa</label>
                        <input type="text" id="language" name="language" value="{{ old('language', 'Indonesia') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="Indonesia">
                        @error('language')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Classification -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Klasifikasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Collection Type -->
                    <div>
                        <label for="collection_type_id" class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Koleksi</label>
                        <select id="collection_type_id" name="collection_type_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="">Pilih Tipe</option>
                            @foreach($collectionTypes as $type)
                                <option value="{{ $type->id }}" {{ old('collection_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }} ({{ $type->code }})</option>
                            @endforeach
                        </select>
                        @error('collection_type_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Classification -->
                    <div>
                        <label for="classification_id" class="block text-sm font-medium text-gray-700 mb-1.5">Klasifikasi DDC</label>
                        <select id="classification_id" name="classification_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="">Pilih Klasifikasi</option>
                            @foreach($classifications as $class)
                                <option value="{{ $class->id }}" {{ old('classification_id') == $class->id ? 'selected' : '' }}>{{ $class->code }} - {{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('classification_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- GMD -->
                    <div>
                        <label for="gmd_id" class="block text-sm font-medium text-gray-700 mb-1.5">GMD</label>
                        <select id="gmd_id" name="gmd_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="">Pilih GMD</option>
                            @foreach($gmds as $gmd)
                                <option value="{{ $gmd->id }}" {{ old('gmd_id') == $gmd->id ? 'selected' : '' }}>{{ $gmd->name }}</option>
                            @endforeach
                        </select>
                        @error('gmd_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Subjects -->
            <div>
                <label for="subjects" class="block text-sm font-medium text-gray-700 mb-1.5">Subjek</label>
                <select id="subjects" name="subjects[]" multiple
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd untuk memilih multiple</p>
                @error('subjects')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="abstract" class="block text-sm font-medium text-gray-700 mb-1.5">Abstrak</label>
                <textarea id="abstract" name="abstract" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="Ringkasan isi koleksi">{{ old('abstract') }}</textarea>
                @error('abstract')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                    placeholder="Deskripsi lengkap">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Items Info -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Item / Copy</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Total Items -->
                    <div>
                        <label for="total_items" class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Copy</label>
                        <input type="number" id="total_items" name="total_items" value="{{ old('total_items', 1) }}" required min="0"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="1">
                        @error('total_items')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1.5">Harga</label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="0">
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('collections.index') }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-800 hover:bg-blue-900 text-white font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition-all duration-200">
                Simpan Koleksi
            </button>
        </div>
    </form>
</div>
@endsection
