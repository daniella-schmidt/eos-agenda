<?php

namespace App\DTO\Event;

class UpdateEventDTO
{
    public function __construct(
        public readonly array $attributes,
    ) {
    }
}