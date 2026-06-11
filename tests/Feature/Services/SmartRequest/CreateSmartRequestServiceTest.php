<?php

namespace Tests\Feature\Services\SmartRequest;

use App\DTO\SmartRequest\CreateSmartRequestDTO;
use App\DTO\SmartRequest\ExtractedEventDataDTO;
use App\Enums\SmartRequestStatus;
use App\Models\SmartRequest;
use App\Models\User;
use App\Services\Event\CheckEventConflictService;
use App\Services\EventSuggestion\GenerateEventSuggestionsService;
use App\Services\SmartRequest\CreateSmartRequestService;
use App\Services\SmartRequest\ExtractEventDataService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class CreateSmartRequestServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_suggestions_when_the_extracted_period_has_a_conflict(): void
    {
        $user = User::factory()->create();
        $extracted = $this->extractedEventData();

        $extractor = Mockery::mock(ExtractEventDataService::class);
        $extractor
            ->shouldReceive('handle')
            ->once()
            ->andReturn($extracted);

        $conflictChecker = Mockery::mock(CheckEventConflictService::class);
        $conflictChecker
            ->shouldReceive('handle')
            ->once()
            ->andReturnTrue();

        $suggestionGenerator = Mockery::mock(GenerateEventSuggestionsService::class);
        $suggestionGenerator
            ->shouldReceive('handle')
            ->once()
            ->with(Mockery::on(
                fn (SmartRequest $smartRequest): bool =>
                    $smartRequest->status === SmartRequestStatus::SuggestingTimes
                    && $smartRequest->extractedTitle === $extracted->title
                    && $smartRequest->extractedStartAt?->format('Y-m-d H:i:s') === $extracted->startAt
                    && $smartRequest->extractedEndAt?->format('Y-m-d H:i:s') === $extracted->endAt
            ))
            ->andReturn(new Collection());

        $smartRequest = $this->service(
            extractor: $extractor,
            conflictChecker: $conflictChecker,
            suggestionGenerator: $suggestionGenerator,
        )->handle(new CreateSmartRequestDTO(
            userId: (string) $user->id,
            rawText: 'Reuniao amanha as 10h.',
        ));

        $this->assertSame(SmartRequestStatus::SuggestingTimes, $smartRequest->status);
    }

    public function test_it_does_not_generate_suggestions_when_there_is_no_conflict(): void
    {
        $user = User::factory()->create();
        $extracted = $this->extractedEventData();

        $extractor = Mockery::mock(ExtractEventDataService::class);
        $extractor
            ->shouldReceive('handle')
            ->once()
            ->andReturn($extracted);

        $conflictChecker = Mockery::mock(CheckEventConflictService::class);
        $conflictChecker
            ->shouldReceive('handle')
            ->once()
            ->andReturnFalse();

        $suggestionGenerator = Mockery::mock(GenerateEventSuggestionsService::class);
        $suggestionGenerator
            ->shouldNotReceive('handle');

        $smartRequest = $this->service(
            extractor: $extractor,
            conflictChecker: $conflictChecker,
            suggestionGenerator: $suggestionGenerator,
        )->handle(new CreateSmartRequestDTO(
            userId: (string) $user->id,
            rawText: 'Reuniao amanha as 10h.',
        ));

        $this->assertSame(SmartRequestStatus::NeedsConfirmation, $smartRequest->status);
    }

    private function service(
        ExtractEventDataService $extractor,
        CheckEventConflictService $conflictChecker,
        GenerateEventSuggestionsService $suggestionGenerator,
    ): CreateSmartRequestService {
        return new CreateSmartRequestService(
            extractEventDataService: $extractor,
            checkEventConflictService: $conflictChecker,
            generateEventSuggestionsService: $suggestionGenerator,
        );
    }

    private function extractedEventData(): ExtractedEventDataDTO
    {
        return new ExtractedEventDataDTO(
            title: 'Reuniao de planejamento',
            description: null,
            startAt: '2026-06-10 10:00:00',
            endAt: '2026-06-10 10:30:00',
            participants: [],
            missingFields: [],
            raw: [],
        );
    }
}
