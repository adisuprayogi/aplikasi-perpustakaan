<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
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
            'title' => $this->title,
            'collection_type_id' => $this->collection_type_id,
            'collection_type' => $this->whenLoaded('collectionType', fn() => [
                'id' => $this->collectionType->id,
                'name' => $this->collectionType->name,
            ]),
            'gmd_id' => $this->gmd_id,
            'gmd' => $this->whenLoaded('gmd', fn() => [
                'id' => $this->gmd->id,
                'name' => $this->gmd->name,
            ]),
            'publisher_id' => $this->publisher_id,
            'publisher' => $this->whenLoaded('publisher', fn() => [
                'id' => $this->publisher?->id,
                'name' => $this->publisher?->name,
            ]),
            'publish_year' => $this->publish_year,
            'publish_location' => $this->publish_location,
            'isbn' => $this->isbn,
            'issn' => $this->issn,
            'language' => $this->language,
            'abstract' => $this->abstract,
            'notes' => $this->notes,
            'call_number' => $this->call_number,
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'edition' => $this->edition,
            'collation' => $this->collation,
            'series_title' => $this->series_title,
            'frequency' => $this->frequency,
            'authors' => $this->when(isset($this->authors), $this->authors),
            'author_ids' => $this->author_ids,
            'subjects' => $this->whenLoaded('subjects', fn() => collect($this->subjects)->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
            ])),
            'total_items' => $this->total_items ?? 0,
            'available_items' => $this->available_items ?? 0,
            'borrowed_items' => $this->borrowed_items ?? 0,
            'is_available' => ($this->available_items ?? 0) > 0,
            'items' => $this->whenLoaded('items', fn() => CollectionItemResource::collection($this->items)),
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
