<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'isDefault' => $this->isDefault,
            'isActive' => $this->isActive,

            'eventsCount' => $this->whenCounted('events'),

            'createdAt' => $this->createdAt?->toISOString(),
            'updatedAt' => $this->updatedAt?->toISOString(),
        ];
    }
}