<?php

namespace App\Services\Event;

use App\DTO\Event\UpdateEventDTO;
use App\Enums\EventStatus;
use App\Models\Calendar;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateEventService
{
    public function __construct(
        private readonly CheckEventConflictService $checkEventConflictService,
    ) {
    }

    public function handle(Event $event, UpdateEventDTO $dto): Event
    {
        return DB::transaction(function () use ($event, $dto): Event {
            if ($event->status === EventStatus::Cancelled) {
                throw ValidationException::withMessages([
                    'event' => [
                        'Eventos cancelados não podem ser alterados.',
                    ],
                ]);
            }

            $attributes = $dto->attributes;

            $calendarId = $attributes['calendarId'] ?? $event->calendarId;
            $startAt = $attributes['startAt'] ?? $event->startAt->toDateTimeString();
            $endAt = $attributes['endAt'] ?? $event->endAt->toDateTimeString();

            $status = isset($attributes['status'])
                ? EventStatus::from($attributes['status'])
                : $event->status;

            $calendarExists = Calendar::query()
                ->whereKey($calendarId)
                ->where('userId', $event->userId)
                ->where('isActive', true)
                ->exists();

            if (! $calendarExists) {
                throw ValidationException::withMessages([
                    'calendarId' => [
                        'O calendário informado não existe ou não está ativo.',
                    ],
                ]);
            }

            if (
                $status === EventStatus::Confirmed
                && $this->checkEventConflictService->handle(
                    userId: $event->userId,
                    startAt: $startAt,
                    endAt: $endAt,
                    ignoreEventId: $event->id,
                )
            ) {
                throw ValidationException::withMessages([
                    'event' => [
                        'Já existe outro evento confirmado nesse período.',
                    ],
                ]);
            }

            $event->update($attributes);

            return $event->refresh();
        });
    }
}