<?php

namespace App\Services\Contact;

use App\DTO\Contact\UpdateContactDTO;
use App\Models\Contact;

class UpdateContactService
{
    public function handle(Contact $contact, UpdateContactDTO $dto): Contact
    {
        $contact->update($dto->attributes);

        return $contact->refresh();
    }
}