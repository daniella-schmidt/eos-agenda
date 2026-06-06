<?php

namespace App\DTO\SmartRequest;

class ExtractedEventDataDTO
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $startAt,
        public readonly ?string $endAt,
        public readonly array $participants = [],
        public readonly array $missingFields = [],
        public readonly array $raw = [],
    ) {
    }

    public function hasMinimumData(): bool
    {
        return $this->title && $this->startAt && $this->endAt;
    }
}
