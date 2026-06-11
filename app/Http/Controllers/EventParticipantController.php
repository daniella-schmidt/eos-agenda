<?php

namespace App\Http\Controllers;

use App\DTO\EventParticipant\CreateEventParticipantDTO;
use App\DTO\EventParticipant\UpdateEventParticipantDTO;
use App\Http\Requests\EventParticipant\StoreEventParticipantRequest;
use App\Http\Requests\EventParticipant\UpdateEventParticipantRequest;
use App\Http\Resources\EventParticipantResource;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Services\EventParticipant\CreateEventParticipantService;
use App\Services\EventParticipant\DeleteEventParticipantService;
use App\Services\EventParticipant\UpdateEventParticipantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class EventParticipantController extends Controller
{
    public function index(Event $event): AnonymousResourceCollection
    {
        abort_unless($event->userId === request()->user()?->id, 403);

        $participants = $event->participants()
            ->with('contact')
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return EventParticipantResource::collection($participants);
    }

    public function store(
        StoreEventParticipantRequest $request,
        Event $event,
        CreateEventParticipantService $service
    ): JsonResponse {
        abort_unless($event->userId === $request->user()?->id, 403);

        $participant = $service->handle(
            event: $event,
            dto: new CreateEventParticipantDTO(
                eventId: $event->id,
                contactId: $request->validated('contactId'),
                name: $request->validated('name'),
                email: $request->validated('email'),
                role: $request->validated('role', 'attendee'),
            )
        );

        return (new EventParticipantResource($participant->load('contact')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(EventParticipant $eventParticipant): JsonResponse
    {
        abort_unless($eventParticipant->event?->userId === request()->user()?->id, 403);

        return (new EventParticipantResource(
            $eventParticipant->load('contact')
        ))->response();
    }

    public function update(
        UpdateEventParticipantRequest $request,
        EventParticipant $eventParticipant,
        UpdateEventParticipantService $service
    ): JsonResponse {
        abort_unless($eventParticipant->event?->userId === $request->user()?->id, 403);

        $participant = $service->handle(
            eventParticipant: $eventParticipant,
            dto: new UpdateEventParticipantDTO(
                attributes: $request->safe()->only([
                    'name',
                    'email',
                    'role',
                    'responseStatus',
                ])
            )
        );

        return (new EventParticipantResource(
            $participant->load('contact')
        ))->response();
    }

    public function destroy(
        EventParticipant $eventParticipant,
        DeleteEventParticipantService $service
    ): Response {
        abort_unless($eventParticipant->event?->userId === request()->user()?->id, 403);

        $service->handle($eventParticipant);

        return response()->noContent();
    }
}