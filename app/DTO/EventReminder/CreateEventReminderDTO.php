<?php

namespace App\DTO\EventReminder;

use App\Enums\EventReminderType;

class CreateEventReminderDTO
{
    public function __construct(
        public readonly int $eventId,
        public readonly EventReminderType $type,
        public readonly int $minutesBefore,
    ) {
    }
}