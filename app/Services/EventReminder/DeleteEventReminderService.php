<?php

namespace App\Services\EventReminder;

use App\Models\EventReminder;
use Illuminate\Validation\ValidationException;

class DeleteEventReminderService
{
    public function handle(EventReminder $eventReminder): void
    {
        if ($eventReminder->isSent) {
            throw ValidationException::withMessages([
                'reminder' => [
                    'Lembretes já enviados não podem ser excluídos.',
                ],
            ]);
        }

        $eventReminder->delete();
    }
}