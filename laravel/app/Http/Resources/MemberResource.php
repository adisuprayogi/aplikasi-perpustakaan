<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'member_no' => $this->member_no,
            'name' => $this->name,
            'type' => $this->type,
            'type_label' => $this->getTypeLabel($this->type),
            'branch_id' => $this->branch_id,
            'branch' => $this->whenLoaded('branch', fn() => [
                'id' => $this->branch->id,
                'name' => $this->branch->name,
            ]),
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'photo' => $this->photo ? asset('storage/' . $this->photo) : null,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel($this->status),
            'is_active' => $this->status === 'active',
            'is_suspended' => $this->status === 'suspended',
            'valid_until' => $this->valid_until?->format('d/m/Y'),
            'is_expired' => $this->valid_until?->isPast() ?? false,
            'active_loans' => $this->when(isset($this->active_loans), $this->active_loans),
            'total_fines' => $this->when(isset($this->total_fines), $this->total_fines),
            'paid_fines' => $this->when(isset($this->paid_fines), $this->paid_fines),
            'remaining_fines' => $this->when(isset($this->remaining_fines), $this->remaining_fines),
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }

    /**
     * Get member type label.
     */
    protected function getTypeLabel(string $type): string
    {
        return match($type) {
            'student' => 'Mahasiswa',
            'lecturer' => 'Dosen',
            'staff' => 'Staf',
            'external' => 'Eksternal',
            default => ucfirst($type),
        };
    }

    /**
     * Get status label.
     */
    protected function getStatusLabel(string $status): string
    {
        return match($status) {
            'active' => 'Aktif',
            'suspended' => 'Disuspend',
            'expired' => 'Kadaluarsa',
            'blacklisted' => 'Blacklist',
            default => ucfirst($status),
        };
    }
}
