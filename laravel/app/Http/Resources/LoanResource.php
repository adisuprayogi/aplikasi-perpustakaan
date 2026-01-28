<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
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
            'loan_branch_id' => $this->loan_branch_id,
            'loan_branch' => $this->whenLoaded('loanBranch', fn() => [
                'id' => $this->loanBranch->id,
                'name' => $this->loanBranch->name,
            ]),
            'return_branch_id' => $this->return_branch_id,
            'return_branch' => $this->whenLoaded('returnBranch', fn() => [
                'id' => $this->returnBranch->id,
                'name' => $this->returnBranch->name,
            ]),
            'loan_date' => $this->loan_date->format('d/m/Y'),
            'due_date' => $this->due_date->format('d/m/Y'),
            'return_date' => $this->return_date?->format('d/m/Y'),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel($this->status),
            'renewal_count' => $this->renewal_count ?? 0,
            'max_renewals' => $this->max_renewals ?? 3,
            'can_renew' => $this->canRenew(),
            'is_overdue' => $this->isOverdue(),
            'days_overdue' => $this->when($this->isOverdue(), $this->days_overdue),
            'calculated_fine' => $this->when(isset($this->calculated_fine), $this->calculated_fine),
            'paid_fine' => $this->when(isset($this->paid_fine), $this->paid_fine),
            'remaining_fine' => $this->when(isset($this->remaining_fine), $this->remaining_fine),
            'notes' => $this->notes,
            'return_condition' => $this->return_condition,
            'return_condition_label' => $this->when($this->return_condition, fn() => $this->getReturnConditionLabel($this->return_condition)),
            'return_notes' => $this->return_notes,
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
            'active' => 'Aktif',
            'returned' => 'Dikembalikan',
            'overdue' => 'Terlambat',
            default => ucfirst($status),
        };
    }

    /**
     * Get return condition label.
     */
    protected function getReturnConditionLabel(string $condition): string
    {
        return match($condition) {
            'good' => 'Baik',
            'damaged' => 'Rusak',
            'lost' => 'Hilang',
            default => ucfirst($condition),
        };
    }
}
