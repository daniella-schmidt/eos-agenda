<?php

namespace App\Http\Controllers;

use App\DTO\SmartRequest\CreateSmartRequestDTO;
use App\Enums\SmartRequestStatus;
use App\Http\Requests\SmartRequest\StoreSmartRequestRequest;
use App\Http\Resources\SmartRequestResource;
use App\Models\SmartRequest;
use App\Services\SmartRequest\ConfirmSmartRequestService;
use App\Services\SmartRequest\CreateSmartRequestService;
use App\Services\SmartRequest\DeleteSmartRequestService;
use App\Services\SmartRequest\GetSmartRequestsByStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class SmartRequestController extends Controller
{

    public function update(Request $request, SmartRequest $smartRequest)
    {
        Gate::authorize('update', $smartRequest);
        $validated = $request->validate([
            'extractedTitle' => 'nullable|string',
            'extractedDescription' => 'nullable|string',
            'extractedStartAt' => 'nullable|date',
            'extractedEndAt' => 'nullable|date',
            'extractedParticipants' => 'nullable|array',
        ]);
        $smartRequest->update($validated);
        return response()->json(['data' => $smartRequest]);
    }
    public function byStatus(
        Request $request,
        SmartRequestStatus $status,
        GetSmartRequestsByStatusService $service
    ): JsonResponse {
        $smartRequests = $service->handle(
            userId: (string) $request->user()->id,
            status: $status,
        );

        return SmartRequestResource::collection($smartRequests)->response();
    }

    public function store(
        StoreSmartRequestRequest $request,
        CreateSmartRequestService $service
    ): JsonResponse {
        $smartRequest = $service->handle(
            new CreateSmartRequestDTO(
                userId: $request->user()->id,
                rawText: $request->validated('rawText')
            )
        );

        return (new SmartRequestResource($smartRequest))
            ->response()
            ->setStatusCode(201);
    }

    public function confirm(
        SmartRequest $smartRequest,
        ConfirmSmartRequestService $service
    ): JsonResponse {
        abort_unless($smartRequest->userId === request()->user()?->id, 403);

        $event = $service->handle($smartRequest);

        return response()->json([
            'message' => 'Evento criado com sucesso.',
            'event_id' => $event->id,
        ], 201);
    }

    public function destroy(
        SmartRequest $smartRequest,
        DeleteSmartRequestService $service
    ): Response {
        abort_unless($smartRequest->userId === request()->user()?->id, 403);

        $service->handle($smartRequest);

        return response()->noContent();
    }
}
