<?php

namespace Tests\Feature\Event;

use App\Models\Calendar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_without_calendar_uses_the_users_default_calendar(): void
    {
        $user = User::factory()->create();
        $calendar = Calendar::create([
            'userId' => $user->id,
            'name' => 'Meu Calendário',
            'isDefault' => true,
            'isActive' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->postJson('/api/events', [
                'title' => 'Reunião de planejamento',
                'startAt' => '2026-07-01 14:00:00',
                'endAt' => '2026-07-01 15:00:00',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.calendarId', $calendar->id);

        $this->assertDatabaseHas('events', [
            'userId' => $user->id,
            'calendarId' => $calendar->id,
            'title' => 'Reunião de planejamento',
        ]);
    }

    public function test_event_without_calendar_requires_an_active_default_calendar(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/events', [
                'title' => 'Reunião de planejamento',
                'startAt' => '2026-07-01 14:00:00',
                'endAt' => '2026-07-01 15:00:00',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('calendarId');

        $this->assertDatabaseCount('events', 0);
    }

    public function test_event_tester_does_not_offer_a_calendar_for_event_creation(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get('/event-tester')
            ->assertOk()
            ->assertDontSee('id="create-calendar"', false);
    }
}
