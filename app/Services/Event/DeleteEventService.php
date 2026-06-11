<?php

namespace App\Services\Event;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Validation\ValidationException;

class DeleteEventService
{
    public function handle(Event $event): void
    {
        if ($event->status !== EventStatus::Draft) {
            throw ValidationException::withMessages([
                'event' => [
                    'Somente eventos em rascunho podem ser excluídos. Cancele eventos confirmados.',
                ],
            ]);
        }

        $event->delete();
    }
}