<?php

namespace App\Services\EventSuggestion;

use App\Enums\SmartRequestStatus;
use App\Models\EventSuggestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SelectEventSuggestionService
{
    public function handle(EventSuggestion $eventSuggestion): EventSuggestion
    {
        return DB::transaction(function () use ($eventSuggestion): EventSuggestion {
            $eventSuggestion = EventSuggestion::query()
                ->with('smartRequest')
                ->lockForUpdate()
                ->findOrFail($eventSuggestion->id);

            $smartRequest = $eventSuggestion->smartRequest;

            if (! $smartRequest) {
                throw ValidationException::withMessages([
                    'smartRequest' => [
                        'A sugestão não possui uma solicitação vinculada.',
                    ],
                ]);
            }

            EventSuggestion::query()
                ->where('smartRequestId', $smartRequest->id)
                ->update([
                    'selected' => false,
                ]);

            $eventSuggestion->update([
                'selected' => true,
            ]);

            $smartRequest->update([
                'extractedStartAt' => $eventSuggestion->suggestedStartAt,
                'extractedEndAt' => $eventSuggestion->suggestedEndAt,
                'status' => SmartRequestStatus::NeedsConfirmation,
            ]);

            return $eventSuggestion->refresh();
        });
    }
}
