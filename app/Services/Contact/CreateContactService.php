<?php

namespace App\Services\Contact;

use App\DTO\Contact\CreateContactDTO;
use App\Models\Contact;

class CreateContactService
{
    public function handle(CreateContactDTO $dto): Contact
    {
        return Contact::create([
            'userId' => $dto->userId,
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'company' => $dto->company,
            'notes' => $dto->notes,
        ]);
    }
}