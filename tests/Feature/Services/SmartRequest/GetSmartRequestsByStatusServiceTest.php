<?php

namespace Tests\Feature\Services\SmartRequest;

use App\Enums\SmartRequestStatus;
use App\Models\SmartRequest;
use App\Models\User;
use App\Services\SmartRequest\GetSmartRequestsByStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetSmartRequestsByStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_the_users_smart_requests_with_the_requested_status(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $older = $this->createSmartRequest(
            userId: $user->id,
            status: SmartRequestStatus::Pending,
            createdAt: '2026-06-05 10:00:00',
        );

        $newer = $this->createSmartRequest(
            userId: $user->id,
            status: SmartRequestStatus::Pending,
            createdAt: '2026-06-06 10:00:00',
        );

        $this->createSmartRequest(
            userId: $user->id,
            status: SmartRequestStatus::Completed,
            createdAt: '2026-06-06 11:00:00',
        );

        $this->createSmartRequest(
            userId: $otherUser->id,
            status: SmartRequestStatus::Pending,
            createdAt: '2026-06-06 12:00:00',
        );

        $smartRequests = app(GetSmartRequestsByStatusService::class)->handle(
            userId: (string) $user->id,
            status: SmartRequestStatus::Pending,
        );

        $this->assertSame(
            [$newer->id, $older->id],
            $smartRequests->modelKeys(),
        );
    }

    private function createSmartRequest(
        int $userId,
        SmartRequestStatus $status,
        string $createdAt,
    ): SmartRequest {
        return SmartRequest::create([
            'userId' => $userId,
            'rawText' => 'Criar uma reuniao.',
            'status' => $status,
            'createdAt' => $createdAt,
        ]);
    }
}
