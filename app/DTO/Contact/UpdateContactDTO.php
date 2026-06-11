<?php

namespace App\DTO\Contact;

class UpdateContactDTO
{
    public function __construct(
        public readonly array $attributes,
    ) {
    }
}