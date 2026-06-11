<?php

namespace App\Http\Controllers;

use App\DTO\Calendar\CreateCalendarDTO;
use App\DTO\Calendar\UpdateCalendarDTO;
use App\Http\Requests\Calendar\StoreCalendarRequest;
use App\Http\Requests\Calendar\UpdateCalendarRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use App\Services\Calendar\CreateCalendarService;
use App\Services\Calendar\DeleteCalendarService;
use App\Services\Calendar\MakeDefaultCalendarService;
use App\Services\Calendar\UpdateCalendarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia; // se não usar Inertia, retorne view normal

class CalendarController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $calendars = Calendar::query()
            ->where('userId', $request->user()->id)
            ->withCount('events')
            ->orderByDesc('isDefault')
            ->orderBy('name')
            ->get();

        return CalendarResource::collection($calendars)->response();
    }

    public function showCalendar(Calendar $calendar)
    {
        Gate::authorize('view', $calendar);
        return view('calendars.show', compact('calendar'));
    }
    public function store(
        StoreCalendarRequest $request,
        CreateCalendarService $service
    ): JsonResponse {
        $calendar = $service->handle(
            new CreateCalendarDTO(
                userId: $request->user()->id,
                name: $request->validated('name'),
                description: $request->validated('description'),
                color: $request->validated('color'),
                isDefault: (bool) $request->validated('isDefault', false),
            )
        );

        return (new CalendarResource($calendar))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Calendar $calendar): JsonResponse
    {
        Gate::authorize('view', $calendar);

        $calendar->loadCount('events');

        return (new CalendarResource($calendar))->response();
    }

    public function update(
        UpdateCalendarRequest $request,
        Calendar $calendar,
        UpdateCalendarService $service
    ): JsonResponse {
        Gate::authorize('update', $calendar);

        $calendar = $service->handle(
            $calendar,
            new UpdateCalendarDTO(
                attributes: $request->safe()->only([
                    'name',
                    'description',
                    'color',
                    'isActive',
                ]),
            )
        );

        return (new CalendarResource($calendar))->response();
    }

    public function makeDefault(
        Calendar $calendar,
        MakeDefaultCalendarService $service
    ): JsonResponse {
        Gate::authorize('makeDefault', $calendar);

        $calendar = $service->handle($calendar);

        return (new CalendarResource($calendar))->response();
    }

    public function destroy(
        Calendar $calendar,
        DeleteCalendarService $service
    ): Response {
        Gate::authorize('delete', $calendar);

        $service->handle($calendar);

        return response()->noContent();
    }
}