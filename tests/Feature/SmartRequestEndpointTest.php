<?php

namespace Tests\Feature;

use App\Enums\SmartRequestStatus;
use App\Models\SmartRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmartRequestEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_their_smart_requests_by_status(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $expected = $this->createSmartRequest($user, SmartRequestStatus::Pending);
        $this->createSmartRequest($user, SmartRequestStatus::Completed);
        $this->createSmartRequest($otherUser, SmartRequestStatus::Pending);

        $response = $this
            ->actingAs($user)
            ->getJson('/api/smart-requests/status/pending');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $expected->id);
    }

    public function test_user_can_delete_their_smart_request(): void
    {
        $user = User::factory()->create();
        $smartRequest = $this->createSmartRequest($user, SmartRequestStatus::Pending);

        $this
            ->actingAs($user)
            ->deleteJson("/api/smart-requests/{$smartRequest->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('smart_requests', [
            'id' => $smartRequest->id,
        ]);
    }

    public function test_user_cannot_delete_another_users_smart_request(): void
    {
        $smartRequest = $this->createSmartRequest(
            User::factory()->create(),
            SmartRequestStatus::Pending,
        );

        $this
            ->actingAs(User::factory()->create())
            ->deleteJson("/api/smart-requests/{$smartRequest->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('smart_requests', [
            'id' => $smartRequest->id,
        ]);
    }

    private function createSmartRequest(
        User $user,
        SmartRequestStatus $status,
    ): SmartRequest {
        return SmartRequest::create([
            'userId' => $user->id,
            'rawText' => 'Criar uma reuniao.',
            'status' => $status,
        ]);
    }
}
