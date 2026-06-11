<?php

namespace App\DTO\EventParticipant;

class UpdateEventParticipantDTO
{
    public function __construct(
        public readonly array $attributes,
    ) {
    }
}