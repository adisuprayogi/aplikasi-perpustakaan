<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'reservation_date' => 'required|date',
            'expiry_date' => 'required|date|after:reservation_date',
            'status' => 'nullable|in:pending,ready,fulfilled,cancelled,expired',
            'notes' => 'nullable|string|max:500',
            'priority' => 'nullable|integer|min:1|max:100',
        ];

        // For create, set default values if not provided
        if ($this->isMethod('POST')) {
            $this->merge([
                'reservation_date' => $this->input('reservation_date', now()->toDateString()),
                'status' => $this->input('status', 'pending'),
                'priority' => $this->input('priority', 50),
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
            'reservation_date.required' => 'Tanggal reservasi wajib diisi.',
            'reservation_date.date' => 'Format tanggal reservasi tidak valid.',
            'expiry_date.required' => 'Tanggal kadaluarsa wajib diisi.',
            'expiry_date.date' => 'Format tanggal kadaluarsa tidak valid.',
            'expiry_date.after' => 'Tanggal kadaluarsa harus setelah tanggal reservasi.',
            'status.in' => 'Status tidak valid.',
            'priority.integer' => 'Prioritas harus berupa angka.',
            'priority.min' => 'Prioritas minimal 1.',
            'priority.max' => 'Prioritas maksimal 100.',
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
            'reservation_date' => 'Tanggal Reservasi',
            'expiry_date' => 'Tanggal Kadaluarsa',
            'status' => 'Status',
            'notes' => 'Catatan',
            'priority' => 'Prioritas',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate item is borrowed (can only reserve borrowed items)
            if ($this->isMethod('POST') && $this->has('item_id')) {
                $item = \App\Models\CollectionItem::find($this->item_id);
                if ($item && $item->status === 'available') {
                    $validator->errors()->add('item_id', 'Item tersedia, silakan pinjam langsung tanpa reservasi.');
                }
            }

            // Validate member is active
            if ($this->isMethod('POST') && $this->has('member_id')) {
                $member = \App\Models\Member::find($this->member_id);
                if ($member && $member->status !== 'active') {
                    $validator->errors()->add('member_id', 'Anggota tidak aktif.');
                }
            }

            // Check if member already has reservation for this item
            if ($this->isMethod('POST') && $this->has('member_id') && $this->has('item_id')) {
                $existingReservation = \App\Models\Reservation::where('member_id', $this->member_id)
                    ->where('item_id', $this->item_id)
                    ->whereIn('status', ['pending', 'ready'])
                    ->first();

                if ($existingReservation) {
                    $validator->errors()->add('item_id', 'Anda sudah memiliki reservasi untuk item ini.');
                }
            }
        });
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Calculate expiry date if not provided (default 7 days)
        if ($this->isMethod('POST') && !$this->has('expiry_date')) {
            $reservationDate = $this->input('reservation_date', now());
            $this->merge([
                'expiry_date' => now()->parse($reservationDate)->addDays(7)->toDateString(),
            ]);
        }
    }
}
