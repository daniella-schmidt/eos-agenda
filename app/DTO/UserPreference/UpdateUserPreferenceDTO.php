<?php

namespace App\DTO\UserPreference;

class UpdateUserPreferenceDTO
{
    public function __construct(
        public readonly array $attributes,
    ) {
    }
}