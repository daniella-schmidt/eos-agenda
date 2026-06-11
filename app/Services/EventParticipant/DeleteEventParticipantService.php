<?php

namespace App\Services\EventParticipant;

use App\Models\EventParticipant;

class DeleteEventParticipantService
{
    public function handle(EventParticipant $eventParticipant): void
    {
        $eventParticipant->delete();
    }
}