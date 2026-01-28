@extends('layouts.admin')

@section('title', 'Edit Koleksi')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Koleksi</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $collection->title }}</p>
        </div>
        <a href="{{ route('collections.show', $collection) }}" class="text-blue-700 hover:text-blue-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <form method="POST" action="{{ route('collections.update', $collection) }}" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Basic Info -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Judul</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $collection->title) }}" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition"
                            placeholder="Judul lengkap koleksi">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cover Image -->
                    <div class="md:col-span-2">
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1.5">Gambar Sampul</label>
                        @if($collection->cover_image)
                            <div class="mb-3">
                                <img src="{{ Storage::url($collection->cover_image) }}" alt="{{ $collection->title }}" class="h-32 w-auto object-cover rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                            </div>
                        @endif
                        <input type="file" id="cover_image" name="cover_image" accept="image/*"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-800 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
                        @error('cover_image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Authors -->
                    <div class="md:col-span-2">
                        <label for="authors" class="block text-sm font-medium text-gray-700 mb-1.5">Penulis</label>
                        <select id="authors" name="authors[]" multiple required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            @foreach($subjects as $author)
                                <option value="{{ $author->id }}" {{ in_array($author->id, old('authors', $collection->authors ?? [])) ? 'selected' : '' }}>{{ $author->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd untuk memilih multiple</p>
                        @error('authors')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ISBN/ISSN/Year -->
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1.5">ISBN</label>
                        <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $collection->isbn) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                        @error('isbn')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1.5">Tahun</label>
                        <input type="number" id="year" name="year" value="{{ old('year', $collection->year) }}" min="1000" max="{{ date('Y') + 1 }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                        @error('year')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publisher/Edition/Pages -->
                    <div>
                        <label for="publisher_id" class="block text-sm font-medium text-gray-700 mb-1.5">Penerbit</label>
                        <select id="publisher_id" name="publisher_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="">Pilih Penerbit</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}" {{ old('publisher_id', $collection->publisher_id) == $publisher->id ? 'selected' : '' }}>{{ $publisher->name }}</option>
                            @endforeach
                        </select>
                        @error('publisher_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="edition" class="block text-sm font-medium text-gray-700 mb-1.5">Edisi</label>
                        <input type="text" id="edition" name="edition" value="{{ old('edition', $collection->edition) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                        @error('edition')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pages" class="block text-sm font-medium text-gray-700 mb-1.5">Halaman</label>
                        <input type="number" id="pages" name="pages" value="{{ old('pages', $collection->pages) }}" min="1"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                        @error('pages')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-1.5">Bahasa</label>
                        <input type="text" id="language" name="language" value="{{ old('language', $collection->language) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
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
                    <div>
                        <label for="collection_type_id" class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Koleksi</label>
                        <select id="collection_type_id" name="collection_type_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="">Pilih Tipe</option>
                            @foreach($collectionTypes as $type)
                                <option value="{{ $type->id }}" {{ old('collection_type_id', $collection->collection_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('collection_type_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="classification_id" class="block text-sm font-medium text-gray-700 mb-1.5">Klasifikasi DDC</label>
                        <select id="classification_id" name="classification_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="">Pilih Klasifikasi</option>
                            @foreach($classifications as $class)
                                <option value="{{ $class->id }}" {{ old('classification_id', $collection->classification_id) == $class->id ? 'selected' : '' }}>{{ $class->code }} - {{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('classification_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gmd_id" class="block text-sm font-medium text-gray-700 mb-1.5">GMD</label>
                        <select id="gmd_id" name="gmd_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                            <option value="">Pilih GMD</option>
                            @foreach($gmds as $gmd)
                                <option value="{{ $gmd->id }}" {{ old('gmd_id', $collection->gmd_id) == $gmd->id ? 'selected' : '' }}>{{ $gmd->name }}</option>
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
                        <option value="{{ $subject->id }}" {{ in_array($subject->id, old('subjects', $collection->subjects->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $subject->name }}</option>
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
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">{{ old('abstract', $collection->abstract) }}</textarea>
                @error('abstract')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">{{ old('description', $collection->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Items -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Item</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="total_items" class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Copy</label>
                        <input type="number" id="total_items" name="total_items" value="{{ old('total_items', $collection->total_items) }}" required min="{{ $collection->borrowed_items }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                        <p class="mt-1 text-xs text-gray-500">Minimal {{ $collection->borrowed_items }} (sedang dipinjam)</p>
                        @error('total_items')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1.5">Harga</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $collection->price) }}" min="0" step="0.01"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition">
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('collections.show', $collection) }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-800 hover:bg-blue-900 text-white font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition-all duration-200">
                Update Koleksi
            </button>
        </div>
    </form>
</div>
@endsection
