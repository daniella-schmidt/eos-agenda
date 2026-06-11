<?php

namespace App\Services\EventReminder;

use App\DTO\EventReminder\CreateEventReminderDTO;
use App\Models\Event;
use App\Models\EventReminder;
use Illuminate\Validation\ValidationException;

class CreateEventReminderService
{
    public function handle(Event $event, CreateEventReminderDTO $dto): EventReminder
    {
        if ($event->id !== $dto->eventId) {
            throw ValidationException::withMessages([
                'eventId' => [
                    'O evento informado é inválido.',
                ],
            ]);
        }

        $alreadyExists = EventReminder::query()
            ->where('eventId', $dto->eventId)
            ->where('type', $dto->type->value)
            ->where('minutesBefore', $dto->minutesBefore)
            ->exists();

        if ($alreadyExists) {
            throw ValidationException::withMessages([
                'reminder' => [
                    'Já existe um lembrete igual para este evento.',
                ],
            ]);
        }

        return EventReminder::create([
            'eventId' => $dto->eventId,
            'type' => $dto->type,
            'minutesBefore' => $dto->minutesBefore,
            'isSent' => false,
            'sentAt' => null,
        ]);
    }
}