<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionItemResource extends JsonResource
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
            'collection_id' => $this->collection_id,
            'barcode' => $this->barcode,
            'call_number' => $this->call_number,
            'branch_id' => $this->branch_id,
            'branch' => $this->whenLoaded('branch', fn() => [
                'id' => $this->branch->id,
                'name' => $this->branch->name,
                'code' => $this->branch->code,
            ]),
            'location' => $this->location,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel($this->status),
            'condition' => $this->condition,
            'condition_label' => $this->getConditionLabel($this->condition ?? 'good'),
            'acquired_date' => $this->acquired_date?->format('d/m/Y'),
            'acquired_price' => $this->acquired_price,
            'source' => $this->source,
            'is_available' => $this->status === 'available',
            'is_borrowed' => $this->status === 'borrowed',
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }

    /**
     * Get status label.
     */
    protected function getStatusLabel(string $status): string
    {
        return match($status) {
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'reserved' => 'Direservasi',
            'lost' => 'Hilang',
            'damaged' => 'Rusak',
            default => ucfirst($status),
        };
    }

    /**
     * Get condition label.
     */
    protected function getConditionLabel(string $condition): string
    {
        return match($condition) {
            'good' => 'Baik',
            'fair' => 'Cukup',
            'poor' => 'Buruk',
            default => ucfirst($condition),
        };
    }
}
