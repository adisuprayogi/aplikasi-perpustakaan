<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'type' => $this->type,
            'type_label' => $this->getTypeLabel($this->type),
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'logo' => $this->logo ? asset('storage/' . $this->logo) : null,
            'is_active' => $this->is_active,
            'status_label' => $this->is_active ? 'Aktif' : 'Tidak Aktif',
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }

    /**
     * Get branch type label.
     */
    protected function getTypeLabel(string $type): string
    {
        return match($type) {
            'central' => 'Pusat',
            'faculty' => 'Fakultas',
            'study_program' => 'Program Studi',
            default => ucfirst($type),
        };
    }
}
