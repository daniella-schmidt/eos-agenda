<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventReminderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'eventId' => $this->eventId,
            'type' => $this->type->value,
            'minutesBefore' => $this->minutesBefore,
            'isSent' => $this->isSent,
            'sentAt' => $this->sentAt?->toISOString(),
            'createAt' => $this->createAt?->toISOString(),
        ];
    }
}