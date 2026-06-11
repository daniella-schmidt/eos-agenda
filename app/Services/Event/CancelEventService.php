<?php

namespace App\Services\Event;

use App\Enums\EventStatus;
use App\Models\Event;

class CancelEventService
{
    public function handle(Event $event): Event
    {
        if ($event->status === EventStatus::Cancelled) {
            return $event;
        }

        $event->update([
            'status' => EventStatus::Cancelled,
        ]);

        return $event->refresh();
    }
}