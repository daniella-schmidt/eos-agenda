<?php

namespace App\DTO\EventParticipant;

class CreateEventParticipantDTO
{
    public function __construct(
        public readonly int $eventId,
        public readonly ?int $contactId,
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly string $role,
    ) {
    }
}