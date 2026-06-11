<?php

namespace App\DTO\Contact;

class CreateContactDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly string $name,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $company,
        public readonly ?string $notes,
    ) {
    }
}