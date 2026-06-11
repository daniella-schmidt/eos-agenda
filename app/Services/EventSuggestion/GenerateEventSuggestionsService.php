<?php

namespace App\Services\EventSuggestion;

use App\Enums\SmartRequestStatus;
use App\Models\EventSuggestion;
use App\Models\SmartRequest;
use App\Models\UserPreference;
use App\Services\Event\CheckEventConflictService;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GenerateEventSuggestionsService
{
    public function __construct(
        private readonly CheckEventConflictService $checkEventConflictService,
    ) {
    }

    public function handle(
        SmartRequest $smartRequest,
        int $daysAhead = 7,
        int $limit = 3,
    ): Collection {
        return DB::transaction(function () use (
            $smartRequest,
            $daysAhead,
            $limit
        ): Collection {
            $smartRequest = SmartRequest::query()
                ->lockForUpdate()
                ->findOrFail($smartRequest->id);

            $this->validateSmartRequest($smartRequest);

            EventSuggestion::query()
                ->where('smartRequestId', $smartRequest->id)
                ->where('selected', false)
                ->delete();

            $preference = $this->getUserPreference($smartRequest->userId);

            $durationMinutes = $this->resolveDurationMinutes(
                smartRequest: $smartRequest,
                preference: $preference,
            );

            $baseDate = $smartRequest->extractedStartAt
                ? Carbon::parse($smartRequest->extractedStartAt)->startOfDay()
                : now()->startOfDay();

            $suggestions = collect();

            for ($day = 0; $day < $daysAhead; $day++) {
                $date = $baseDate->copy()->addDays($day);

                $daySuggestions = $this->generateSuggestionsForDay(
                    smartRequest: $smartRequest,
                    preference: $preference,
                    date: $date,
                    durationMinutes: $durationMinutes,
                    remainingLimit: $limit - $suggestions->count(),
                );

                $suggestions = $suggestions->merge($daySuggestions);

                if ($suggestions->count() >= $limit) {
                    break;
                }
            }

            if ($suggestions->isEmpty()) {
                throw ValidationException::withMessages([
                    'suggestions' => [
                        'Nenhum horário disponível foi encontrado no período informado.',
                    ],
                ]);
            }

            $smartRequest->update([
                'status' => SmartRequestStatus::SuggestingTimes,
            ]);

            return $suggestions;
        });
    }

    private function validateSmartRequest(SmartRequest $smartRequest): void
    {
        if ($smartRequest->userId === null) {
            throw ValidationException::withMessages([
                'smartRequest' => [
                    'Solicitação inválida.',
                ],
            ]);
        }

        if (! $smartRequest->extractedTitle) {
            throw ValidationException::withMessages([
                'smartRequest' => [
                    'A solicitação ainda não possui título extraído.',
                ],
            ]);
        }
    }

    private function getUserPreference(int $userId): UserPreference
    {
        return UserPreference::firstOrCreate(
            ['userId' => $userId],
            [
                'defaultEventDurationMinutes' => 60,
                'defaultMeetingDurationMinutes' => 30,
                'preferredStartTime' => '09:00:00',
                'preferredEndTime' => '18:00:00',
                'bufferBetweenEventsMinutes' => 15,
                'requireConfirmationBeforeCreate' => true,
                'autoCreateMeetingLink' => false,
                'autoCreateReminder' => true,
            ]
        );
    }

    private function resolveDurationMinutes(
        SmartRequest $smartRequest,
        UserPreference $preference
    ): int {
        if (
            $smartRequest->extractedStartAt
            && $smartRequest->extractedEndAt
        ) {
            return Carbon::parse($smartRequest->extractedStartAt)
                ->diffInMinutes(Carbon::parse($smartRequest->extractedEndAt));
        }

        return $preference->defaultMeetingDurationMinutes;
    }

    private function generateSuggestionsForDay(
        SmartRequest $smartRequest,
        UserPreference $preference,
        CarbonInterface $date,
        int $durationMinutes,
        int $remainingLimit,
    ): Collection {
        $suggestions = collect();

        $preferredStart = Carbon::parse(
            $date->format('Y-m-d') . ' ' . $preference->preferredStartTime
        );

        $preferredEnd = Carbon::parse(
            $date->format('Y-m-d') . ' ' . $preference->preferredEndTime
        );

        $cursor = $preferredStart->copy();

        while ($cursor->copy()->addMinutes($durationMinutes)->lte($preferredEnd)) {
            $suggestedStartAt = $cursor->copy();
            $suggestedEndAt = $cursor->copy()->addMinutes($durationMinutes);

            $startWithBuffer = $suggestedStartAt
                ->copy()
                ->subMinutes($preference->bufferBetweenEventsMinutes);

            $endWithBuffer = $suggestedEndAt
                ->copy()
                ->addMinutes($preference->bufferBetweenEventsMinutes);

            $hasConflict = $this->checkEventConflictService->handle(
                userId: $smartRequest->userId,
                startAt: $startWithBuffer->toDateTimeString(),
                endAt: $endWithBuffer->toDateTimeString(),
            );

            if (! $hasConflict && $suggestedStartAt->isFuture()) {
                $suggestions->push(
                    EventSuggestion::create([
                        'userId' => $smartRequest->userId,
                        'smartRequestId' => $smartRequest->id,
                        'suggestedStartAt' => $suggestedStartAt,
                        'suggestedEndAt' => $suggestedEndAt,
                        'score' => $this->calculateScore(
                            suggestedStartAt: $suggestedStartAt,
                            preference: $preference,
                        ),
                        'reason' => $this->buildReason(
                            suggestedStartAt: $suggestedStartAt,
                            durationMinutes: $durationMinutes,
                            bufferMinutes: $preference->bufferBetweenEventsMinutes,
                        ),
                        'selected' => false,
                    ])
                );
            }

            if ($suggestions->count() >= $remainingLimit) {
                break;
            }

            $cursor->addMinutes(30);
        }

        return $suggestions;
    }

    private function calculateScore(
        CarbonInterface $suggestedStartAt,
        UserPreference $preference,
    ): float {
        $preferredStart = Carbon::parse(
            $suggestedStartAt->format('Y-m-d') . ' ' . $preference->preferredStartTime
        );

        $minutesFromPreferredStart = abs(
            $preferredStart->diffInMinutes($suggestedStartAt, false)
        );

        $score = 100 - min($minutesFromPreferredStart / 10, 30);

        return round(max($score, 50), 2);
    }

    private function buildReason(
        CarbonInterface $suggestedStartAt,
        int $durationMinutes,
        int $bufferMinutes,
    ): string {
        return sprintf(
            'Horário livre em %s, com duração de %d minutos e buffer de %d minutos entre eventos.',
            $suggestedStartAt->format('d/m/Y H:i'),
            $durationMinutes,
            $bufferMinutes,
        );
    }
}
