<?php

namespace App\Services\EventParticipant;

use App\DTO\EventParticipant\UpdateEventParticipantDTO;
use App\Models\EventParticipant;

class UpdateEventParticipantService
{
    public function handle(
        EventParticipant $eventParticipant,
        UpdateEventParticipantDTO $dto
    ): EventParticipant {
        $eventParticipant->update($dto->attributes);

        return $eventParticipant->refresh();
    }
}