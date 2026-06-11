<?php

namespace App\Services\EventReminder;

use App\Models\EventReminder;

class MarkEventReminderAsSentService
{
    public function handle(EventReminder $eventReminder): EventReminder
    {
        if ($eventReminder->isSent) {
            return $eventReminder;
        }

        $eventReminder->update([
            'isSent' => true,
            'sentAt' => now(),
        ]);

        return $eventReminder->refresh();
    }
}