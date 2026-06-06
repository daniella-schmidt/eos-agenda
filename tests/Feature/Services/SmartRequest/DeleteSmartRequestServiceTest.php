<?php

namespace Tests\Feature\Services\SmartRequest;

use App\Enums\SmartRequestStatus;
use App\Models\SmartRequest;
use App\Models\User;
use App\Services\SmartRequest\DeleteSmartRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteSmartRequestServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_a_smart_request(): void
    {
        $smartRequest = SmartRequest::create([
            'userId' => User::factory()->create()->id,
            'rawText' => 'Criar uma reuniao amanha.',
            'status' => SmartRequestStatus::Pending,
        ]);

        app(DeleteSmartRequestService::class)->handle($smartRequest);

        $this->assertDatabaseMissing('smart_requests', [
            'id' => $smartRequest->id,
        ]);
    }
}
