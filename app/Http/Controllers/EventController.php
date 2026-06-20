<?php

namespace App\Http\Controllers;

use App\DTO\Event\CreateEventDTO;
use App\DTO\Event\UpdateEventDTO;
use App\Enums\EventPriority;
use App\Enums\EventStatus;
use App\Http\Requests\Event\IndexEventRequest;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Calendar;
use App\Models\Event;
use App\Services\Event\CancelEventService;
use App\Services\Event\CreateEventService;
use App\Services\Event\DeleteEventService;
use App\Services\Event\UpdateEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index(
        IndexEventRequest $request
    ): AnonymousResourceCollection {
        Gate::authorize('viewAny', Event::class);

        $events = Event::query()
            ->where('userId', $request->user()->id)
            ->with(['calendar', 'participants'])
            ->withCount(['participants', 'reminders'])

            ->when(
                $request->validated('calendarId'),
                fn ($query, $calendarId) => $query->where('calendarId', $calendarId)
            )

            ->when(
                $request->validated('status'),
                fn ($query, $status) => $query->where('status', $status)
            )

            ->when(
                $request->validated('from'),
                fn ($query, $from) => $query->where('endAt', '>', $from)
            )

            ->when(
                $request->validated('to'),
                fn ($query, $to) => $query->where('startAt', '<', $to)
            )

            ->orderBy('startAt')
            ->paginate($request->validated('perPage', 20));

        return EventResource::collection($events);
    }

    public function store(
        StoreEventRequest $request,
        CreateEventService $service
    ): JsonResponse {
        Gate::authorize('create', Event::class);

        $calendarId = $request->filled('calendarId')
            ? $request->integer('calendarId')
            : Calendar::query()
                ->where('userId', $request->user()->id)
                ->where('isDefault', true)
                ->where('isActive', true)
                ->value('id');

        if (! $calendarId) {
            throw ValidationException::withMessages([
                'calendarId' => [
                    'Nenhum calendário padrão ativo foi encontrado.',
                ],
            ]);
        }

        $event = $service->handle(
            new CreateEventDTO(
                userId: $request->user()->id,
                calendarId: $calendarId,
                title: $request->validated('title'),
                description: $request->validated('description'),
                startAt: $request->validated('startAt'),
                endAt: $request->validated('endAt'),
                timezone: $request->validated(
                    'timezone',
                    $request->user()->timezone ?? 'America/Sao_Paulo'
                ),
                location: $request->validated('location'),
                meetingURL: $request->validated('meetingURL'),
                status: EventStatus::Confirmed,
                priority: EventPriority::from(
                    $request->validated(
                        'priority',
                        EventPriority::Medium->value
                    )
                ),
                isAllDay: $request->boolean('isAllDay'),
                isRecurring: $request->boolean('isRecurring'),
            )
        );

        return (new EventResource($event))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Event $event): JsonResponse
    {
        Gate::authorize('view', $event);

        $event->load([
            'calendar',
            'participants',
            'reminders',
        ]);

        return (new EventResource($event))->response();
    }

    public function update(
        UpdateEventRequest $request,
        Event $event,
        UpdateEventService $service
    ): JsonResponse {
        Gate::authorize('update', $event);

        $event = $service->handle(
            $event,
            new UpdateEventDTO(
                attributes: $request->safe()->only([
                    'calendarId',
                    'title',
                    'description',
                    'startAt',
                    'endAt',
                    'timezone',
                    'location',
                    'meetingURL',
                    'status',
                    'priority',
                    'isAllDay',
                    'isRecurring',
                ]),
            )
        );

        return (new EventResource($event))->response();
    }

    public function cancel(
        Event $event,
        CancelEventService $service
    ): JsonResponse {
        Gate::authorize('cancel', $event);

        $event = $service->handle($event);

        return (new EventResource($event))->response();
    }

    public function destroy(
        Event $event,
        DeleteEventService $service
    ): Response {
        Gate::authorize('delete', $event);

        $service->handle($event);

        return response()->noContent();
    }

    public function indexByCalendar(
        IndexEventRequest $request,
        Calendar $calendar
    ): AnonymousResourceCollection {
        Gate::authorize('view', $calendar);

        $events = $calendar->events()
            ->where('userId', $request->user()->id)
            ->with(['calendar', 'participants'])
            ->withCount(['participants', 'reminders'])
            ->when(
                $request->validated('status'),
                fn ($query, $status) => $query->where('status', $status)
            )
            ->when(
                $request->validated('from'),
                fn ($query, $from) => $query->where('endAt', '>', $from)
            )
            ->when(
                $request->validated('to'),
                fn ($query, $to) => $query->where('startAt', '<', $to)
            )
            ->orderBy('startAt')
            ->paginate($request->validated('perPage', 20));

        return EventResource::collection($events);
    }
}
