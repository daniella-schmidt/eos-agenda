<?php

namespace App\Services\Calendar;

use App\DTO\Calendar\CreateCalendarDTO;
use App\Models\Calendar;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateCalendarService
{
    public function handle(CreateCalendarDTO $dto): Calendar
    {
        return DB::transaction(function () use ($dto): Calendar {
            User::query()
                ->whereKey($dto->userId)
                ->lockForUpdate()
                ->firstOrFail();

            $userHasCalendar = Calendar::query()
                ->where('userId', $dto->userId)
                ->exists();

            $shouldBeDefault = $dto->isDefault || ! $userHasCalendar;

            if ($shouldBeDefault) {
                Calendar::query()
                    ->where('userId', $dto->userId)
                    ->update([
                        'isDefault' => false,
                    ]);
            }

            return Calendar::create([
                'userId' => $dto->userId,
                'name' => $dto->name,
                'description' => $dto->description,
                'color' => $dto->color,
                'isDefault' => $shouldBeDefault,
                'isActive' => true,
            ]);
        });
    }
}