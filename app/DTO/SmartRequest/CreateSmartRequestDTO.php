<?php

namespace App\DTO\SmartRequest;

class CreateSmartRequestDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $rawText,
    ) {
    }
}
