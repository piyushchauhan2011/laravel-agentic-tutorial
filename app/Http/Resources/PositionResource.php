<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
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
            'company' => [
                'id' => $this->company?->id,
                'name' => $this->company?->name,
            ],
            'title' => $this->title,
            'department' => $this->department,
            'employment_type' => $this->employment_type,
            'location' => $this->location,
            'description' => $this->description,
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'closing_at' => $this->closing_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
