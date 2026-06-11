<?php

namespace App\Services\Event;

use App\DTO\Event\CreateEventDTO;
use App\Enums\EventSource;
use App\Enums\EventStatus;
use App\Models\Calendar;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateEventService
{
    public function __construct(
        private readonly CheckEventConflictService $checkEventConflictService,
    ) {
    }

    public function handle(CreateEventDTO $dto): Event
    {
        return DB::transaction(function () use ($dto): Event {
            $calendar = Calendar::query()
                ->whereKey($dto->calendarId)
                ->where('userId', $dto->userId)
                ->where('isActive', true)
                ->first();

            if (! $calendar) {
                throw ValidationException::withMessages([
                    'calendarId' => [
                        'O calendário informado não existe ou não está ativo.',
                    ],
                ]);
            }

            if (
                $dto->status === EventStatus::Confirmed
                && $this->checkEventConflictService->handle(
                    userId: $dto->userId,
                    startAt: $dto->startAt,
                    endAt: $dto->endAt,
                )
            ) {
                throw ValidationException::withMessages([
                    'event' => [
                        'Já existe um evento confirmado nesse período.',
                    ],
                ]);
            }

            return Event::create([
                'userId' => $dto->userId,
                'calendarId' => $dto->calendarId,
                'smartRequestId' => null,

                'title' => $dto->title,
                'description' => $dto->description,

                'startAt' => $dto->startAt,
                'endAt' => $dto->endAt,

                'timezone' => $dto->timezone,
                'location' => $dto->location,
                'meetingURL' => $dto->meetingURL,

                'status' => $dto->status,
                'priority' => $dto->priority,
                'source' => EventSource::Manual,

                'isAllDay' => $dto->isAllDay,
                'isRecurring' => false,
                'createdByAI' => false,
            ]);
        });
    }
}