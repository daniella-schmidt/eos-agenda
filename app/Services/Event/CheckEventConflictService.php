<?php

namespace App\Services\Event;

use App\Enums\EventStatus;
use App\Models\Event;
use Carbon\CarbonInterface;

class CheckEventConflictService
{
    public function handle(
        int|string $userId,
        string|CarbonInterface $startAt,
        string|CarbonInterface $endAt,
        ?int $ignoreEventId = null,
        ?int $calendarId = null,
    ): bool {
        return Event::query()
            ->where('userId', $userId)
            ->where('status', EventStatus::Confirmed->value)

            ->when(
                $calendarId,
                fn ($query) => $query->where('calendarId', $calendarId)
            )

            ->when(
                $ignoreEventId,
                fn ($query) => $query->whereKeyNot($ignoreEventId)
            )

            ->where('startAt', '<', $endAt)
            ->where('endAt', '>', $startAt)
            ->exists();
    }
}