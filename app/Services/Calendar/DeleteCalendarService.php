<?php

namespace App\Services\Calendar;

use App\Models\Calendar;
use Illuminate\Validation\ValidationException;

class DeleteCalendarService
{
    public function handle(Calendar $calendar): void
    {
        if ($calendar->isDefault) {
            throw ValidationException::withMessages([
                'calendar' => [
                    'O calendário padrão não pode ser excluído.',
                ],
            ]);
        }

        if ($calendar->events()->exists()) {
            throw ValidationException::withMessages([
                'calendar' => [
                    'O calendário possui eventos vinculados e não pode ser excluído.',
                ],
            ]);
        }

        $calendar->delete();
    }
}