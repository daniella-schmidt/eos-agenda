<?php

namespace App\Services\SmartRequest;

use App\DTO\SmartRequest\CreateSmartRequestDTO;
use App\Enums\SmartRequestStatus;
use App\Models\SmartRequest;
use App\Models\User;

class CreateSmartRequestService
{
    public function __construct(
        private ExtractEventDataService $extractEventDataService,
        private CheckEventConflictService $checkEventConflictService,
    ) {
    }

    public function handle(CreateSmartRequestDTO $dto): SmartRequest
    {
        $smartRequest = SmartRequest::create([
            'userId' => $dto->userId,
            'rawText' => $dto->rawText,
            'intent' => 'create_event',
            'status' => SmartRequestStatus::Pending,
        ]);

        $timezone = $this->resolveTimezone($dto->userId);
        $extracted = $this->extractEventDataService->handle($dto->rawText, $timezone);

        $extractedData = [
            ...$extracted->raw,
            'missing_fields' => $extracted->missingFields,
        ];

        if (! $extracted->hasMinimumData()) {
            $smartRequest->update([
                'extractedTitle' => $extracted->title,
                'extractedDescription' => $extracted->description,
                'extractedStartAt' => $extracted->startAt,
                'extractedEndAt' => $extracted->endAt,
                'extractedParticipants' => $extracted->participants,
                'extractedData' => $extractedData,
                'status' => SmartRequestStatus::NeedsMoreInfo,
            ]);

            return $smartRequest->fresh();
        }

        $hasConflict = $this->checkEventConflictService->handle(
            userId: $dto->userId,
            startAt: $extracted->startAt,
            endAt: $extracted->endAt
        );

        $smartRequest->update([
            'extractedTitle' => $extracted->title,
            'extractedDescription' => $extracted->description,
            'extractedStartAt' => $extracted->startAt,
            'extractedEndAt' => $extracted->endAt,
            'extractedParticipants' => $extracted->participants,
            'extractedData' => $extractedData,
            'status' => $hasConflict
                ? SmartRequestStatus::SuggestingTimes
                : SmartRequestStatus::NeedsConfirmation,
        ]);

        return $smartRequest->fresh();
    }

    private function resolveTimezone(string $userId): string
    {
        $timezone = User::query()
            ->whereKey($userId)
            ->value('timezone');

        return $timezone === 'BRT' || ! $timezone
            ? 'America/Sao_Paulo'
            : $timezone;
    }
}
