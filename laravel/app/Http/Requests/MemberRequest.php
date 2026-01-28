<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => 'required|in:student,lecturer,staff,external',
            'branch_id' => 'required|exists:branches,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:members,email',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048', // 2MB max
            'status' => 'nullable|in:active,suspended,expired,blacklisted',
            'valid_until' => 'nullable|date|after:today',
            'metadata' => 'nullable|array',
        ];

        // For updates, exclude current member from unique check
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['email'] = 'nullable|email|max:255|unique:members,email,' . $this->route('member');
            $rules['photo'] = 'nullable|image|max:2048'; // Photo is optional on update
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama anggota wajib diisi.',
            'type.required' => 'Tipe anggota wajib dipilih.',
            'type.in' => 'Tipe anggota tidak valid.',
            'branch_id.required' => 'Branch wajib dipilih.',
            'branch_id.exists' => 'Branch tidak ditemukan.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
            'status.in' => 'Status tidak valid.',
            'valid_until.date' => 'Format tanggal tidak valid.',
            'valid_until.after' => 'Tanggal berlaku harus setelah hari ini.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'type' => 'Tipe',
            'branch_id' => 'Branch',
            'phone' => 'Telepon',
            'email' => 'Email',
            'address' => 'Alamat',
            'photo' => 'Foto',
            'status' => 'Status',
            'valid_until' => 'Berlaku Sampai',
            'metadata' => 'Metadata',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default status if not provided
        if ($this->isMethod('POST') && !$this->has('status')) {
            $this->merge([
                'status' => 'active',
            ]);
        }
    }
}
