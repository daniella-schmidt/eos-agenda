<?php

namespace App\DTO\Event;

use App\Enums\EventPriority;
use App\Enums\EventStatus;

class CreateEventDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $calendarId,
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $startAt,
        public readonly string $endAt,
        public readonly string $timezone,
        public readonly ?string $location,
        public readonly ?string $meetingURL,
        public readonly EventStatus $status,
        public readonly EventPriority $priority,
        public readonly bool $isAllDay,
    ) {
    }
}