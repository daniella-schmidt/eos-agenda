<?php

namespace App\Services\UserPreference;

use App\Models\UserPreference;

class GetOrCreateUserPreferenceService
{
    public function handle(int $userId): UserPreference
    {
        return UserPreference::firstOrCreate(
            [
                'userId' => $userId,
            ],
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
}