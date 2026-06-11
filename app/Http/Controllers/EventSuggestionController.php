<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventSuggestion\GenerateEventSuggestionRequest;
use App\Http\Resources\EventSuggestionResource;
use App\Models\EventSuggestion;
use App\Models\SmartRequest;
use App\Services\EventSuggestion\GenerateEventSuggestionsService;
use App\Services\EventSuggestion\SelectEventSuggestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventSuggestionController extends Controller
{
    public function index(
        SmartRequest $smartRequest
    ): AnonymousResourceCollection {
        abort_unless($smartRequest->userId === request()->user()?->id, 403);

        $suggestions = $smartRequest
            ->suggestions()
            ->orderByDesc('selected')
            ->orderByDesc('score')
            ->orderBy('suggestedStartAt')
            ->get();

        return EventSuggestionResource::collection($suggestions);
    }

    public function generate(
        GenerateEventSuggestionRequest $request,
        SmartRequest $smartRequest,
        GenerateEventSuggestionsService $service,
    ): AnonymousResourceCollection {
        abort_unless($smartRequest->userId === $request->user()?->id, 403);

        $suggestions = $service->handle(
            smartRequest: $smartRequest,
            daysAhead: (int) $request->validated('daysAhead', 7),
            limit: (int) $request->validated('limit', 3),
        );

        return EventSuggestionResource::collection($suggestions);
    }

    public function select(
        EventSuggestion $eventSuggestion,
        SelectEventSuggestionService $service,
    ): JsonResponse {
        abort_unless($eventSuggestion->userId === request()->user()?->id, 403);

        $eventSuggestion = $service->handle($eventSuggestion);

        return (new EventSuggestionResource($eventSuggestion))
            ->response();
    }
}