<?php

namespace App\Policies;

use App\Models\EventReminder;
use App\Models\User;

class EventReminderPolicy
{
    public function view(User $user, EventReminder $eventReminder): bool
    {
        return $eventReminder->event?->userId === $user->id;
    }

    public function update(User $user, EventReminder $eventReminder): bool
    {
        return $eventReminder->event?->userId === $user->id;
    }

    public function delete(User $user, EventReminder $eventReminder): bool
    {
        return $eventReminder->event?->userId === $user->id;
    }

    public function markAsSent(User $user, EventReminder $eventReminder): bool
    {
        return $eventReminder->event?->userId === $user->id;
    }
}