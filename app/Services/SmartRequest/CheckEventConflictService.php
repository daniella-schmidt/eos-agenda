<?php

namespace App\Services\SmartRequest;

use App\Models\Event;

class CheckEventConflictService
{
    public function handle(string $userId, string $startAt, string $endAt): bool
    {
        return Event::query()
            ->where('userId', $userId)
            ->where('status', 'confirmed')
            ->where('startAt', '<', $endAt)
            ->where('endAt', '>', $startAt)
            ->exists();
    }
}
