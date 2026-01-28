<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'code' => 'required|string|max:20|unique:branches,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:central,faculty,study_program',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|max:2048', // 2MB max
            'is_active' => 'boolean',
        ];

        // For updates, exclude current branch from unique check
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['code'] = 'required|string|max:20|unique:branches,code,' . $this->route('branch');
            $rules['logo'] = 'nullable|image|max:2048'; // Logo is optional on update
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Kode branch wajib diisi.',
            'code.unique' => 'Kode branch sudah digunakan.',
            'name.required' => 'Nama branch wajib diisi.',
            'type.required' => 'Tipe branch wajib dipilih.',
            'type.in' => 'Tipe branch tidak valid.',
            'email.email' => 'Format email tidak valid.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'code' => 'Kode',
            'name' => 'Nama',
            'type' => 'Tipe',
            'address' => 'Alamat',
            'phone' => 'Telepon',
            'email' => 'Email',
            'logo' => 'Logo',
            'is_active' => 'Status Aktif',
        ];
    }
}
