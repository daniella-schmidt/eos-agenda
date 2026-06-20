<?php

namespace App\Services\UserPreference;

use App\DTO\UserPreference\UpdateUserPreferenceDTO;
use App\Models\UserPreference;
use Illuminate\Validation\ValidationException;

class UpdateUserPreferenceService
{
    public function __construct(
        private readonly GetOrCreateUserPreferenceService $getOrCreateUserPreferenceService,
    ) {
    }

    public function handle(
        int $userId,
        UpdateUserPreferenceDTO $dto
    ): UserPreference {
        $preference = $this->getOrCreateUserPreferenceService->handle($userId);

        $attributes = $this->normalizeAttributes($dto->attributes);

        $this->validateTimeRange(
            preferredStartTime: $attributes['preferredStartTime']
                ?? $preference->preferredStartTime,
            preferredEndTime: $attributes['preferredEndTime']
                ?? $preference->preferredEndTime,
        );

        $preference->update($attributes);

        return $preference->refresh();
    }

    private function normalizeAttributes(array $attributes): array
    {
        foreach (['preferredStartTime', 'preferredEndTime'] as $field) {
            if (array_key_exists($field, $attributes) && is_string($attributes[$field]) && strlen($attributes[$field]) === 5) {
                $attributes[$field] .= ':00';
            }
        }

        return $attributes;
    }

    private function validateTimeRange(
        ?string $preferredStartTime,
        ?string $preferredEndTime
    ): void {
        if ($preferredStartTime === null || $preferredEndTime === null) {
            return;
        }

        if ($preferredEndTime <= $preferredStartTime) {
            throw ValidationException::withMessages([
                'preferredEndTime' => [
                    'O horário final preferido deve ser posterior ao horário inicial.',
                ],
            ]);
        }
    }
}