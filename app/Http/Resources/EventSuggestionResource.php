<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventSuggestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'smartRequestId' => $this->smartRequestId,

            'suggestedStartAt' => $this->suggestedStartAt?->toISOString(),
            'suggestedEndAt' => $this->suggestedEndAt?->toISOString(),

            'score' => (float) $this->score,
            'reason' => $this->reason,
            'selected' => $this->selected,

            'createdAt' => $this->createdAt?->toISOString(),
        ];
    }
}