<?php

namespace App\Http\Controllers;

use App\DTO\UserPreference\UpdateUserPreferenceDTO;
use App\Http\Requests\UserPreference\UpdateUserPreferenceRequest;
use App\Http\Resources\UserPreferenceResource;
use App\Services\UserPreference\GetOrCreateUserPreferenceService;
use App\Services\UserPreference\UpdateUserPreferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function show(
        Request $request,
        GetOrCreateUserPreferenceService $service
    ): JsonResponse {
        $preference = $service->handle(
            userId: $request->user()->id
        );

        return (new UserPreferenceResource($preference))->response();
    }

    public function update(
        UpdateUserPreferenceRequest $request,
        UpdateUserPreferenceService $service
    ): JsonResponse {
        $preference = $service->handle(
            userId: $request->user()->id,
            dto: new UpdateUserPreferenceDTO(
                attributes: $request->safe()->only([
                    'defaultEventDurationMinutes',
                    'defaultMeetingDurationMinutes',
                    'preferredStartTime',
                    'preferredEndTime',
                    'bufferBetweenEventsMinutes',
                    'requireConfirmationBeforeCreate',
                    'autoCreateMeetingLink',
                    'autoCreateReminder',
                ])
            )
        );

        return (new UserPreferenceResource($preference))->response();
    }
}