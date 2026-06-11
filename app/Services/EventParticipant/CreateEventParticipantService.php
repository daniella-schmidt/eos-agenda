<?php

namespace App\Services\EventParticipant;

use App\DTO\EventParticipant\CreateEventParticipantDTO;
use App\Enums\EventParticipantResponseStatus;
use App\Models\Contact;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Validation\ValidationException;

class CreateEventParticipantService
{
    public function handle(
        Event $event,
        CreateEventParticipantDTO $dto
    ): EventParticipant {
        if ($event->id !== $dto->eventId) {
            throw ValidationException::withMessages([
                'eventId' => ['Evento inválido.'],
            ]);
        }

        $contact = null;

        if ($dto->contactId) {
            $contact = Contact::query()
                ->whereKey($dto->contactId)
                ->where('userId', $event->userId)
                ->firstOrFail();
        }

        $name = $dto->name ?? $contact?->name;
        $email = $dto->email ?? $contact?->email;

        if (! $name && ! $email) {
            throw ValidationException::withMessages([
                'participant' => [
                    'Participante precisa ter nome ou e-mail.',
                ],
            ]);
        }

        if ($email) {
            $alreadyExists = EventParticipant::query()
                ->where('eventId', $event->id)
                ->where('email', $email)
                ->exists();

            if ($alreadyExists) {
                throw ValidationException::withMessages([
                    'email' => [
                        'Este participante já foi adicionado ao evento.',
                    ],
                ]);
            }
        }

        return EventParticipant::create([
            'eventId' => $event->id,
            'contactId' => $contact?->id,
            'name' => $name,
            'email' => $email,
            'role' => $dto->role,
            'responseStatus' => EventParticipantResponseStatus::Pending,
        ]);
    }
}