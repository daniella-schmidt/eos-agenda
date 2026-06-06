<?php

namespace App\Services\Calendar;

use App\DTO\Calendar\UpdateCalendarDTO;
use App\Models\Calendar;
use Illuminate\Validation\ValidationException;

class UpdateCalendarService
{
    public function handle(
        Calendar $calendar,
        UpdateCalendarDTO $dto
    ): Calendar {
        if (
            $calendar->isDefault
            && array_key_exists('isActive', $dto->attributes)
            && $dto->attributes['isActive'] === false
        ) {
            throw ValidationException::withMessages([
                'isActive' => [
                    'O calendário padrão não pode ser desativado.',
                ],
            ]);
        }

        $calendar->update($dto->attributes);

        return $calendar->refresh();
    }
}