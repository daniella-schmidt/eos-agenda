<?php

namespace App\Services\Contact;

use App\Models\Contact;
use Illuminate\Validation\ValidationException;

class DeleteContactService
{
    public function handle(Contact $contact): void
    {
        if ($contact->eventParticipants()->exists()) {
            throw ValidationException::withMessages([
                'contact' => [
                    'Este contato está vinculado a participantes de eventos e não pode ser excluído.',
                ],
            ]);
        }

        $contact->delete();
    }
}