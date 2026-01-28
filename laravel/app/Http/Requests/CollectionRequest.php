<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:500',
            'collection_type_id' => 'required|exists:collection_types,id',
            'gmd_id' => 'required|exists:gmds,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'publish_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'publish_location' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:collections,isbn',
            'issn' => 'nullable|string|max:20|unique:collections,issn',
            'language' => 'nullable|string|max:50',
            'abstract' => 'nullable|string',
            'notes' => 'nullable|string',
            'call_number' => 'nullable|string|max:100',
            'cover_image' => 'nullable|image|max:5120', // 5MB max
            'edition' => 'nullable|string|max:100',
            'collation' => 'nullable|string|max:100', // Jilid, halaman, dll
            'series_title' => 'nullable|string|max:255',
            'frequency' => 'nullable|string|max:50', // Untuk jurnal
            'author_ids' => 'nullable|array',
            'author_ids.*' => 'exists:authors,id',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'total_items' => 'nullable|integer|min:0',
            'available_items' => 'nullable|integer|min:0',
            'borrowed_items' => 'nullable|integer|min:0',
        ];

        // For updates, exclude current collection from unique check
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['isbn'] = 'nullable|string|max:20|unique:collections,isbn,' . $this->route('collection');
            $rules['issn'] = 'nullable|string|max:20|unique:collections,issn,' . $this->route('collection');
            $rules['cover_image'] = 'nullable|image|max:5120'; // Cover is optional on update
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul koleksi wajib diisi.',
            'collection_type_id.required' => 'Tipe koleksi wajib dipilih.',
            'collection_type_id.exists' => 'Tipe koleksi tidak ditemukan.',
            'gmd_id.required' => 'GMD wajib dipilih.',
            'gmd_id.exists' => 'GMD tidak ditemukan.',
            'publisher_id.exists' => 'Penerbit tidak ditemukan.',
            'publish_year.integer' => 'Tahun terbit harus berupa angka.',
            'publish_year.min' => 'Tahun terbit tidak valid.',
            'publish_year.max' => 'Tahun terbit tidak boleh lebih dari ' . (date('Y') + 1),
            'isbn.unique' => 'ISBN sudah digunakan.',
            'issn.unique' => 'ISSN sudah digunakan.',
            'cover_image.image' => 'Cover harus berupa gambar.',
            'cover_image.max' => 'Ukuran cover maksimal 5MB.',
            'author_ids.array' => 'Penulis harus berupa array.',
            'author_ids.*.exists' => 'Penulis tidak ditemukan.',
            'subject_ids.array' => 'Subjek harus berupa array.',
            'subject_ids.*.exists' => 'Subjek tidak ditemukan.',
            'total_items.integer' => 'Total item harus berupa angka.',
            'available_items.integer' => 'Item tersedia harus berupa angka.',
            'borrowed_items.integer' => 'Item dipinjam harus berupa angka.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Judul',
            'collection_type_id' => 'Tipe Koleksi',
            'gmd_id' => 'GMD',
            'publisher_id' => 'Penerbit',
            'publish_year' => 'Tahun Terbit',
            'publish_location' => 'Tempat Terbit',
            'isbn' => 'ISBN',
            'issn' => 'ISSN',
            'language' => 'Bahasa',
            'abstract' => 'Abstrak',
            'notes' => 'Catatan',
            'call_number' => 'Nomor Panggil',
            'cover_image' => 'Cover',
            'edition' => 'Edisi',
            'collation' => 'Kolasi',
            'series_title' => 'Judul Seri',
            'frequency' => 'Frekuensi',
            'author_ids' => 'Penulis',
            'subject_ids' => 'Subjek',
            'total_items' => 'Total Item',
            'available_items' => 'Item Tersedia',
            'borrowed_items' => 'Item Dipinjam',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values if not provided
        if ($this->isMethod('POST')) {
            $this->merge([
                'total_items' => $this->input('total_items', 0),
                'available_items' => $this->input('available_items', 0),
                'borrowed_items' => $this->input('borrowed_items', 0),
            ]);
        }
    }
}
