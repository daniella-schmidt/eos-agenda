<?php

namespace App\Services\EventReminder;

use App\DTO\EventReminder\UpdateEventReminderDTO;
use App\Models\EventReminder;
use Illuminate\Validation\ValidationException;

class UpdateEventReminderService
{
    public function handle(
        EventReminder $eventReminder,
        UpdateEventReminderDTO $dto
    ): EventReminder {
        if ($eventReminder->isSent) {
            throw ValidationException::withMessages([
                'reminder' => [
                    'Lembretes já enviados não podem ser alterados.',
                ],
            ]);
        }

        $attributes = $dto->attributes;

        if (isset($attributes['type']) && is_object($attributes['type'])) {
            $attributes['type'] = $attributes['type']->value;
        }

        $eventReminder->update($attributes);

        return $eventReminder->refresh();
    }
}