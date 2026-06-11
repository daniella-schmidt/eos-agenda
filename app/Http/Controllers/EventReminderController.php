<?php

namespace App\Http\Controllers;

use App\DTO\EventReminder\CreateEventReminderDTO;
use App\DTO\EventReminder\UpdateEventReminderDTO;
use App\Enums\EventReminderType;
use App\Http\Requests\EventReminder\StoreEventReminderRequest;
use App\Http\Requests\EventReminder\UpdateEventReminderRequest;
use App\Http\Resources\EventReminderResource;
use App\Models\Event;
use App\Models\EventReminder;
use App\Services\EventReminder\CreateEventReminderService;
use App\Services\EventReminder\DeleteEventReminderService;
use App\Services\EventReminder\MarkEventReminderAsSentService;
use App\Services\EventReminder\UpdateEventReminderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class EventReminderController extends Controller
{
    public function index(Event $event): AnonymousResourceCollection
    {
        Gate::authorize('view', $event);

        $reminders = $event->reminders()
            ->orderBy('minutesBefore')
            ->get();

        return EventReminderResource::collection($reminders);
    }

    public function store(
        StoreEventReminderRequest $request,
        Event $event,
        CreateEventReminderService $service
    ): JsonResponse {
        Gate::authorize('update', $event);

        $type = EventReminderType::from(
            $request->validated(
                'type',
                EventReminderType::notification->value
            )
        );

        $eventReminder = $service->handle(
            event: $event,
            dto: new CreateEventReminderDTO(
                eventId: $event->id,
                type: $type,
                minutesBefore: (int) $request->validated('minutesBefore'),
            )
        );

        return (new EventReminderResource($eventReminder))
            ->response()
            ->setStatusCode(201);
    }

    public function show(EventReminder $eventReminder): JsonResponse
    {
        Gate::authorize('view', $eventReminder);

        return (new EventReminderResource($eventReminder))->response();
    }

    public function update(
        UpdateEventReminderRequest $request,
        EventReminder $eventReminder,
        UpdateEventReminderService $service
    ): JsonResponse {
        Gate::authorize('update', $eventReminder);

        $attributes = $request->safe()->only([
            'type',
            'minutesBefore',
        ]);

        $eventReminder = $service->handle(
            eventReminder: $eventReminder,
            dto: new UpdateEventReminderDTO(
                attributes: $attributes,
            )
        );

        return (new EventReminderResource($eventReminder))->response();
    }

    public function markAsSent(
        EventReminder $eventReminder,
        MarkEventReminderAsSentService $service
    ): JsonResponse {
        Gate::authorize('markAsSent', $eventReminder);

        $eventReminder = $service->handle($eventReminder);

        return (new EventReminderResource($eventReminder))->response();
    }

    public function destroy(
        EventReminder $eventReminder,
        DeleteEventReminderService $service
    ): Response {
        Gate::authorize('delete', $eventReminder);

        $service->handle($eventReminder);

        return response()->noContent();
    }
}