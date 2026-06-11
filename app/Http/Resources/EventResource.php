<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'calendarId' => $this->calendarId,
            'smartRequestId' => $this->smartRequestId,

            'title' => $this->title,
            'description' => $this->description,

            'startAt' => $this->startAt?->toISOString(),
            'endAt' => $this->endAt?->toISOString(),
            'timezone' => $this->timezone,

            'location' => $this->location,
            'meetingURL' => $this->meetingURL,

            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'source' => $this->source->value,

            'isAllDay' => $this->isAllDay,
            'isRecurring' => $this->isRecurring,
            'createByAI' => $this->createByAI,

            'calendar' => new CalendarResource(
                $this->whenLoaded('calendar')
            ),

            'participantsCount' => $this->whenCounted('participants'),
            'remindersCount' => $this->whenCounted('reminders'),

            'participants' => $this->whenLoaded(
                'participants',
                fn () => $this->participants->map(fn ($participant) => [
                    'id' => $participant->id,
                    'contactId' => $participant->contactId,
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'role' => $participant->role->value,
                    'responseStatus' => $participant->responseStatus->value,
                ])
            ),

            'reminders' => $this->whenLoaded(
                'reminders',
                fn () => $this->reminders->map(fn ($reminder) => [
                    'id' => $reminder->id,
                    'type' => $reminder->type->value,
                    'minutesBefore' => $reminder->minutesBefore,
                    'isSent' => $reminder->isSent,
                    'sentAt' => $reminder->sentAt?->toISOString(),
                ])
            ),

            'createdAt' => $this->createdAt?->toISOString(),
            'updatedAt' => $this->updatedAt?->toISOString(),
        ];
    }
}
