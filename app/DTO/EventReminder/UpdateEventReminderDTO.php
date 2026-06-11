<?php

namespace App\DTO\EventReminder;

class UpdateEventReminderDTO
{
    public function __construct(
        public readonly array $attributes,
    ) {
    }
}