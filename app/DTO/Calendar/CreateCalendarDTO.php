<?php

namespace App\DTO\Calendar;

class CreateCalendarDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly string $name,
        public readonly ?string $description = null,
        public readonly ?string $color = null,
        public readonly bool $isDefault = false,
    ) {
    }
}