<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,

            'defaultEventDurationMinutes' => $this->defaultEventDurationMinutes,
            'defaultMeetingDurationMinutes' => $this->defaultMeetingDurationMinutes,

            'preferredStartTime' => $this->preferredStartTime,
            'preferredEndTime' => $this->preferredEndTime,

            'bufferBetweenEventsMinutes' => $this->bufferBetweenEventsMinutes,

            'requireConfirmationBeforeCreate' => $this->requireConfirmationBeforeCreate,
            'autoCreateMeetingLink' => $this->autoCreateMeetingLink,
            'autoCreateReminder' => $this->autoCreateReminder,

            'createdAt' => $this->createdAt?->toISOString(),
            'updatedAt' => $this->updatedAt?->toISOString(),
        ];
    }
}