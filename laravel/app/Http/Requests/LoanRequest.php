<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
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
            'member_id' => 'required|exists:members,id',
            'item_id' => 'required|exists:collection_items,id',
            'loan_branch_id' => 'required|exists:branches,id',
            'return_branch_id' => 'nullable|exists:branches,id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after:loan_date',
            'return_date' => 'nullable|date|after:loan_date',
            'status' => 'nullable|in:active,returned,overdue',
            'renewal_count' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:500',
            'return_condition' => 'nullable|in:good,damaged,lost',
            'return_notes' => 'nullable|string|max:500',
        ];

        // For create, set default return branch to loan branch if not provided
        if ($this->isMethod('POST')) {
            $this->merge([
                'return_branch_id' => $this->input('return_branch_id', $this->input('loan_branch_id')),
            ]);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'member_id.required' => 'Anggota wajib dipilih.',
            'member_id.exists' => 'Anggota tidak ditemukan.',
            'item_id.required' => 'Item wajib dipilih.',
            'item_id.exists' => 'Item tidak ditemukan.',
            'loan_branch_id.required' => 'Branch peminjaman wajib dipilih.',
            'loan_branch_id.exists' => 'Branch peminjaman tidak ditemukan.',
            'return_branch_id.exists' => 'Branch pengembalian tidak ditemukan.',
            'loan_date.required' => 'Tanggal pinjam wajib diisi.',
            'loan_date.date' => 'Format tanggal pinjam tidak valid.',
            'due_date.required' => 'Tanggal jatuh tempo wajib diisi.',
            'due_date.date' => 'Format tanggal jatuh tempo tidak valid.',
            'due_date.after' => 'Tanggal jatuh tempo harus setelah tanggal pinjam.',
            'return_date.date' => 'Format tanggal pengembalian tidak valid.',
            'return_date.after' => 'Tanggal pengembalian harus setelah tanggal pinjam.',
            'status.in' => 'Status tidak valid.',
            'renewal_count.integer' => 'Jumlah perpanjangan harus berupa angka.',
            'renewal_count.min' => 'Jumlah perpanjangan tidak boleh negatif.',
            'return_condition.in' => 'Kondisi pengembalian tidak valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'member_id' => 'Anggota',
            'item_id' => 'Item',
            'loan_branch_id' => 'Branch Peminjaman',
            'return_branch_id' => 'Branch Pengembalian',
            'loan_date' => 'Tanggal Pinjam',
            'due_date' => 'Tanggal Jatuh Tempo',
            'return_date' => 'Tanggal Pengembalian',
            'status' => 'Status',
            'renewal_count' => 'Jumlah Perpanjangan',
            'notes' => 'Catatan',
            'return_condition' => 'Kondisi Pengembalian',
            'return_notes' => 'Catatan Pengembalian',
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
                'loan_date' => $this->input('loan_date', now()->toDateString()),
                'status' => $this->input('status', 'active'),
                'renewal_count' => $this->input('renewal_count', 0),
                'return_branch_id' => $this->input('return_branch_id', $this->input('loan_branch_id')),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate item is available for new loans
            if ($this->isMethod('POST') && $this->has('item_id')) {
                $item = \App\Models\CollectionItem::find($this->item_id);
                if ($item && $item->status !== 'available') {
                    $validator->errors()->add('item_id', 'Item tidak tersedia untuk dipinjam.');
                }
            }

            // Validate member is active for new loans
            if ($this->isMethod('POST') && $this->has('member_id')) {
                $member = \App\Models\Member::find($this->member_id);
                if ($member && $member->status !== 'active') {
                    $validator->errors()->add('member_id', 'Anggota tidak aktif.');
                }

                // Check if member is suspended
                if ($member && $member->is_suspended ?? false) {
                    $validator->errors()->add('member_id', 'Anggota sedang disuspend.');
                }
            }
        });
    }
}
