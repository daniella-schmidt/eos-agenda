<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventParticipantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'eventId' => $this->eventId,
            'contactId' => $this->contactId,

            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'responseStatus' => $this->responseStatus->value,

            'contact' => new ContactResource(
                $this->whenLoaded('contact')
            ),

            'createAt' => $this->createAt?->toISOString(),
        ];
    }
}