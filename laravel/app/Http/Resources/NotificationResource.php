<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->data;

        return [
            'id' => $this->id,
            'type' => $data['type'] ?? 'notification',
            'title' => $data['title'] ?? 'Notification',
            'message' => $data['message'] ?? '',
            'icon' => $data['icon'] ?? 'bell',
            'color' => $data['color'] ?? 'info',
            'read_at' => $this->read_at?->format('d/m/Y H:i'),
            'is_read' => $this->read_at !== null,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'data' => [
                'loan_id' => $data['loan_id'] ?? null,
                'reservation_id' => $data['reservation_id'] ?? null,
                'collection_title' => $data['collection_title'] ?? null,
                'collection_id' => $data['collection_id'] ?? null,
                'branch_name' => $data['branch_name'] ?? null,
                'due_date' => $data['due_date'] ?? null,
                'expiry_date' => $data['expiry_date'] ?? null,
                'days_until_due' => $data['days_until_due'] ?? null,
                'days_remaining' => $data['days_remaining'] ?? null,
                'is_overdue' => $data['is_overdue'] ?? false,
                'calculated_fine' => $data['calculated_fine'] ?? null,
            ],
        ];
    }
}
