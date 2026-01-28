<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'member_id' => $this->member_id,
            'member' => $this->whenLoaded('member', fn() => [
                'id' => $this->member->id,
                'member_no' => $this->member->member_no,
                'name' => $this->member->name,
                'type' => $this->member->type,
            ]),
            'item_id' => $this->item_id,
            'item' => $this->whenLoaded('item', fn() => [
                'id' => $this->item->id,
                'barcode' => $this->item->barcode,
                'collection' => [
                    'id' => $this->item->collection->id,
                    'title' => $this->item->collection->title,
                    'cover_image' => $this->item->collection->cover_image ? asset('storage/' . $this->item->collection->cover_image) : null,
                ],
            ]),
            'reservation_date' => $this->reservation_date->format('d/m/Y'),
            'expiry_date' => $this->expiry_date->format('d/m/Y'),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel($this->status),
            'is_active' => in_array($this->status, ['pending', 'ready']),
            'is_expired' => $this->isExpired(),
            'days_until_expiry' => $this->when(!$this->isExpired(), function () {
                return $this->expiry_date->diffInDays(now());
            }),
            'ready_at' => $this->ready_at?->format('d/m/Y H:i'),
            'fulfilled_at' => $this->fulfilled_at?->format('d/m/Y H:i'),
            'cancelled_at' => $this->cancelled_at?->format('d/m/Y H:i'),
            'priority' => $this->priority ?? 50,
            'queue_position' => $this->when($this->status === 'pending', fn() => $this->queue_position ?? null),
            'notes' => $this->notes,
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
            'pending' => 'Pending',
            'ready' => 'Siap Diambil',
            'fulfilled' => 'Dipenuhi',
            'cancelled' => 'Dibatalkan',
            'expired' => 'Kadaluarsa',
            default => ucfirst($status),
        };
    }
}
